<?php
class ProductController extends Controller {
    private Product $product;
    private Category $category;

    public function __construct() {
        $this->product  = new Product();
        $this->category = new Category();
    }

    public function detail(): void {
        $id       = (int)($_GET['id'] ?? 0);
        $producto = $this->product->find($id);
        if (!$producto) {
            $this->redirect('store/index');
            return;
        }
        $relacionados = $this->product->byCategoria((int)$producto['categoria_id']);
        $relacionados = array_filter($relacionados, fn($p) => $p['id'] !== $id);
        $relacionados = array_slice(array_values($relacionados), 0, 4);
        $categorias   = $this->category->all();
        $this->render('layouts/header', ['title' => $producto['nombre'], 'categorias' => $categorias]);
        $this->render('product/detail', compact('producto', 'relacionados'));
        $this->render('layouts/footer');
    }
}
