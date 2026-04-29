<div class="container-fluid py-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2">
      <?php include __DIR__ . '/sidebar.php'; ?>
    </div>
    <div class="col-md-10">
      <h3 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h3>
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-primary text-white">
            <div class="card-body">
              <i class="bi bi-box-seam stat-icon"></i>
              <h2 class="fw-bold"><?= $stats['total_productos'] ?></h2>
              <p class="mb-0">Productos activos</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-success text-white">
            <div class="card-body">
              <i class="bi bi-bag-check stat-icon"></i>
              <h2 class="fw-bold"><?= $stats['total_pedidos'] ?></h2>
              <p class="mb-0">Pedidos totales</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-warning text-dark">
            <div class="card-body">
              <i class="bi bi-currency-dollar stat-icon"></i>
              <h2 class="fw-bold"><?= formatPrice($stats['total_ventas']) ?></h2>
              <p class="mb-0">Ventas totales</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-info text-white">
            <div class="card-body">
              <i class="bi bi-people stat-icon"></i>
              <h2 class="fw-bold"><?= $stats['total_clientes'] ?></h2>
              <p class="mb-0">Clientes</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <h6 class="fw-bold mb-3">Acciones rápidas</h6>
              <div class="d-grid gap-2">
                <a href="<?= APP_URL ?>/index.php?r=admin/productos" class="btn btn-outline-primary">
                  <i class="bi bi-plus-circle me-2"></i>Agregar producto
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/pedidos" class="btn btn-outline-success">
                  <i class="bi bi-eye me-2"></i>Ver pedidos
                </a>
                <a href="<?= APP_URL ?>/api/swagger-ui/" class="btn btn-outline-secondary" target="_blank">
                  <i class="bi bi-file-earmark-code me-2"></i>Documentación API
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
