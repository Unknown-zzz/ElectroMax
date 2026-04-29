<?php
function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin';
}

function isStaff(): bool {
    return isset($_SESSION['user_rol'])
        && in_array($_SESSION['user_rol'], ['admin', 'vendedor', 'inventario']);
}

function currentRole(): string {
    return $_SESSION['user_rol'] ?? 'cliente';
}

function rolLabel(string $rol = ''): string {
    return match($rol ?: currentRole()) {
        'admin'     => 'Administrador',
        'vendedor'  => 'Vendedor',
        'inventario'=> 'Inventario',
        default     => 'Cliente',
    };
}

function rolBadge(string $rol = ''): string {
    $r = $rol ?: currentRole();
    $class = match($r) {
        'admin'     => 'bg-danger',
        'vendedor'  => 'bg-primary',
        'inventario'=> 'bg-success',
        default     => 'bg-secondary',
    };
    return '<span class="badge ' . $class . '">' . rolLabel($r) . '</span>';
}

/**
 * Matriz de permisos por rol.
 * ver_* = puede ver la sección
 * editar_* = puede crear/editar/eliminar en esa sección
 */
function canDo(string $perm): bool {
    static $matrix = [
        'ver_dashboard'      => ['admin', 'vendedor', 'inventario'],
        'ver_productos'      => ['admin', 'vendedor', 'inventario'],
        'editar_productos'   => ['admin', 'inventario'],
        'ver_pedidos'        => ['admin', 'vendedor'],
        'editar_pedidos'     => ['admin', 'vendedor'],
        'ver_categorias'     => ['admin', 'inventario'],
        'editar_categorias'  => ['admin', 'inventario'],
        'ver_marcas'         => ['admin', 'inventario'],
        'editar_marcas'      => ['admin', 'inventario'],
        'gestionar_usuarios' => ['admin'],
        'chat'               => ['admin', 'vendedor', 'inventario'],
    ];
    return isset($matrix[$perm]) && in_array(currentRole(), $matrix[$perm]);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Debes iniciar sesión para continuar.'];
        header('Location: ' . APP_URL . '/index.php?r=auth/login');
        exit;
    }
}

function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        header('Location: ' . APP_URL . '/index.php?r=store/index');
        exit;
    }
}

function requireStaff(): void {
    requireLogin();
    if (!isStaff()) {
        http_response_code(403);
        header('Location: ' . APP_URL . '/index.php?r=store/index');
        exit;
    }
}

function requirePermiso(string $perm): void {
    requireStaff();
    if (!canDo($perm)) {
        http_response_code(403);
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'No tienes permisos para acceder a esta sección.'];
        header('Location: ' . APP_URL . '/index.php?r=admin/dashboard');
        exit;
    }
}

function flash(): string {
    if (!isset($_SESSION['flash'])) return '';
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $type = in_array($f['type'], ['success','danger','warning','info']) ? $f['type'] : 'info';
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">'
        . e($f['msg'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

function cartCount(): int {
    return array_sum(array_column($_SESSION['carrito'] ?? [], 'cantidad'));
}

function formatPrice(float $price): string {
    return 'Bs. ' . number_format($price, 2);
}

function uploadImage(string $inputName, string $dest): ?string {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES[$inputName]['tmp_name']);
    if (!in_array($mime, $allowed)) return null;
    $ext = match($mime) {
        'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp',
        default => 'jpg'
    };
    $filename = uniqid('img_', true) . '.' . $ext;
    move_uploaded_file($_FILES[$inputName]['tmp_name'], $dest . '/' . $filename);
    return $filename;
}
