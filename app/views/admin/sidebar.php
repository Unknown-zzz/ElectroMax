<div class="admin-sidebar">

  <!-- Badge de rol (visible en desktop) -->
  <div class="admin-role-card d-none d-md-flex">
    <span class="role-avatar"><i class="bi bi-person-circle fs-5 text-muted"></i></span>
    <div class="lh-sm overflow-hidden">
      <div class="fw-semibold small text-truncate"><?= e($_SESSION['user_nombre'] ?? '') ?></div>
      <div><?= rolBadge() ?></div>
    </div>
  </div>

  <h6 class="admin-sidebar-label text-muted fw-bold px-1 mb-2 small text-uppercase">Panel</h6>

  <!-- Nav: horizontal en móvil, vertical en desktop -->
  <nav class="nav admin-nav">

    <?php if (canDo('ver_dashboard')): ?>
    <a href="<?= APP_URL ?>/index.php?r=admin/dashboard"
       class="nav-link admin-nav-link <?= ($_GET['r'] ?? '') === 'admin/dashboard' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2 me-1"></i>Dashboard
    </a>
    <?php endif; ?>

    <?php if (canDo('ver_productos')): ?>
    <a href="<?= APP_URL ?>/index.php?r=admin/productos"
       class="nav-link admin-nav-link <?= str_starts_with($_GET['r'] ?? '', 'admin/producto') ? 'active' : '' ?>">
      <i class="bi bi-box-seam me-1"></i>Productos
    </a>
    <?php endif; ?>

    <?php if (canDo('ver_pedidos')): ?>
    <a href="<?= APP_URL ?>/index.php?r=admin/pedidos"
       class="nav-link admin-nav-link <?= ($_GET['r'] ?? '') === 'admin/pedidos' ? 'active' : '' ?>">
      <i class="bi bi-bag-check me-1"></i>Pedidos
    </a>
    <?php endif; ?>

    <?php if (canDo('ver_categorias')): ?>
    <a href="<?= APP_URL ?>/index.php?r=admin/categorias"
       class="nav-link admin-nav-link <?= str_starts_with($_GET['r'] ?? '', 'admin/categor') ? 'active' : '' ?>">
      <i class="bi bi-tags me-1"></i>Categorías
    </a>
    <?php endif; ?>

    <?php if (canDo('ver_marcas')): ?>
    <a href="<?= APP_URL ?>/index.php?r=admin/marcas"
       class="nav-link admin-nav-link <?= str_starts_with($_GET['r'] ?? '', 'admin/marca') ? 'active' : '' ?>">
      <i class="bi bi-award me-1"></i>Marcas
    </a>
    <?php endif; ?>

    <?php if (canDo('gestionar_usuarios')): ?>
    <a href="<?= APP_URL ?>/index.php?r=admin/usuarios"
       class="nav-link admin-nav-link <?= str_starts_with($_GET['r'] ?? '', 'admin/usuario') ? 'active' : '' ?>">
      <i class="bi bi-people me-1"></i>Usuarios
    </a>
    <?php endif; ?>

    <?php if (canDo('chat')): ?>
    <a href="<?= APP_URL ?>/index.php?r=admin/chat"
       class="nav-link admin-nav-link <?= ($_GET['r'] ?? '') === 'admin/chat' ? 'active' : '' ?>">
      <i class="bi bi-chat-dots me-1"></i>Chat
    </a>
    <?php endif; ?>

    <hr class="my-2 w-100">

    <a href="<?= APP_URL ?>/index.php?r=store/index" class="nav-link admin-nav-link">
      <i class="bi bi-shop me-1"></i>Tienda
    </a>

    <?php if (isAdmin()): ?>
    <a href="<?= APP_URL ?>/api/swagger-ui/" class="nav-link admin-nav-link" target="_blank">
      <i class="bi bi-file-earmark-code me-1"></i>API
    </a>
    <?php endif; ?>

  </nav>
</div>
