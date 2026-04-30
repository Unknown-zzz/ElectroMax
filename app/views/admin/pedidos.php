<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-2"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="col-md-10">
      <h3 class="fw-bold mb-4"><i class="bi bi-bag-check me-2"></i>Pedidos</h3>
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th>#</th><th>Cliente</th><th>Email</th><th>Total</th>
                  <th>Estado</th><th>Fecha</th><th>Cambiar estado</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($pedidos)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No hay pedidos aún.</td></tr>
                <?php endif; ?>
                <?php foreach ($pedidos as $p): ?>
                <?php
                  $badges = ['pendiente'=>'warning','procesando'=>'info','enviado'=>'primary','entregado'=>'success','cancelado'=>'danger'];
                  $badge  = $badges[$p['estado']] ?? 'secondary';
                ?>
                <tr style="cursor: pointer;" onclick="abrirDetallePedido(<?= htmlspecialchars(json_encode($p)) ?>)" class="pedido-row" data-id="<?= $p['id'] ?>">
                  <td><strong>#<?= $p['id'] ?></strong></td>
                  <td><?= e($p['nombre_cliente']) ?></td>
                  <td class="small text-muted"><?= e($p['email_cliente']) ?></td>
                  <td class="fw-bold text-primary"><?= formatPrice($p['total']) ?></td>
                  <td><span class="badge bg-<?= $badge ?>"><?= ucfirst($p['estado']) ?></span></td>
                  <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                  <td onclick="event.stopPropagation();">
                    <form method="POST" action="<?= APP_URL ?>/index.php?r=admin/pedido_estado" class="d-flex gap-1">
                      <input type="hidden" name="id" value="<?= $p['id'] ?>">
                      <select name="estado" class="form-select form-select-sm" style="width:130px">
                        <?php foreach (['pendiente','procesando','enviado','entregado','cancelado'] as $e): ?>
                        <option value="<?= $e ?>" <?= $p['estado'] === $e ? 'selected' : '' ?>><?= ucfirst($e) ?></option>
                        <?php endforeach; ?>
                      </select>
                      <button class="btn btn-sm btn-success"><i class="bi bi-check"></i></button>
                    </form>
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

<div id="modalDetallePedido" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>Detalles del Pedido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBody">
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
            <h6 class="text-muted">Cliente</h6>
            <p class="fw-bold" id="detPedidoCliente">—</p>
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
        <div class="row mb-3">
          <div class="col-md-6">
            <h6 class="text-muted">Ciudad</h6>
            <p id="detPedidoCiudad">—</p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Código Postal</h6>
            <p id="detPedidoCodigoPostal">—</p>
          </div>
        </div>
        <hr>
        <h6 class="fw-bold mb-2">Productos</h6>
        <div id="detPedidoProductos" style="max-height: 300px; overflow-y: auto;">
          <p class="text-muted">Cargando...</p>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-muted">Subtotal</h6>
            <p id="detPedidoSubtotal" class="fw-bold">—</p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Impuestos</h6>
            <p id="detPedidoImpuestos" class="fw-bold">—</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-muted">Total</h6>
            <p id="detPedidoTotal" class="fw-bold text-primary" style="font-size: 1.3em;">—</p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Fecha</h6>
            <p id="detPedidoFecha">—</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
function abrirDetallePedido(pedido) {
  const badges = {'pendiente':'warning','procesando':'info','enviado':'primary','entregado':'success','cancelado':'danger'};
  const badge = badges[pedido.estado] || 'secondary';

  document.getElementById('detPedidoId').textContent = '#' + pedido.id;
  document.getElementById('detPedidoEstado').innerHTML = `<span class="badge bg-${badge}">${pedido.estado.charAt(0).toUpperCase() + pedido.estado.slice(1)}</span>`;
  document.getElementById('detPedidoCliente').textContent = pedido.nombre_cliente || '—';
  document.getElementById('detPedidoEmail').textContent = pedido.email_cliente || '—';
  document.getElementById('detPedidoTelefono').textContent = pedido.telefono_cliente || '—';
  document.getElementById('detPedidoDireccion').textContent = pedido.direccion_cliente || '—';
  document.getElementById('detPedidoCiudad').textContent = pedido.ciudad_cliente || '—';
  document.getElementById('detPedidoCodigoPostal').textContent = pedido.codigo_postal_cliente || '—';
  document.getElementById('detPedidoFecha').textContent = new Date(pedido.created_at).toLocaleString('es-ES');

  const total = parseFloat(pedido.total) || 0;
  const impuestos = parseFloat(pedido.impuestos) || 0;
  const subtotal = total - impuestos;

  document.getElementById('detPedidoSubtotal').textContent = '$' + subtotal.toFixed(2);
  document.getElementById('detPedidoImpuestos').textContent = '$' + impuestos.toFixed(2);
  document.getElementById('detPedidoTotal').textContent = '$' + total.toFixed(2);

  const prodDiv = document.getElementById('detPedidoProductos');
  if (pedido.productos && Array.isArray(pedido.productos) && pedido.productos.length > 0) {
    prodDiv.innerHTML = pedido.productos.map(prod => `
      <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
        <div>
          <div class="fw-bold">${prod.nombre || '—'}</div>
          <small class="text-muted">Cantidad: ${prod.cantidad || 1}</small>
        </div>
        <div class="text-end fw-bold">$${(parseFloat(prod.precio) * (prod.cantidad || 1)).toFixed(2)}</div>
      </div>
    `).join('');
  } else {
    prodDiv.innerHTML = '<p class="text-muted">Sin información de productos disponible</p>';
  }

  const modal = new bootstrap.Modal(document.getElementById('modalDetallePedido'));
  modal.show();
}
</script>
