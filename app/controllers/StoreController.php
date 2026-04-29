<?php
class StoreController extends Controller {
    private Product $product;
    private Category $category;

    public function __construct() {
        $this->product  = new Product();
        $this->category = new Category();
    }

    public function index(): void {
        $destacados  = $this->product->destacados();
        $categorias  = $this->category->all();
        $this->render('layouts/header', ['title' => 'Inicio', 'categorias' => $categorias]);
        $this->render('store/index', compact('destacados', 'categorias'));
        $this->render('layouts/footer');
    }

    public function categoria(): void {
        $catId      = (int)($_GET['id'] ?? 0);
        $categorias = $this->category->all();
        $catActual  = array_filter($categorias, fn($c) => $c['id'] === $catId);
        $catActual  = $catActual ? array_values($catActual)[0] : null;
        $productos  = $this->product->byCategoria($catId);
        $this->render('layouts/header', ['title' => $catActual['nombre'] ?? 'Categoría', 'categorias' => $categorias]);
        $this->render('store/categoria', compact('productos', 'catActual', 'categorias'));
        $this->render('layouts/footer');
    }

    public function buscar(): void {
        $q          = trim($_GET['q'] ?? '');
        $categorias = $this->category->all();
        $productos  = $q ? $this->product->buscar($q) : [];
        $this->render('layouts/header', ['title' => 'Buscar: ' . $q, 'categorias' => $categorias]);
        $this->render('store/buscar', compact('productos', 'q', 'categorias'));
        $this->render('layouts/footer');
    }
}
