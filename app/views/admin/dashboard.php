<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-2"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="col-md-10">

      <h3 class="fw-bold mb-4">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
        <small class="fs-6 fw-normal text-muted ms-2"><?= rolLabel() ?></small>
      </h3>

      <?php if (currentRole() === 'admin' && $stats): ?>
      <!-- ── Vista Admin ─────────────────────────────────────────── -->
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
        <div class="col-md-5">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <h6 class="fw-bold mb-3">Acciones rápidas</h6>
              <div class="d-grid gap-2">
                <a href="<?= APP_URL ?>/index.php?r=admin/productos" class="btn btn-outline-primary">
                  <i class="bi bi-plus-circle me-2"></i>Agregar producto
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/pedidos" class="btn btn-outline-success">
                  <i class="bi bi-eye me-2"></i>Ver pedidos
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/usuarios" class="btn btn-outline-secondary">
                  <i class="bi bi-people me-2"></i>Gestionar usuarios
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/chat" class="btn btn-outline-info">
                  <i class="bi bi-chat-dots me-2"></i>Abrir chat
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php elseif (currentRole() === 'vendedor' && $statsVendedor): ?>
      <!-- ── Vista Vendedor ──────────────────────────────────────── -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-success text-white">
            <div class="card-body">
              <i class="bi bi-bag-check stat-icon"></i>
              <h2 class="fw-bold"><?= $statsVendedor['total_pedidos'] ?></h2>
              <p class="mb-0">Pedidos totales</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-warning text-dark">
            <div class="card-body">
              <i class="bi bi-hourglass-split stat-icon"></i>
              <h2 class="fw-bold"><?= $statsVendedor['pendientes'] ?></h2>
              <p class="mb-0">Pendientes</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-primary text-white">
            <div class="card-body">
              <i class="bi bi-arrow-repeat stat-icon"></i>
              <h2 class="fw-bold"><?= $statsVendedor['procesando'] ?></h2>
              <p class="mb-0">En proceso</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-info text-white">
            <div class="card-body">
              <i class="bi bi-currency-dollar stat-icon"></i>
              <h2 class="fw-bold"><?= formatPrice($statsVendedor['total_ventas']) ?></h2>
              <p class="mb-0">Ventas totales</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row g-3">
        <div class="col-md-5">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <h6 class="fw-bold mb-3">Acciones rápidas</h6>
              <div class="d-grid gap-2">
                <a href="<?= APP_URL ?>/index.php?r=admin/pedidos" class="btn btn-outline-success">
                  <i class="bi bi-bag-check me-2"></i>Ver pedidos
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/productos" class="btn btn-outline-primary">
                  <i class="bi bi-box-seam me-2"></i>Ver catálogo
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/chat" class="btn btn-outline-info">
                  <i class="bi bi-chat-dots me-2"></i>Abrir chat
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php elseif (currentRole() === 'inventario' && $statsInventario): ?>
      <!-- ── Vista Inventario ────────────────────────────────────── -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-primary text-white">
            <div class="card-body">
              <i class="bi bi-box-seam stat-icon"></i>
              <h2 class="fw-bold"><?= $statsInventario['total_productos'] ?></h2>
              <p class="mb-0">Productos activos</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-danger text-white">
            <div class="card-body">
              <i class="bi bi-x-circle stat-icon"></i>
              <h2 class="fw-bold"><?= $statsInventario['sin_stock'] ?></h2>
              <p class="mb-0">Sin stock</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-warning text-dark">
            <div class="card-body">
              <i class="bi bi-exclamation-triangle stat-icon"></i>
              <h2 class="fw-bold"><?= $statsInventario['stock_bajo'] ?></h2>
              <p class="mb-0">Stock bajo (≤5)</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-0 stat-card bg-success text-white">
            <div class="card-body">
              <i class="bi bi-tags stat-icon"></i>
              <h2 class="fw-bold"><?= $statsInventario['total_categorias'] ?></h2>
              <p class="mb-0">Categorías</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row g-3">
        <div class="col-md-5">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <h6 class="fw-bold mb-3">Acciones rápidas</h6>
              <div class="d-grid gap-2">
                <a href="<?= APP_URL ?>/index.php?r=admin/productos" class="btn btn-outline-primary">
                  <i class="bi bi-plus-circle me-2"></i>Gestionar productos
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/categorias" class="btn btn-outline-secondary">
                  <i class="bi bi-tags me-2"></i>Gestionar categorías
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/marcas" class="btn btn-outline-secondary">
                  <i class="bi bi-award me-2"></i>Gestionar marcas
                </a>
                <a href="<?= APP_URL ?>/index.php?r=admin/chat" class="btn btn-outline-info">
                  <i class="bi bi-chat-dots me-2"></i>Abrir chat
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>
