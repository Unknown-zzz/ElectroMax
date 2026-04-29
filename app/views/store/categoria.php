<div class="container py-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= APP_URL ?>/index.php?r=store/index">Inicio</a></li>
      <li class="breadcrumb-item active"><?= e($catActual['nombre'] ?? 'Categoría') ?></li>
    </ol>
  </nav>

  <div class="d-flex align-items-center mb-4">
    <i class="bi <?= e($catActual['icono'] ?? 'bi-box') ?> fs-2 text-primary me-3"></i>
    <div>
      <h2 class="mb-0 fw-bold"><?= e($catActual['nombre'] ?? 'Categoría') ?></h2>
      <p class="text-muted mb-0 small"><?= e($catActual['descripcion'] ?? '') ?></p>
    </div>
  </div>

  <?php if (empty($productos)): ?>
    <div class="text-center py-5">
      <i class="bi bi-box-seam display-1 text-muted"></i>
      <p class="mt-3 text-muted">No hay productos en esta categoría.</p>
    </div>
  <?php else: ?>
    <p class="text-muted mb-3"><?= count($productos) ?> producto(s) encontrado(s)</p>
    <div class="row g-4">
      <?php foreach ($productos as $p): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <?php include __DIR__ . '/../partials/product_card.php'; ?>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
