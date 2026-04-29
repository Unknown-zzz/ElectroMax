<?php
class OrderController extends Controller {
    private Order $order;
    private Category $category;

    public function __construct() {
        $this->order    = new Order();
        $this->category = new Category();
    }

    public function checkout(): void {
        $carrito = $_SESSION['carrito'] ?? [];
        if (empty($carrito)) { $this->redirect('cart/index'); return; }
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Finalizar Compra', 'categorias' => $categorias]);
        $this->render('checkout/index', compact('carrito'));
        $this->render('layouts/footer');
    }

    public function confirm(): void {
        $carrito = $_SESSION['carrito'] ?? [];
        if (empty($carrito) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cart/index'); return;
        }
        $total = array_reduce($carrito, fn($s, $i) => $s + $i['precio'] * $i['cantidad'], 0);
        $data  = [
            'usuario_id'     => $_SESSION['user_id'] ?? null,
            'total'          => $total,
            'nombre_cliente' => trim($_POST['nombre'] ?? ''),
            'email_cliente'  => trim($_POST['email'] ?? ''),
            'telefono'       => trim($_POST['telefono'] ?? ''),
            'direccion'      => trim($_POST['direccion'] ?? ''),
            'notas'          => trim($_POST['notas'] ?? ''),
        ];
        try {
            $pedidoId = $this->order->create($data);
            foreach ($carrito as $item) {
                $this->order->addDetail($pedidoId, $item['id'], $item['cantidad'], $item['precio']);
            }
            $_SESSION['carrito']        = [];
            $_SESSION['ultimo_pedido']  = $pedidoId;
            $_SESSION['flash']          = ['type' => 'success', 'msg' => "¡Pedido #{$pedidoId} registrado con éxito!"];
            $this->redirect('order/gracias');
        } catch (Exception $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Error al procesar el pedido. Intenta de nuevo.'];
            $this->redirect('order/checkout');
        }
    }

    public function gracias(): void {
        $pedidoId   = $_SESSION['ultimo_pedido'] ?? 0;
        $detalle    = $pedidoId ? $this->order->getDetalle($pedidoId) : [];
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Pedido Confirmado', 'categorias' => $categorias]);
        $this->render('checkout/gracias', compact('pedidoId', 'detalle'));
        $this->render('layouts/footer');
    }

    public function history(): void {
        requireLogin();
        $pedidos    = $this->order->byUsuario((int)$_SESSION['user_id']);
        $categorias = $this->category->all();
        $this->render('layouts/header', ['title' => 'Mis Pedidos', 'categorias' => $categorias]);
        $this->render('checkout/history', compact('pedidos'));
        $this->render('layouts/footer');
    }
}
