<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-2"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="col-md-10">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="bi bi-tags me-2"></i>Categorías</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCat" onclick="resetCat()">
          <i class="bi bi-plus-circle me-1"></i> Nueva
        </button>
      </div>
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr><th>#</th><th>Icono</th><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr>
            </thead>
            <tbody>
              <?php foreach ($categorias as $c): ?>
              <tr>
                <td><?= $c['id'] ?></td>
                <td><i class="bi <?= e($c['icono']) ?> fs-5 text-primary"></i></td>
                <td class="fw-semibold"><?= e($c['nombre']) ?></td>
                <td class="text-muted small"><?= e($c['descripcion']) ?></td>
                <td>
                  <button class="btn btn-sm btn-outline-primary me-1"
                          onclick="editCat(<?= htmlspecialchars(json_encode($c), ENT_QUOTES) ?>)">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <a href="<?= APP_URL ?>/index.php?r=admin/categoria_delete&id=<?= $c['id'] ?>"
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('¿Eliminar categoría?')">
                    <i class="bi bi-trash3"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalCat" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="<?= APP_URL ?>/index.php?r=admin/categoria_save">
        <input type="hidden" name="id" id="cat_id" value="0">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="catModalTitle">Nueva Categoría</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nombre *</label>
            <input type="text" name="nombre" id="cat_nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Descripción</label>
            <input type="text" name="descripcion" id="cat_descripcion" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Icono Bootstrap <span class="text-muted small">(ej: bi-tv)</span></label>
            <input type="text" name="icono" id="cat_icono" class="form-control" value="bi-box">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
function resetCat() {
  document.getElementById('cat_id').value = '0';
  document.getElementById('cat_nombre').value = '';
  document.getElementById('cat_descripcion').value = '';
  document.getElementById('cat_icono').value = 'bi-box';
  document.getElementById('catModalTitle').textContent = 'Nueva Categoría';
}
function editCat(c) {
  document.getElementById('cat_id').value = c.id;
  document.getElementById('cat_nombre').value = c.nombre;
  document.getElementById('cat_descripcion').value = c.descripcion || '';
  document.getElementById('cat_icono').value = c.icono || 'bi-box';
  document.getElementById('catModalTitle').textContent = 'Editar Categoría';
  new bootstrap.Modal(document.getElementById('modalCat')).show();
}
</script>
