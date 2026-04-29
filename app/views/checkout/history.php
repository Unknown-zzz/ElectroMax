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
          <tr>
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
