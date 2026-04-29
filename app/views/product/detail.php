<?php
$imgSrc = !empty($producto['imagen'])
    ? APP_URL . '/resources/imagenes/' . e($producto['imagen'])
    : APP_URL . '/public/img/no-image.svg';
$precio      = (float)$producto['precio'];
$oferta      = $producto['precio_oferta'] ? (float)$producto['precio_oferta'] : null;
$precioFinal = $oferta ?? $precio;
$descuento   = $oferta ? round((1 - $oferta / $precio) * 100) : 0;
?>
<div class="container py-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= APP_URL ?>/index.php?r=store/index">Inicio</a></li>
      <li class="breadcrumb-item">
        <a href="<?= APP_URL ?>/index.php?r=store/categoria&id=<?= $producto['categoria_id'] ?>">
          <?= e($producto['categoria_nombre']) ?>
        </a>
      </li>
      <li class="breadcrumb-item active"><?= e($producto['nombre']) ?></li>
    </ol>
  </nav>

  <div class="row g-4">
    <!-- Imagen -->
    <div class="col-md-5">
      <div class="product-detail-img-wrap">
        <img src="<?= $imgSrc ?>" class="img-fluid rounded" alt="<?= e($producto['nombre']) ?>"
             onerror="this.src='<?= APP_URL ?>/public/img/no-image.svg'">
        <?php if ($descuento > 0): ?>
          <span class="badge bg-danger fs-6 position-absolute top-0 end-0 m-2">-<?= $descuento ?>% OFF</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Info -->
    <div class="col-md-7">
      <p class="text-muted mb-1">
        <i class="bi bi-tag-fill text-primary me-1"></i><?= e($producto['marca_nombre'] ?? 'Sin marca') ?>
        &nbsp;|&nbsp; <?= e($producto['categoria_nombre']) ?>
      </p>
      <h2 class="fw-bold"><?= e($producto['nombre']) ?></h2>

      <div class="price-block-detail my-3">
        <?php if ($oferta): ?>
          <span class="text-muted text-decoration-line-through fs-5 me-2"><?= formatPrice($precio) ?></span>
        <?php endif; ?>
        <span class="price-detail text-primary fw-bold"><?= formatPrice($precioFinal) ?></span>
        <?php if ($oferta): ?>
          <span class="badge bg-danger ms-2">Oferta</span>
        <?php endif; ?>
      </div>

      <p class="text-muted"><?= nl2br(e($producto['descripcion'])) ?></p>

      <div class="d-flex align-items-center gap-3 mb-4">
        <?php if ($producto['stock'] > 0): ?>
          <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>En stock (<?= $producto['stock'] ?>)</span>
        <?php else: ?>
          <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Sin stock</span>
        <?php endif; ?>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary btn-lg btn-add-cart"
                data-id="<?= $producto['id'] ?>"
                data-name="<?= e($producto['nombre']) ?>"
                <?= $producto['stock'] <= 0 ? 'disabled' : '' ?>>
          <i class="bi bi-cart-plus me-2"></i>Agregar al carrito
        </button>
        <a href="<?= APP_URL ?>/index.php?r=cart/index" class="btn btn-outline-primary btn-lg">
          <i class="bi bi-cart3"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- Relacionados -->
  <?php if (!empty($relacionados)): ?>
  <hr class="my-5">
  <h4 class="fw-bold mb-4">También te puede interesar</h4>
  <div class="row g-4">
    <?php foreach ($relacionados as $p): ?>
    <div class="col-6 col-md-3">
      <?php include __DIR__ . '/../partials/product_card.php'; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
