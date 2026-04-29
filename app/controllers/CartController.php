<?php
class CartController extends Controller {
    private Product $product;
    private Category $category;

    public function __construct() {
        $this->product  = new Product();
        $this->category = new Category();
    }

    public function index(): void {
        $carrito    = $_SESSION['carrito'] ?? [];
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Carrito', 'categorias' => $categorias]);
        $this->render('cart/index', compact('carrito'));
        $this->render('layouts/footer');
    }

    public function add(): void {
        $id       = (int)($_POST['id'] ?? 0);
        $producto = $this->product->find($id);
        if (!$producto) { $this->json(['ok' => false, 'msg' => 'Producto no encontrado'], 404); return; }
        if ($producto['stock'] <= 0) { $this->json(['ok' => false, 'msg' => 'Sin stock'], 409); return; }

        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
        $carrito = &$_SESSION['carrito'];
        if (isset($carrito[$id])) {
            if ($carrito[$id]['cantidad'] >= $producto['stock']) {
                $this->json(['ok' => false, 'msg' => 'Stock máximo alcanzado'], 409); return;
            }
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                'id'       => $id,
                'nombre'   => $producto['nombre'],
                'precio'   => (float)($producto['precio_oferta'] ?: $producto['precio']),
                'imagen'   => $producto['imagen'],
                'cantidad' => 1,
            ];
        }
        $this->json(['ok' => true, 'count' => cartCount()]);
    }

    public function remove(): void {
        $id = (int)($_POST['id'] ?? 0);
        unset($_SESSION['carrito'][$id]);
        $this->json(['ok' => true, 'count' => cartCount()]);
    }

    public function update(): void {
        $id  = (int)($_POST['id'] ?? 0);
        $op  = $_POST['op'] ?? '';
        $carrito = &$_SESSION['carrito'];
        if (!isset($carrito[$id])) { $this->json(['ok' => false], 404); return; }
        $producto = $this->product->find($id);
        if ($op === 'inc') {
            if ($carrito[$id]['cantidad'] < ($producto['stock'] ?? 99)) $carrito[$id]['cantidad']++;
        } elseif ($op === 'dec') {
            $carrito[$id]['cantidad']--;
            if ($carrito[$id]['cantidad'] <= 0) unset($carrito[$id]);
        }
        $subtotal = isset($carrito[$id]) ? $carrito[$id]['precio'] * $carrito[$id]['cantidad'] : 0;
        $this->json(['ok' => true, 'count' => cartCount(), 'subtotal' => $subtotal]);
    }

    public function count(): void {
        $this->json(['count' => cartCount()]);
    }

    public function clear(): void {
        $_SESSION['carrito'] = [];
        $this->json(['ok' => true]);
    }
}
