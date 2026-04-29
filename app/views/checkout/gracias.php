<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 text-center">
      <div class="card border-0 shadow">
        <div class="card-body p-5">
          <i class="bi bi-check-circle-fill text-success display-1"></i>
          <h3 class="fw-bold mt-3">¡Pedido Confirmado!</h3>
          <p class="text-muted">Tu pedido <strong>#<?= $pedidoId ?></strong> ha sido registrado con éxito.</p>
          <p class="text-muted small">Nos pondremos en contacto para coordinar la entrega.</p>
          <?php if (!empty($detalle)): ?>
          <div class="text-start mt-3">
            <h6 class="fw-bold">Detalle:</h6>
            <?php foreach ($detalle as $d): ?>
            <div class="d-flex justify-content-between small py-1 border-bottom">
              <span><?= e($d['producto_nombre']) ?> ×<?= $d['cantidad'] ?></span>
              <span><?= formatPrice($d['precio_unitario'] * $d['cantidad']) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <a href="<?= APP_URL ?>/index.php?r=store/index" class="btn btn-primary mt-4 px-4">
            <i class="bi bi-house me-2"></i>Volver a la tienda
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
