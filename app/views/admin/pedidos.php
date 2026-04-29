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
                <tr>
                  <td><strong>#<?= $p['id'] ?></strong></td>
                  <td><?= e($p['nombre_cliente']) ?></td>
                  <td class="small text-muted"><?= e($p['email_cliente']) ?></td>
                  <td class="fw-bold text-primary"><?= formatPrice($p['total']) ?></td>
                  <td><span class="badge bg-<?= $badge ?>"><?= ucfirst($p['estado']) ?></span></td>
                  <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                  <td>
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
