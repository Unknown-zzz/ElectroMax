<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow border-0">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <i class="bi bi-person-plus-fill text-primary fs-1"></i>
            <h4 class="fw-bold mt-2">Crear Cuenta</h4>
            <p class="text-muted small">Únete a ElectroMax</p>
          </div>
          <form method="POST" action="<?= APP_URL ?>/index.php?r=auth/register">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nombre completo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="nombre" class="form-control" required
                       value="<?= e($_POST['nombre'] ?? '') ?>" placeholder="Juan Pérez">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" required
                       value="<?= e($_POST['email'] ?? '') ?>" placeholder="tu@email.com">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="telefono" class="form-control"
                       value="<?= e($_POST['telefono'] ?? '') ?>" placeholder="+591 7-XXXXXXX">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Contraseña <span class="text-muted small">(mín. 6 caracteres)</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" required minlength="6" placeholder="••••••">
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
              <i class="bi bi-person-check me-2"></i>Crear cuenta
            </button>
          </form>
          <p class="text-center text-muted mt-3 mb-0 small">
            ¿Ya tienes cuenta?
            <a href="<?= APP_URL ?>/index.php?r=auth/login" class="text-primary fw-semibold">Inicia sesión</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
