<?php $total = array_reduce($carrito, fn($s,$i) => $s + $i['precio']*$i['cantidad'], 0); ?>
<div class="container py-4">
  <h3 class="fw-bold mb-4"><i class="bi bi-credit-card me-2 text-primary"></i>Finalizar Compra</h3>
  <div class="row g-4">
    <!-- Formulario -->
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3"><i class="bi bi-person-lines-fill me-2"></i>Datos de entrega</h5>
          <form method="POST" action="<?= APP_URL ?>/index.php?r=order/confirm" id="checkoutForm">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold">Nombre completo *</label>
                <input type="text" name="nombre" class="form-control" required
                       value="<?= isLoggedIn() ? e($_SESSION['user_nombre']) : '' ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email *</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Teléfono *</label>
                <input type="text" name="telefono" class="form-control" required placeholder="+591 7-XXXXXXX">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Dirección de entrega *</label>
                <textarea name="direccion" class="form-control" rows="2" required
                          placeholder="Calle, número, barrio, ciudad"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Notas adicionales</label>
                <textarea name="notas" class="form-control" rows="2"
                          placeholder="Instrucciones especiales para la entrega..."></textarea>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-4">
              <i class="bi bi-bag-check me-2"></i>Confirmar Pedido &mdash; <?= formatPrice($total) ?>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Resumen -->
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3">Tu pedido</h5>
          <?php foreach ($carrito as $item): ?>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small"><?= e($item['nombre']) ?> <span class="text-muted">×<?= $item['cantidad'] ?></span></span>
            <span class="fw-semibold small"><?= formatPrice($item['precio'] * $item['cantidad']) ?></span>
          </div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between fw-bold fs-5">
            <span>Total</span>
            <span class="text-primary"><?= formatPrice($total) ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
