<div class="container py-4">
  <h3 class="fw-bold mb-4"><i class="bi bi-bag-check me-2 text-primary"></i>Mis Pedidos</h3>
  <?php if (empty($pedidos)): ?>
    <div class="text-center py-5">
      <i class="bi bi-bag-x display-1 text-muted"></i>
      <p class="mt-3 text-muted">Aún no tienes pedidos.</p>
      <a href="<?= APP_URL ?>/index.php?r=store/index" class="btn btn-primary">Ir a la tienda</a>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th><th>Fecha</th><th>Total</th><th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pedidos as $p): ?>
          <tr style="cursor: pointer;" onclick="abrirDetallePedidoCliente(<?= htmlspecialchars(json_encode($p)) ?>, <?= htmlspecialchars(json_encode($detalles[$p['id']] ?? [])) ?>)" class="pedido-row" data-id="<?= $p['id'] ?>">
            <td><strong>#<?= $p['id'] ?></strong></td>
            <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
            <td class="fw-bold text-primary"><?= formatPrice($p['total']) ?></td>
            <td>
              <?php
              $badges = ['pendiente'=>'warning','procesando'=>'info','enviado'=>'primary','entregado'=>'success','cancelado'=>'danger'];
              $badge  = $badges[$p['estado']] ?? 'secondary';
              ?>
              <span class="badge bg-<?= $badge ?>"><?= ucfirst($p['estado']) ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<div id="modalDetallePedidoCliente" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>Detalles del Pedido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <h6 class="text-muted">ID del Pedido</h6>
            <p class="fw-bold" id="detPedidoId">#—</p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Estado</h6>
            <p id="detPedidoEstado"><span class="badge">—</span></p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <h6 class="text-muted">Nombre</h6>
            <p class="fw-bold" id="detPedidoNombre">—</p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Email</h6>
            <p id="detPedidoEmail">—</p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <h6 class="text-muted">Teléfono</h6>
            <p id="detPedidoTelefono">—</p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Dirección</h6>
            <p id="detPedidoDireccion">—</p>
          </div>
        </div>
        <hr>
        <h6 class="fw-bold mb-2">Productos</h6>
        <div id="detPedidoProductos" style="max-height: 250px; overflow-y: auto;">
          <p class="text-muted">Cargando...</p>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <h6 class="text-muted">Total</h6>
            <p id="detPedidoTotal" class="fw-bold text-primary" style="font-size: 1.3em;">—</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <h6 class="text-muted">Fecha del Pedido</h6>
            <p id="detPedidoFecha" class="small">—</p>
          </div>
        </div>
        <div id="detPedidoNotas" style="display:none;">
          <hr>
          <h6 class="text-muted">Notas</h6>
          <p id="detPedidoNotasText" class="small">—</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
function abrirDetallePedidoCliente(pedido, detalles) {
  const badges = {'pendiente':'warning','procesando':'info','enviado':'primary','entregado':'success','cancelado':'danger'};
  const badge = badges[pedido.estado] || 'secondary';

  document.getElementById('detPedidoId').textContent = '#' + pedido.id;
  document.getElementById('detPedidoEstado').innerHTML = `<span class="badge bg-${badge}">${pedido.estado.charAt(0).toUpperCase() + pedido.estado.slice(1)}</span>`;
  document.getElementById('detPedidoNombre').textContent = pedido.nombre_cliente || '—';
  document.getElementById('detPedidoEmail').textContent = pedido.email_cliente || '—';
  document.getElementById('detPedidoTelefono').textContent = pedido.telefono || '—';
  document.getElementById('detPedidoDireccion').textContent = pedido.direccion || '—';
  document.getElementById('detPedidoTotal').textContent = '$' + parseFloat(pedido.total).toFixed(2);
  document.getElementById('detPedidoFecha').textContent = new Date(pedido.created_at).toLocaleString('es-ES');

  const notasDiv = document.getElementById('detPedidoNotas');
  if (pedido.notas && pedido.notas.trim()) {
    document.getElementById('detPedidoNotasText').textContent = pedido.notas;
    notasDiv.style.display = 'block';
  } else {
    notasDiv.style.display = 'none';
  }

  const prodDiv = document.getElementById('detPedidoProductos');
  if (detalles && Array.isArray(detalles) && detalles.length > 0) {
    prodDiv.innerHTML = detalles.map(det => `
      <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
        <div>
          <div class="fw-bold">${det.nombre_producto || '—'}</div>
          <small class="text-muted">Cantidad: ${det.cantidad || 1}</small>
        </div>
        <div class="text-end fw-bold">$${(parseFloat(det.precio) * (det.cantidad || 1)).toFixed(2)}</div>
      </div>
    `).join('');
  } else {
    prodDiv.innerHTML = '<p class="text-muted">Sin información de productos disponible</p>';
  }

  const modal = new bootstrap.Modal(document.getElementById('modalDetallePedidoCliente'));
  modal.show();
}
</script>
