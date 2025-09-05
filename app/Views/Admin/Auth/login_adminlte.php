<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/Auth/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Iniciar Sesión
<?= $this->endSection() ?>
<!-- Seccion Class Body -->
<?= $this->section('classBody') ?>
hold-transition login-page
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<div class="login-box">
  <div class="login-logo">
    <a href="<?= site_url(route_to('login')) ?>"><b>Admin</b> Website <b>Matelab</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicio de Sesión</p>
      <!-- Incluimos Mensajes -->
      <?= $this->include('Admin/Auth/layout/message_block') ?>
      <form action="<?= site_url(route_to('login')) ?>" method="post">
        <?= csrf_field() ?>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?= (session('errors.login')) ? 'is-invalid' : '' ?>" placeholder="Email o Usuario" name="login">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user-shield"></span>
            </div>
          </div>
          <div class="invalid-feedback">
            <?= session('errors.login') ?>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control <?= (session('errors.password')) ? 'is-invalid' : '' ?>" placeholder="Contraseña" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <div class="invalid-feedback">
									<?= session('errors.password') ?>
								</div>
        </div>
        <div class="row">
        
          <div class="col-8">
          <?php if ($config->allowRemembering) : ?>
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember" <?= (old('remember'))? 'checked':'' ?>>
              <label for="remember">
                Recordarme
              </label>
            </div>
            <?php endif; ?>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Iniciar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1">
      <?php if ($config->activeResetter) : ?>
        <a href="<?= site_url(route_to('forgot')) ?>">Olvide mi Contraseña</a>
        <?php endif; ?>
      </p>
      <p class="mb-0">
      <?php if ($config->allowRegistration) : ?>
        <a href="<?= site_url(route_to('register')) ?>" class="text-center">Registrarme</a>
        <?php endif; ?>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<?= $this->endSection() ?>