<!-- Footer -->
<footer class="main-footer mt-5">
  <div class="container">
    <div class="row py-4">
      <div class="col-md-4 mb-3">
        <h5 class="text-warning"><i class="bi bi-lightning-charge-fill"></i> ElectroMax</h5>
        <p class="text-muted small">Tu tienda de confianza en electrodomésticos y tecnología. Los mejores precios y marcas del mercado.</p>
      </div>
      <div class="col-md-2 mb-3">
        <h6 class="text-white">Tienda</h6>
        <ul class="list-unstyled small">
          <li><a href="<?= APP_URL ?>/index.php?r=store/index" class="footer-link">Inicio</a></li>
          <li><a href="<?= APP_URL ?>/index.php?r=cart/index" class="footer-link">Carrito</a></li>
          <li><a href="<?= APP_URL ?>/api/swagger-ui/" class="footer-link">API Docs</a></li>
        </ul>
      </div>
      <div class="col-md-3 mb-3">
        <h6 class="text-white">Contacto</h6>
        <ul class="list-unstyled small text-muted">
          <li><i class="bi bi-geo-alt-fill text-warning me-1"></i> Av. Principal #123</li>
          <li><i class="bi bi-telephone-fill text-warning me-1"></i> +591 2-123456</li>
          <li><i class="bi bi-envelope-fill text-warning me-1"></i> ventas@electromax.com</li>
        </ul>
      </div>
      <div class="col-md-3 mb-3">
        <h6 class="text-white">Síguenos</h6>
        <div class="d-flex gap-2">
          <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-btn"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>
    </div>
    <hr class="border-secondary">
    <p class="text-center text-muted small py-2 mb-0">
      &copy; <?= date('Y') ?> ElectroMax &mdash; Tecnologías Web 2
    </p>
  </div>
</footer>

<!-- ── Offcanvas Carrito ────────────────────────────────────────── -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" style="width:420px;max-width:100vw">
  <div class="offcanvas-header border-bottom" style="background:var(--primary)">
    <h5 class="offcanvas-title text-white fw-bold">
      <i class="bi bi-cart3 me-2"></i>Mi Carrito
      <span class="badge bg-warning text-dark ms-2 fs-6" id="ocCartCount">0</span>
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0 d-flex flex-column">
    <div id="ocCartBody" class="flex-grow-1 overflow-auto p-3">
      <div class="text-center py-5 text-muted" id="ocCartLoading">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Cargando carrito...</p>
      </div>
    </div>
    <div id="ocCartFooter" class="border-top p-3 bg-light d-none">
      <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
        <span>Total</span>
        <span class="text-primary" id="ocCartTotal">Bs. 0.00</span>
      </div>
      <div class="d-grid gap-2">
        <a href="<?= APP_URL ?>/index.php?r=order/checkout"
           class="btn btn-primary py-2 fw-bold">
          <i class="bi bi-credit-card me-2"></i>Finalizar compra
        </a>
        <a href="<?= APP_URL ?>/index.php?r=cart/index"
           class="btn btn-outline-secondary btn-sm" data-bs-dismiss="offcanvas">
          <i class="bi bi-bag me-1"></i>Ver carrito completo
        </a>
      </div>
    </div>
  </div>
</div>

<!-- ── Modal Vista Rápida Producto ─────────────────────────────── -->
<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-0 px-4 pb-4" id="productModalBody">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<meta name="base-url" content="<?= APP_URL ?>">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= APP_URL ?>/public/js/app.js"></script>
</body>
</html>
