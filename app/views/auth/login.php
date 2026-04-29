<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow border-0">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <i class="bi bi-lightning-charge-fill text-warning fs-1"></i>
            <h4 class="fw-bold mt-2">Iniciar Sesión</h4>
            <p class="text-muted small">Accede a tu cuenta ElectroMax</p>
          </div>
          <form method="POST" action="<?= APP_URL ?>/index.php?r=auth/login">
            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" required
                       value="<?= e($_POST['email'] ?? '') ?>" placeholder="tu@email.com">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" required placeholder="••••••">
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
              <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
            </button>
          </form>
          <p class="text-center text-muted mt-3 mb-0 small">
            ¿No tienes cuenta?
            <a href="<?= APP_URL ?>/index.php?r=auth/register" class="text-primary fw-semibold">Regístrate aquí</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
