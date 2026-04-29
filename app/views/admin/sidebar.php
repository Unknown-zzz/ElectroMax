<div class="admin-sidebar">
  <h6 class="text-muted fw-bold px-2 mb-3 small text-uppercase">Panel Admin</h6>
  <nav class="nav flex-column gap-1">
    <a href="<?= APP_URL ?>/index.php?r=admin/dashboard"
       class="nav-link admin-nav-link <?= ($_GET['r'] ?? '') === 'admin/dashboard' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="<?= APP_URL ?>/index.php?r=admin/productos"
       class="nav-link admin-nav-link <?= str_starts_with($_GET['r'] ?? '', 'admin/producto') ? 'active' : '' ?>">
      <i class="bi bi-box-seam"></i> Productos
    </a>
    <a href="<?= APP_URL ?>/index.php?r=admin/pedidos"
       class="nav-link admin-nav-link <?= ($_GET['r'] ?? '') === 'admin/pedidos' ? 'active' : '' ?>">
      <i class="bi bi-bag-check"></i> Pedidos
    </a>
    <a href="<?= APP_URL ?>/index.php?r=admin/categorias"
       class="nav-link admin-nav-link <?= str_starts_with($_GET['r'] ?? '', 'admin/categor') ? 'active' : '' ?>">
      <i class="bi bi-tags"></i> Categorías
    </a>
    <a href="<?= APP_URL ?>/index.php?r=admin/marcas"
       class="nav-link admin-nav-link <?= str_starts_with($_GET['r'] ?? '', 'admin/marca') ? 'active' : '' ?>">
      <i class="bi bi-award"></i> Marcas
    </a>
    <hr>
    <a href="<?= APP_URL ?>/index.php?r=store/index" class="nav-link admin-nav-link">
      <i class="bi bi-shop"></i> Ver tienda
    </a>
    <a href="<?= APP_URL ?>/api/swagger-ui/" class="nav-link admin-nav-link" target="_blank">
      <i class="bi bi-file-earmark-code"></i> API Docs
    </a>
  </nav>
</div>
