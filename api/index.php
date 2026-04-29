<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/models/Product.php';
require_once __DIR__ . '/../app/models/Category.php';
require_once __DIR__ . '/../app/models/Brand.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Order.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$method   = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';
$action   = $_GET['action'] ?? '';

function jsonResponse(mixed $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode(['success' => $code < 400, 'data' => $data], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function jsonError(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

function getBody(): array {
    $raw = file_get_contents('php://input');
    return $raw ? (json_decode($raw, true) ?? []) : [];
}

try {
    $product  = new Product();
    $category = new Category();
    $brand    = new Brand();
    $user     = new User();
    $order    = new Order();

    match(true) {

        // GET /api/?endpoint=productos
        $method === 'GET' && $endpoint === 'productos' && !$action => (function() use ($product) {
            if (isset($_GET['id'])) {
                $p = $product->find((int)$_GET['id']);
                $p ? jsonResponse($p) : jsonError('Producto no encontrado', 404);
            }
            if (isset($_GET['categoria_id'])) {
                jsonResponse($product->byCategoria((int)$_GET['categoria_id']));
            }
            if (isset($_GET['q'])) {
                jsonResponse($product->buscar(trim($_GET['q'])));
            }
            if (isset($_GET['destacados'])) {
                jsonResponse($product->destacados());
            }
            jsonResponse($product->all());
        })(),

        // GET /api/?endpoint=categorias
        $method === 'GET' && $endpoint === 'categorias' => (function() use ($category) {
            jsonResponse($category->all());
        })(),

        // GET /api/?endpoint=marcas
        $method === 'GET' && $endpoint === 'marcas' => (function() use ($brand) {
            jsonResponse($brand->all());
        })(),

        // GET /api/?endpoint=carrito
        $method === 'GET' && $endpoint === 'carrito' => (function() {
            $carrito = $_SESSION['carrito'] ?? [];
            $total   = array_reduce($carrito, fn($s, $i) => $s + $i['precio'] * $i['cantidad'], 0);
            jsonResponse(['items' => array_values($carrito), 'total' => $total, 'count' => cartCount()]);
        })(),

        // POST /api/?endpoint=carrito&action=agregar
        $method === 'POST' && $endpoint === 'carrito' && $action === 'agregar' => (function() use ($product) {
            $body = getBody();
            $id   = (int)($body['producto_id'] ?? 0);
            $p    = $product->find($id);
            if (!$p)           jsonError('Producto no encontrado', 404);
            if ($p['stock'] <= 0) jsonError('Sin stock disponible', 409);
            if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
            $carrito = &$_SESSION['carrito'];
            if (isset($carrito[$id])) {
                if ($carrito[$id]['cantidad'] >= $p['stock']) jsonError('Stock máximo alcanzado', 409);
                $carrito[$id]['cantidad']++;
            } else {
                $carrito[$id] = [
                    'id'       => $id,
                    'nombre'   => $p['nombre'],
                    'precio'   => (float)($p['precio_oferta'] ?: $p['precio']),
                    'imagen'   => $p['imagen'],
                    'cantidad' => 1,
                ];
            }
            jsonResponse(['count' => cartCount(), 'item' => $carrito[$id]]);
        })(),

        // POST /api/?endpoint=carrito&action=quitar
        $method === 'POST' && $endpoint === 'carrito' && $action === 'quitar' => (function() {
            $body = getBody();
            $id   = (int)($body['producto_id'] ?? 0);
            unset($_SESSION['carrito'][$id]);
            jsonResponse(['count' => cartCount()]);
        })(),

        // POST /api/?endpoint=carrito&action=actualizar
        $method === 'POST' && $endpoint === 'carrito' && $action === 'actualizar' => (function() use ($product) {
            $body     = getBody();
            $id       = (int)($body['producto_id'] ?? 0);
            $cantidad = (int)($body['cantidad'] ?? 1);
            $carrito  = &$_SESSION['carrito'];
            if (!isset($carrito[$id])) jsonError('Item no encontrado en carrito', 404);
            $p = $product->find($id);
            if ($cantidad <= 0) {
                unset($carrito[$id]);
            } else {
                $carrito[$id]['cantidad'] = min($cantidad, $p['stock'] ?? 99);
            }
            jsonResponse(['count' => cartCount()]);
        })(),

        // DELETE /api/?endpoint=carrito
        $method === 'DELETE' && $endpoint === 'carrito' => (function() {
            $_SESSION['carrito'] = [];
            jsonResponse(['count' => 0]);
        })(),

        // POST /api/?endpoint=auth&action=login
        $method === 'POST' && $endpoint === 'auth' && $action === 'login' => (function() use ($user) {
            $body  = getBody();
            $email = trim($body['email'] ?? '');
            $pass  = $body['password'] ?? '';
            $u     = $user->login($email, $pass);
            if (!$u) jsonError('Credenciales incorrectas', 401);
            $_SESSION['user_id']     = $u['id'];
            $_SESSION['user_nombre'] = $u['nombre'];
            $_SESSION['user_rol']    = $u['rol'];
            jsonResponse(['id' => $u['id'], 'nombre' => $u['nombre'], 'email' => $u['email'], 'rol' => $u['rol']]);
        })(),

        // POST /api/?endpoint=auth&action=registro
        $method === 'POST' && $endpoint === 'auth' && $action === 'registro' => (function() use ($user) {
            $body     = getBody();
            $nombre   = trim($body['nombre'] ?? '');
            $email    = trim($body['email'] ?? '');
            $password = $body['password'] ?? '';
            $telefono = trim($body['telefono'] ?? '');
            if (!$nombre || !$email || strlen($password) < 6) jsonError('Datos inválidos. La contraseña debe tener al menos 6 caracteres.');
            $result = $user->register($nombre, $email, $password, $telefono);
            if ((int)$result['p_id'] === 0) jsonError($result['p_error'], 409);
            jsonResponse(['id' => $result['p_id'], 'mensaje' => 'Cuenta creada exitosamente']);
        })(),

        // POST /api/?endpoint=pedidos
        $method === 'POST' && $endpoint === 'pedidos' => (function() use ($order) {
            $carrito = $_SESSION['carrito'] ?? [];
            if (empty($carrito)) jsonError('El carrito está vacío', 400);
            $body  = getBody();
            $total = array_reduce($carrito, fn($s, $i) => $s + $i['precio'] * $i['cantidad'], 0);
            $data  = [
                'usuario_id'     => $_SESSION['user_id'] ?? null,
                'total'          => $total,
                'nombre_cliente' => trim($body['nombre'] ?? ''),
                'email_cliente'  => trim($body['email'] ?? ''),
                'telefono'       => trim($body['telefono'] ?? ''),
                'direccion'      => trim($body['direccion'] ?? ''),
                'notas'          => trim($body['notas'] ?? ''),
            ];
            if (!$data['nombre_cliente'] || !$data['email_cliente'] || !$data['direccion']) {
                jsonError('Faltan datos obligatorios: nombre, email, direccion');
            }
            $pedidoId = $order->create($data);
            foreach ($carrito as $item) {
                $order->addDetail($pedidoId, $item['id'], $item['cantidad'], $item['precio']);
            }
            $_SESSION['carrito'] = [];
            jsonResponse(['pedido_id' => $pedidoId, 'total' => $total]);
        })(),

        // GET /api/?endpoint=pedidos (requiere sesión)
        $method === 'GET' && $endpoint === 'pedidos' => (function() use ($order) {
            if (!isLoggedIn()) jsonError('No autenticado', 401);
            if (isAdmin()) {
                jsonResponse($order->all());
            }
            jsonResponse($order->byUsuario((int)$_SESSION['user_id']));
        })(),

        default => jsonError("Endpoint '{$endpoint}' no encontrado. Consulta la documentación en /api/swagger-ui/", 404),
    };

} catch (Exception $e) {
    jsonError('Error interno del servidor: ' . $e->getMessage(), 500);
}
