<div class="container py-4">
  <h4 class="fw-bold mb-1">Resultados para: <span class="text-primary">"<?= e($q) ?>"</span></h4>
  <p class="text-muted mb-4"><?= count($productos) ?> resultado(s) encontrado(s)</p>

  <?php if (empty($productos)): ?>
    <div class="text-center py-5">
      <i class="bi bi-search display-1 text-muted"></i>
      <p class="mt-3 text-muted">No se encontraron productos para "<?= e($q) ?>".</p>
      <a href="<?= APP_URL ?>/index.php?r=store/index" class="btn btn-primary">Volver al inicio</a>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($productos as $p): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <?php include __DIR__ . '/../partials/product_card.php'; ?>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
