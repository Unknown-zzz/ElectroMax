<?php
class AdminController extends Controller {
    private Product  $product;
    private Category $category;
    private Brand    $brand;
    private Order    $order;
    private Database $db;

    public function __construct() {
        $this->product  = new Product();
        $this->category = new Category();
        $this->brand    = new Brand();
        $this->order    = new Order();
        $this->db       = Database::getInstance();
    }

    // ── Dashboard ──────────────────────────────────────────────────────────
    public function dashboard(): void {
        requirePermiso('ver_dashboard');
        $categorias = $this->category->all();
        $rol = currentRole();

        $stats = null;
        $statsVendedor  = null;
        $statsInventario = null;

        if ($rol === 'admin') {
            $stats = $this->db->call('sp_admin_stats')->fetch();
        } elseif ($rol === 'vendedor') {
            $statsVendedor = $this->db->query(
                "SELECT
                    (SELECT COUNT(*) FROM pedidos) AS total_pedidos,
                    (SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente') AS pendientes,
                    (SELECT COUNT(*) FROM pedidos WHERE estado = 'procesando') AS procesando,
                    (SELECT COALESCE(SUM(total),0) FROM pedidos WHERE estado != 'cancelado') AS total_ventas"
            )->fetch();
        } elseif ($rol === 'inventario') {
            $statsInventario = $this->db->query(
                "SELECT
                    (SELECT COUNT(*) FROM productos WHERE activo = 1) AS total_productos,
                    (SELECT COUNT(*) FROM productos WHERE activo = 1 AND stock = 0) AS sin_stock,
                    (SELECT COUNT(*) FROM productos WHERE activo = 1 AND stock > 0 AND stock <= 5) AS stock_bajo,
                    (SELECT COUNT(*) FROM categorias WHERE activo = 1) AS total_categorias"
            )->fetch();
        }

        $this->render('layouts/header', ['title' => 'Dashboard', 'categorias' => $categorias]);
        $this->render('admin/dashboard', compact('stats', 'statsVendedor', 'statsInventario'));
        $this->render('layouts/footer');
    }

    // ── Productos ──────────────────────────────────────────────────────────
    public function productos(): void {
        requirePermiso('ver_productos');
        $productos  = $this->product->allAdmin();
        $categorias = $this->category->all();
        $marcas     = $this->brand->all();
        $this->render('layouts/header', ['title' => 'Admin - Productos', 'categorias' => $categorias]);
        $this->render('admin/productos', compact('productos', 'categorias', 'marcas'));
        $this->render('layouts/footer');
    }

    public function productoSave(): void {
        requirePermiso('editar_productos');
        $id      = (int)($_POST['id'] ?? 0);
        $imgPath = __DIR__ . '/../../resources/imagenes';
        $imagen  = uploadImage('imagen', $imgPath);
        $data    = [
            'nombre'        => trim($_POST['nombre'] ?? ''),
            'descripcion'   => trim($_POST['descripcion'] ?? ''),
            'precio'        => (float)($_POST['precio'] ?? 0),
            'precio_oferta' => $_POST['precio_oferta'] !== '' ? (float)$_POST['precio_oferta'] : null,
            'stock'         => (int)($_POST['stock'] ?? 0),
            'imagen'        => $imagen,
            'categoria_id'  => (int)($_POST['categoria_id'] ?? 0),
            'marca_id'      => (int)($_POST['marca_id'] ?? 0),
            'destacado'     => isset($_POST['destacado']) ? 1 : 0,
        ];
        if ($id > 0) $this->product->update($id, $data);
        else         $this->product->create($data);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Producto guardado.'];
        $this->redirect('admin/productos');
    }

    public function productoDelete(): void {
        requirePermiso('editar_productos');
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->product->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Producto eliminado.'];
        $this->redirect('admin/productos');
    }

    // ── Pedidos ────────────────────────────────────────────────────────────
    public function pedidos(): void {
        requirePermiso('ver_pedidos');
        $pedidos    = $this->order->all();
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Admin - Pedidos', 'categorias' => $categorias]);
        $this->render('admin/pedidos', compact('pedidos'));
        $this->render('layouts/footer');
    }

    public function pedidoEstado(): void {
        requirePermiso('editar_pedidos');
        $id     = (int)($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? '';
        $validos = ['pendiente','procesando','enviado','entregado','cancelado'];
        if ($id && in_array($estado, $validos)) $this->order->updateEstado($id, $estado);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Estado actualizado.'];
        $this->redirect('admin/pedidos');
    }

    // ── Categorías ─────────────────────────────────────────────────────────
    public function categorias(): void {
        requirePermiso('ver_categorias');
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Admin - Categorías', 'categorias' => $categorias]);
        $this->render('admin/categorias', compact('categorias'));
        $this->render('layouts/footer');
    }

    public function categoriaSave(): void {
        requirePermiso('editar_categorias');
        $id          = (int)($_POST['id'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $icono       = trim($_POST['icono'] ?? 'bi-box');
        if ($id > 0) $this->category->update($id, $nombre, $descripcion, $icono);
        else         $this->category->create($nombre, $descripcion, $icono);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Categoría guardada.'];
        $this->redirect('admin/categorias');
    }

    public function categoriaDelete(): void {
        requirePermiso('editar_categorias');
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->category->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Categoría eliminada.'];
        $this->redirect('admin/categorias');
    }

    // ── Marcas ─────────────────────────────────────────────────────────────
    public function marcas(): void {
        requirePermiso('ver_marcas');
        $marcas     = $this->brand->all();
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Admin - Marcas', 'categorias' => $categorias]);
        $this->render('admin/marcas', compact('marcas'));
        $this->render('layouts/footer');
    }

    public function marcaSave(): void {
        requirePermiso('editar_marcas');
        $id     = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        if ($id > 0) $this->brand->update($id, $nombre);
        else         $this->brand->create($nombre);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Marca guardada.'];
        $this->redirect('admin/marcas');
    }

    public function marcaDelete(): void {
        requirePermiso('editar_marcas');
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->brand->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Marca eliminada.'];
        $this->redirect('admin/marcas');
    }

    // ── Gestión de Usuarios / Roles ────────────────────────────────────────
    public function usuarios(): void {
        requirePermiso('gestionar_usuarios');
        $categorias = $this->category->all();
        $staff = $this->db->query(
            "SELECT id, nombre, email, rol, activo, created_at FROM usuarios
             WHERE rol != 'cliente' ORDER BY rol, nombre"
        )->fetchAll();
        $clientes = $this->db->query(
            "SELECT id, nombre, email FROM usuarios WHERE rol = 'cliente' AND activo = 1 ORDER BY nombre"
        )->fetchAll();
        $this->render('layouts/header', ['title' => 'Admin - Usuarios', 'categorias' => $categorias]);
        $this->render('admin/usuarios', compact('staff', 'clientes'));
        $this->render('layouts/footer');
    }

    public function usuarioRolSave(): void {
        requirePermiso('gestionar_usuarios');
        $id  = (int)($_POST['id'] ?? 0);
        $rol = $_POST['rol'] ?? '';
        $validos = ['cliente', 'vendedor', 'inventario', 'admin'];
        if ($id && in_array($rol, $validos)) {
            $this->db->query('UPDATE usuarios SET rol = ? WHERE id = ?', [$rol, $id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Rol actualizado correctamente.'];
        }
        $this->redirect('admin/usuarios');
    }

    public function usuarioToggle(): void {
        requirePermiso('gestionar_usuarios');
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->db->query(
                'UPDATE usuarios SET activo = IF(activo=1,0,1) WHERE id = ? AND rol != \'admin\'',
                [$id]
            );
        }
        $this->redirect('admin/usuarios');
    }

    // ── Chat ───────────────────────────────────────────────────────────────
    public function chat(): void {
        requirePermiso('chat');

        // Generar token de un solo uso para autenticar el WebSocket
        $token = bin2hex(random_bytes(32));
        $expMs = (time() + 90) * 1000; // válido 90 segundos

        // Limpiar tokens vencidos y guardar el nuevo
        $this->db->query('DELETE FROM chat_tokens WHERE expires_at < ?', [time() * 1000]);
        $this->db->query(
            'INSERT INTO chat_tokens (token, usuario_id, expires_at) VALUES (?, ?, ?)',
            [$token, $_SESSION['user_id'], $expMs]
        );

        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Chat de Equipo', 'categorias' => $categorias]);
        $this->render('admin/chat', compact('token'));
        $this->render('layouts/footer');
    }
}
