<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/helpers.php';

require_once __DIR__ . '/app/models/Category.php';
require_once __DIR__ . '/app/models/Brand.php';
require_once __DIR__ . '/app/models/Product.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Order.php';

require_once __DIR__ . '/app/controllers/StoreController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/ProductController.php';
require_once __DIR__ . '/app/controllers/CartController.php';
require_once __DIR__ . '/app/controllers/OrderController.php';
require_once __DIR__ . '/app/controllers/AdminController.php';

$store   = new StoreController();
$auth    = new AuthController();
$product = new ProductController();
$cart    = new CartController();
$order   = new OrderController();
$admin   = new AdminController();

$routes = [
    'store/index'           => [$store,   'index'],
    'store/categoria'       => [$store,   'categoria'],
    'store/buscar'          => [$store,   'buscar'],
    'product/detail'        => [$product, 'detail'],
    'auth/login'            => [$auth,    'login'],
    'auth/register'         => [$auth,    'register'],
    'auth/logout'           => [$auth,    'logout'],
    'cart/index'            => [$cart,    'index'],
    'cart/add'              => [$cart,    'add'],
    'cart/remove'           => [$cart,    'remove'],
    'cart/update'           => [$cart,    'update'],
    'cart/count'            => [$cart,    'count'],
    'cart/clear'            => [$cart,    'clear'],
    'order/checkout'        => [$order,   'checkout'],
    'order/confirm'         => [$order,   'confirm'],
    'order/gracias'         => [$order,   'gracias'],
    'order/history'         => [$order,   'history'],
    'admin/dashboard'       => [$admin,   'dashboard'],
    'admin/productos'       => [$admin,   'productos'],
    'admin/producto_save'   => [$admin,   'productoSave'],
    'admin/producto_delete' => [$admin,   'productoDelete'],
    'admin/pedidos'         => [$admin,   'pedidos'],
    'admin/pedido_estado'   => [$admin,   'pedidoEstado'],
    'admin/categorias'      => [$admin,   'categorias'],
    'admin/categoria_save'  => [$admin,   'categoriaSave'],
    'admin/categoria_delete'=> [$admin,   'categoriaDelete'],
    'admin/marcas'          => [$admin,   'marcas'],
    'admin/marca_save'      => [$admin,   'marcaSave'],
    'admin/marca_delete'    => [$admin,   'marcaDelete'],
];

$r = $_GET['r'] ?? 'store/index';

if (isset($routes[$r])) {
    call_user_func($routes[$r]);
} else {
    http_response_code(404);
    echo '<!DOCTYPE html><html><body style="font-family:sans-serif;text-align:center;padding:4rem">
          <h1>404 – Página no encontrada</h1>
          <a href="' . APP_URL . '/index.php?r=store/index">Volver al inicio</a>
          </body></html>';
}
