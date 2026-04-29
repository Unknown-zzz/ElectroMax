<div class="container py-4">
  <h3 class="fw-bold mb-4"><i class="bi bi-cart3 me-2 text-primary"></i>Mi Carrito</h3>

  <?php if (empty($carrito)): ?>
  <div class="text-center py-5">
    <i class="bi bi-cart-x display-1 text-muted"></i>
    <p class="mt-3 fs-5 text-muted">Tu carrito está vacío.</p>
    <a href="<?= APP_URL ?>/index.php?r=store/index" class="btn btn-primary">
      <i class="bi bi-arrow-left me-2"></i>Seguir comprando
    </a>
  </div>
  <?php else: ?>
  <div class="row g-4">
    <!-- Tabla carrito -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0" id="cartTable">
              <thead class="table-light">
                <tr>
                  <th>Producto</th>
                  <th class="text-center">Precio</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Subtotal</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($carrito as $item): ?>
                <tr id="row-<?= $item['id'] ?>">
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <img src="<?= !empty($item['imagen']) ? APP_URL.'/resources/imagenes/'.e($item['imagen']) : APP_URL.'/public/img/no-image.svg' ?>"
                           width="60" height="60" style="object-fit:cover;border-radius:8px;"
                           onerror="this.src='<?= APP_URL ?>/public/img/no-image.svg'">
                      <span class="fw-semibold"><?= e($item['nombre']) ?></span>
                    </div>
                  </td>
                  <td class="text-center"><?= formatPrice($item['precio']) ?></td>
                  <td class="text-center">
                    <div class="d-flex align-items-center justify-content-center gap-1">
                      <button class="btn btn-outline-secondary btn-sm btn-qty" data-id="<?= $item['id'] ?>" data-op="dec">
                        <i class="bi bi-dash"></i>
                      </button>
                      <span class="qty-display fw-bold px-2" id="qty-<?= $item['id'] ?>"><?= $item['cantidad'] ?></span>
                      <button class="btn btn-outline-secondary btn-sm btn-qty" data-id="<?= $item['id'] ?>" data-op="inc">
                        <i class="bi bi-plus"></i>
                      </button>
                    </div>
                  </td>
                  <td class="text-center fw-bold text-primary" id="sub-<?= $item['id'] ?>">
                    <?= formatPrice($item['precio'] * $item['cantidad']) ?>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-outline-danger btn-sm btn-remove" data-id="<?= $item['id'] ?>">
                      <i class="bi bi-trash3"></i>
                    </button>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="mt-2 d-flex justify-content-between">
        <a href="<?= APP_URL ?>/index.php?r=store/index" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i>Seguir comprando
        </a>
        <button class="btn btn-outline-danger" id="btnClearCart">
          <i class="bi bi-trash3 me-1"></i>Vaciar carrito
        </button>
      </div>
    </div>

    <!-- Resumen -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="fw-bold mb-3">Resumen del pedido</h5>
          <?php $total = array_reduce($carrito, fn($s,$i) => $s + $i['precio']*$i['cantidad'], 0); ?>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Subtotal</span>
            <span id="cartTotal" class="fw-bold"><?= formatPrice($total) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Envío</span>
            <span class="text-success fw-semibold">Gratis</span>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-3">
            <span class="fw-bold fs-5">Total</span>
            <span class="fw-bold fs-5 text-primary" id="cartTotalFinal"><?= formatPrice($total) ?></span>
          </div>
          <a href="<?= APP_URL ?>/index.php?r=order/checkout" class="btn btn-primary w-100 py-2 fw-bold">
            <i class="bi bi-credit-card me-2"></i>Finalizar compra
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
