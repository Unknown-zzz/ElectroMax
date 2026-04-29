<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-2"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="col-md-10">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Gestión de Usuarios</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsignar">
          <i class="bi bi-person-plus me-1"></i> Asignar Rol a Cliente
        </button>
      </div>

      <!-- Tabla de Staff -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 fw-bold pt-3">
          <i class="bi bi-shield-check me-2 text-primary"></i>Personal del sistema
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Rol</th>
                  <th>Estado</th>
                  <th>Registrado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($staff as $u): ?>
                <tr>
                  <td class="text-muted small"><?= $u['id'] ?></td>
                  <td class="fw-semibold"><?= e($u['nombre']) ?></td>
                  <td class="text-muted small"><?= e($u['email']) ?></td>
                  <td><?= rolBadge($u['rol']) ?></td>
                  <td>
                    <?php if ($u['activo']): ?>
                      <span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>
                    <?php else: ?>
                      <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactivo</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-muted small"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                  <td>
                    <?php if ($u['rol'] !== 'admin'): ?>
                    <button class="btn btn-sm btn-outline-primary me-1"
                            onclick="abrirEditar(<?= htmlspecialchars(json_encode($u), ENT_QUOTES) ?>)">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <a href="<?= APP_URL ?>/index.php?r=admin/usuario_toggle&id=<?= $u['id'] ?>"
                       class="btn btn-sm <?= $u['activo'] ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                       title="<?= $u['activo'] ? 'Desactivar' : 'Activar' ?>"
                       onclick="return confirm('¿Cambiar estado del usuario?')">
                      <i class="bi <?= $u['activo'] ? 'bi-pause-circle' : 'bi-play-circle' ?>"></i>
                    </a>
                    <?php else: ?>
                    <span class="text-muted small">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($staff)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No hay personal registrado.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Información de roles -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 fw-bold pt-3">
          <i class="bi bi-info-circle me-2 text-info"></i>Permisos por rol
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="p-3 rounded border border-danger-subtle bg-danger-subtle">
                <div class="fw-bold mb-2"><span class="badge bg-danger me-2">Administrador</span></div>
                <ul class="small mb-0 ps-3">
                  <li>Acceso total al panel</li>
                  <li>Gestión de usuarios y roles</li>
                  <li>Productos, pedidos, categorías, marcas</li>
                  <li>Chat — todos los canales (lectura/escritura)</li>
                </ul>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-3 rounded border border-primary-subtle bg-primary-subtle">
                <div class="fw-bold mb-2"><span class="badge bg-primary me-2">Vendedor</span></div>
                <ul class="small mb-0 ps-3">
                  <li>Ver catálogo (solo lectura)</li>
                  <li>Gestión completa de pedidos</li>
                  <li>Chat — #general, #avisos (lectura), #ventas</li>
                </ul>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-3 rounded border border-success-subtle bg-success-subtle">
                <div class="fw-bold mb-2"><span class="badge bg-success me-2">Inventario</span></div>
                <ul class="small mb-0 ps-3">
                  <li>Gestión completa de productos</li>
                  <li>Gestión de categorías y marcas</li>
                  <li>Chat — #general, #avisos (lectura), #inventario</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal Editar Rol -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="<?= APP_URL ?>/index.php?r=admin/usuario_rol_save">
      <input type="hidden" name="id" id="edit_id">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Cambiar Rol</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="mb-3">Usuario: <strong id="edit_nombre"></strong></p>
          <label class="form-label fw-semibold">Nuevo rol</label>
          <select name="rol" id="edit_rol" class="form-select">
            <option value="cliente">Cliente (sin acceso al panel)</option>
            <option value="vendedor">Vendedor</option>
            <option value="inventario">Inventario</option>
            <option value="admin">Administrador</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Asignar rol a cliente existente -->
<div class="modal fade" id="modalAsignar" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="<?= APP_URL ?>/index.php?r=admin/usuario_rol_save">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Asignar Rol a Cliente</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Seleccionar cliente</label>
            <select name="id" class="form-select" required>
              <option value="">-- Selecciona un usuario --</option>
              <?php foreach ($clientes as $c): ?>
              <option value="<?= $c['id'] ?>"><?= e($c['nombre']) ?> — <?= e($c['email']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Rol a asignar</label>
            <select name="rol" class="form-select" required>
              <option value="vendedor">Vendedor</option>
              <option value="inventario">Inventario</option>
              <option value="admin">Administrador</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success"><i class="bi bi-person-check me-1"></i>Asignar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function abrirEditar(u) {
  document.getElementById('edit_id').value   = u.id;
  document.getElementById('edit_nombre').textContent = u.nombre;
  document.getElementById('edit_rol').value  = u.rol;
  new bootstrap.Modal(document.getElementById('modalEditar')).show();
}
</script>
