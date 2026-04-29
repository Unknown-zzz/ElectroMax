<?php
$imgSrc = !empty($p['imagen'])
    ? APP_URL . '/resources/imagenes/' . e($p['imagen'])
    : APP_URL . '/public/img/no-image.svg';
$precio     = (float)$p['precio'];
$oferta     = $p['precio_oferta'] ? (float)$p['precio_oferta'] : null;
$precioFinal = $oferta ?? $precio;
$descuento   = $oferta ? round((1 - $oferta / $precio) * 100) : 0;
?>
<div class="card product-card h-100 border-0 shadow-sm">
  <?php if ($descuento > 0): ?>
    <span class="badge bg-danger product-badge">-<?= $descuento ?>%</span>
  <?php elseif ($p['destacado']): ?>
    <span class="badge bg-warning text-dark product-badge">Destacado</span>
  <?php endif; ?>
  <div class="product-img-wrap position-relative">
    <a href="<?= APP_URL ?>/index.php?r=product/detail&id=<?= $p['id'] ?>">
      <img src="<?= $imgSrc ?>" class="card-img-top product-img" alt="<?= e($p['nombre']) ?>"
           onerror="this.src='<?= APP_URL ?>/public/img/no-image.svg'">
    </a>
    <div class="product-overlay">
      <button class="btn btn-light btn-quickview fw-semibold"
              data-id="<?= $p['id'] ?>"
              data-bs-toggle="modal" data-bs-target="#productModal">
        <i class="bi bi-eye me-1"></i>Vista Rápida
      </button>
    </div>
  </div>
  <div class="card-body d-flex flex-column p-3 pt-2">
    <p class="text-muted small mb-1"><?= e($p['marca_nombre'] ?? '') ?></p>
    <a href="<?= APP_URL ?>/index.php?r=product/detail&id=<?= $p['id'] ?>" class="text-decoration-none">
      <h6 class="card-title product-name fw-semibold"><?= e($p['nombre']) ?></h6>
    </a>
    <div class="mt-auto">
      <div class="price-block">
        <?php if ($oferta): ?>
          <span class="price-original text-muted text-decoration-line-through small"><?= formatPrice($precio) ?></span><br>
        <?php endif; ?>
        <span class="price-final fw-bold"><?= formatPrice($precioFinal) ?></span>
      </div>
      <button class="btn btn-primary btn-sm w-100 mt-2 btn-add-cart"
              data-id="<?= $p['id'] ?>"
              data-name="<?= e($p['nombre']) ?>"
              <?= $p['stock'] <= 0 ? 'disabled' : '' ?>>
        <i class="bi bi-cart-plus me-1"></i>
        <?= $p['stock'] <= 0 ? 'Sin stock' : 'Agregar' ?>
      </button>
    </div>
  </div>
</div>
