<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-2"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="col-md-10">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="bi bi-award me-2"></i>Marcas</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMarca" onclick="resetMarca()">
          <i class="bi bi-plus-circle me-1"></i> Nueva
        </button>
      </div>
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr><th>#</th><th>Nombre</th><th>Acciones</th></tr>
            </thead>
            <tbody>
              <?php foreach ($marcas as $m): ?>
              <tr>
                <td><?= $m['id'] ?></td>
                <td class="fw-semibold"><?= e($m['nombre']) ?></td>
                <td>
                  <button class="btn btn-sm btn-outline-primary me-1"
                          onclick="editMarca(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <a href="<?= APP_URL ?>/index.php?r=admin/marca_delete&id=<?= $m['id'] ?>"
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('¿Eliminar marca?')">
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
<div class="modal fade" id="modalMarca" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="<?= APP_URL ?>/index.php?r=admin/marca_save">
        <input type="hidden" name="id" id="marca_id" value="0">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="marcaModalTitle">Nueva Marca</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label fw-semibold">Nombre *</label>
          <input type="text" name="nombre" id="marca_nombre" class="form-control" required>
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
function resetMarca() {
  document.getElementById('marca_id').value = '0';
  document.getElementById('marca_nombre').value = '';
  document.getElementById('marcaModalTitle').textContent = 'Nueva Marca';
}
function editMarca(m) {
  document.getElementById('marca_id').value = m.id;
  document.getElementById('marca_nombre').value = m.nombre;
  document.getElementById('marcaModalTitle').textContent = 'Editar Marca';
  new bootstrap.Modal(document.getElementById('modalMarca')).show();
}
</script>
