<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-2"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="col-md-10">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="bi bi-box-seam me-2"></i>Productos</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProducto" onclick="resetForm()">
          <i class="bi bi-plus-circle me-1"></i> Nuevo
        </button>
      </div>

      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th>#</th><th>Imagen</th><th>Nombre</th><th>Categoría</th>
                  <th>Precio</th><th>Oferta</th><th>Stock</th><th>Dest.</th><th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($productos as $p): ?>
                <tr class="<?= !$p['activo'] ? 'table-secondary text-muted' : '' ?>">
                  <td><?= $p['id'] ?></td>
                  <td>
                    <img src="<?= !empty($p['imagen']) ? APP_URL.'/resources/imagenes/'.e($p['imagen']) : APP_URL.'/public/img/no-image.svg' ?>"
                         width="50" height="50" style="object-fit:cover;border-radius:6px;"
                         onerror="this.src='<?= APP_URL ?>/public/img/no-image.svg'">
                  </td>
                  <td class="fw-semibold"><?= e($p['nombre']) ?></td>
                  <td><span class="badge bg-secondary"><?= e($p['categoria_nombre'] ?? '-') ?></span></td>
                  <td><?= formatPrice($p['precio']) ?></td>
                  <td><?= $p['precio_oferta'] ? formatPrice($p['precio_oferta']) : '<span class="text-muted">-</span>' ?></td>
                  <td>
                    <span class="badge <?= $p['stock'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                      <?= $p['stock'] ?>
                    </span>
                  </td>
                  <td>
                    <?= $p['destacado'] ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>' ?>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editProducto(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <a href="<?= APP_URL ?>/index.php?r=admin/producto_delete&id=<?= $p['id'] ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('¿Eliminar este producto?')">
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
</div>

<!-- Modal Producto -->
<div class="modal fade" id="modalProducto" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="<?= APP_URL ?>/index.php?r=admin/producto_save" enctype="multipart/form-data">
        <input type="hidden" name="id" id="prod_id" value="0">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalTitle">Nuevo Producto</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Nombre *</label>
              <input type="text" name="nombre" id="prod_nombre" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Descripción</label>
              <textarea name="descripcion" id="prod_descripcion" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Precio *</label>
              <input type="number" name="precio" id="prod_precio" class="form-control" step="0.01" min="0" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Precio Oferta</label>
              <input type="number" name="precio_oferta" id="prod_precio_oferta" class="form-control" step="0.01" min="0">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Stock *</label>
              <input type="number" name="stock" id="prod_stock" class="form-control" min="0" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Categoría</label>
              <select name="categoria_id" id="prod_categoria" class="form-select">
                <option value="0">-- Sin categoría --</option>
                <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>"><?= e($c['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Marca</label>
              <select name="marca_id" id="prod_marca" class="form-select">
                <option value="0">-- Sin marca --</option>
                <?php foreach ($marcas as $m): ?>
                <option value="<?= $m['id'] ?>"><?= e($m['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Imagen</label>
              <input type="file" name="imagen" id="prod_imagen" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6 d-flex align-items-end">
              <div class="form-check">
                <input type="checkbox" name="destacado" id="prod_destacado" class="form-check-input" value="1">
                <label class="form-check-label fw-semibold" for="prod_destacado">
                  <i class="bi bi-star-fill text-warning me-1"></i>Producto Destacado
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function resetForm() {
  document.getElementById('prod_id').value = '0';
  document.getElementById('prod_nombre').value = '';
  document.getElementById('prod_descripcion').value = '';
  document.getElementById('prod_precio').value = '';
  document.getElementById('prod_precio_oferta').value = '';
  document.getElementById('prod_stock').value = '';
  document.getElementById('prod_categoria').value = '0';
  document.getElementById('prod_marca').value = '0';
  document.getElementById('prod_destacado').checked = false;
  document.getElementById('modalTitle').textContent = 'Nuevo Producto';
}
function editProducto(p) {
  document.getElementById('prod_id').value = p.id;
  document.getElementById('prod_nombre').value = p.nombre;
  document.getElementById('prod_descripcion').value = p.descripcion || '';
  document.getElementById('prod_precio').value = p.precio;
  document.getElementById('prod_precio_oferta').value = p.precio_oferta || '';
  document.getElementById('prod_stock').value = p.stock;
  document.getElementById('prod_categoria').value = p.categoria_id || '0';
  document.getElementById('prod_marca').value = p.marca_id || '0';
  document.getElementById('prod_destacado').checked = p.destacado == 1;
  document.getElementById('modalTitle').textContent = 'Editar Producto';
  new bootstrap.Modal(document.getElementById('modalProducto')).show();
}
</script>
