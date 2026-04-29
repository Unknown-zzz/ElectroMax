<!-- Hero Banner -->
<div class="hero-banner">
  <div class="container">
    <div class="row align-items-center min-vh-40">
      <div class="col-md-6 text-white py-5">
        <p class="badge bg-warning text-dark fs-6 mb-2">Ofertas especiales</p>
        <h1 class="display-4 fw-bold">Tecnología al<br><span class="text-warning">mejor precio</span></h1>
        <p class="lead text-light">Televisores, electrodomésticos, celulares y más. Envío a domicilio en toda la ciudad.</p>
        <a href="<?= APP_URL ?>/index.php?r=store/buscar&q=" class="btn btn-warning btn-lg fw-bold px-4">
          <i class="bi bi-grid-fill me-2"></i>Ver todos los productos
        </a>
      </div>
      <div class="col-md-6 text-center py-4">
        <i class="bi bi-tv hero-icon"></i>
      </div>
    </div>
  </div>
</div>

<!-- Categorías -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-title">Categorías</h2>
    <div class="row g-3">
      <?php foreach ($categorias as $cat): ?>
      <div class="col-6 col-md-3">
        <a href="<?= APP_URL ?>/index.php?r=store/categoria&id=<?= $cat['id'] ?>" class="category-card text-decoration-none">
          <div class="card h-100 border-0 text-center p-3 hover-lift">
            <i class="bi <?= e($cat['icono']) ?> cat-icon"></i>
            <h6 class="mt-2 mb-0 fw-semibold"><?= e($cat['nombre']) ?></h6>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Productos Destacados -->
<section class="py-5">
  <div class="container">
    <h2 class="section-title">Productos Destacados</h2>
    <?php if (empty($destacados)): ?>
      <p class="text-muted">No hay productos destacados aún.</p>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($destacados as $p): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <?php include __DIR__ . '/../partials/product_card.php'; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- Banner promocional -->
<section class="promo-banner py-5">
  <div class="container text-center text-white">
    <h3 class="fw-bold"><i class="bi bi-truck me-2"></i>Envío gratis en compras mayores a Bs. 500</h3>
    <p class="mb-0 text-warning">Entrega en 24-48 horas hábiles en toda la ciudad.</p>
  </div>
</section>
