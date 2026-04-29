<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title ?? 'ElectroMax') ?> | ElectroMax</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
  <div class="container d-flex justify-content-between align-items-center">
    <span class="topbar-contact">
      <i class="bi bi-telephone-fill me-1"></i> +591 2-123456
      &nbsp;|&nbsp;
      <i class="bi bi-envelope-fill me-1"></i> ventas@electromax.com
    </span>
    <div class="ms-auto">
      <?php if (isLoggedIn()): ?>
        <span class="me-2 d-none d-sm-inline"><i class="bi bi-person-circle"></i> <?= e($_SESSION['user_nombre']) ?></span>
        <?php if (isStaff()): ?>
          <a href="<?= APP_URL ?>/index.php?r=admin/dashboard" class="topbar-link me-2"><i class="bi bi-gear-fill"></i> <span class="d-none d-sm-inline">Panel</span></a>
        <?php else: ?>
          <a href="<?= APP_URL ?>/index.php?r=order/history" class="topbar-link me-2"><i class="bi bi-bag-check"></i> <span class="d-none d-sm-inline">Mis Pedidos</span></a>
        <?php endif; ?>
        <a href="<?= APP_URL ?>/index.php?r=auth/logout" class="topbar-link"><i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Salir</span></a>
      <?php else: ?>
        <a href="<?= APP_URL ?>/index.php?r=auth/login" class="topbar-link me-2"><i class="bi bi-person"></i> <span class="d-none d-sm-inline">Iniciar Sesión</span></a>
        <a href="<?= APP_URL ?>/index.php?r=auth/register" class="topbar-link"><i class="bi bi-person-plus"></i> <span class="d-none d-sm-inline">Registrarse</span></a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Navbar principal -->
<nav class="navbar navbar-expand-lg navbar-dark main-navbar">
  <div class="container">
    <a class="navbar-brand" href="<?= APP_URL ?>/index.php?r=store/index">
      <i class="bi bi-lightning-charge-fill text-warning fs-4"></i>
      <span class="brand-name">Electro<span class="text-warning">Max</span></span>
    </a>

    <!-- Búsqueda -->
    <form class="d-flex search-form mx-auto" action="<?= APP_URL ?>/index.php" method="GET">
      <input type="hidden" name="r" value="store/buscar">
      <div class="input-group">
        <input type="text" name="q" class="form-control search-input"
               placeholder="Buscar productos, marcas..."
               value="<?= e($_GET['q'] ?? '') ?>" autocomplete="off" id="searchInput">
        <button class="btn btn-warning" type="submit"><i class="bi bi-search"></i></button>
      </div>
    </form>

    <!-- Carrito -->
    <div class="d-flex align-items-center gap-2">
      <button class="cart-btn position-relative border-0 bg-transparent"
              data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-label="Abrir carrito">
        <i class="bi bi-cart3 fs-4"></i>
        <span class="cart-badge" id="cartCount"><?= cartCount() ?></span>
      </button>
      <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
</nav>

<!-- Categorías -->
<div class="categories-bar">
  <div class="container">
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="nav categories-nav">
        <li class="nav-item">
          <a class="nav-link cat-link" href="<?= APP_URL ?>/index.php?r=store/index">
            <i class="bi bi-house-fill"></i> Inicio
          </a>
        </li>
        <?php foreach ($categorias as $cat): ?>
        <li class="nav-item">
          <a class="nav-link cat-link <?= (($_GET['id'] ?? 0) == $cat['id'] && ($_GET['r'] ?? '') === 'store/categoria') ? 'active' : '' ?>"
             href="<?= APP_URL ?>/index.php?r=store/categoria&id=<?= $cat['id'] ?>">
            <i class="bi <?= e($cat['icono']) ?>"></i> <?= e($cat['nombre']) ?>
          </a>
        </li>
        <?php endforeach; ?>
        <li class="nav-item ms-lg-auto">
          <a class="nav-link cat-link" href="<?= APP_URL ?>/api/swagger-ui/">
            <i class="bi bi-file-earmark-code"></i> API Docs
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>

<!-- Flash -->
<div class="container mt-2"><?= flash() ?></div>
