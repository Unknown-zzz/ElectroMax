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

    public function dashboard(): void {
        requireAdmin();
        $stats      = $this->db->call('sp_admin_stats')->fetch();
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Dashboard Admin', 'categorias' => $categorias]);
        $this->render('admin/dashboard', compact('stats'));
        $this->render('layouts/footer');
    }

    public function productos(): void {
        requireAdmin();
        $productos  = $this->product->allAdmin();
        $categorias = $this->category->all();
        $marcas     = $this->brand->all();
        $this->render('layouts/header', ['title' => 'Admin - Productos', 'categorias' => $categorias]);
        $this->render('admin/productos', compact('productos', 'categorias', 'marcas'));
        $this->render('layouts/footer');
    }

    public function productoSave(): void {
        requireAdmin();
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
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->product->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Producto eliminado.'];
        $this->redirect('admin/productos');
    }

    public function pedidos(): void {
        requireAdmin();
        $pedidos    = $this->order->all();
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Admin - Pedidos', 'categorias' => $categorias]);
        $this->render('admin/pedidos', compact('pedidos'));
        $this->render('layouts/footer');
    }

    public function pedidoEstado(): void {
        requireAdmin();
        $id     = (int)($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? '';
        $validos = ['pendiente','procesando','enviado','entregado','cancelado'];
        if ($id && in_array($estado, $validos)) $this->order->updateEstado($id, $estado);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Estado actualizado.'];
        $this->redirect('admin/pedidos');
    }

    public function categorias(): void {
        requireAdmin();
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Admin - Categorías', 'categorias' => $categorias]);
        $this->render('admin/categorias', compact('categorias'));
        $this->render('layouts/footer');
    }

    public function categoriaSave(): void {
        requireAdmin();
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
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->category->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Categoría eliminada.'];
        $this->redirect('admin/categorias');
    }

    public function marcas(): void {
        requireAdmin();
        $marcas     = $this->brand->all();
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Admin - Marcas', 'categorias' => $categorias]);
        $this->render('admin/marcas', compact('marcas'));
        $this->render('layouts/footer');
    }

    public function marcaSave(): void {
        requireAdmin();
        $id     = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        if ($id > 0) $this->brand->update($id, $nombre);
        else         $this->brand->create($nombre);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Marca guardada.'];
        $this->redirect('admin/marcas');
    }

    public function marcaDelete(): void {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->brand->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Marca eliminada.'];
        $this->redirect('admin/marcas');
    }
}
