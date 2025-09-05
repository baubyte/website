<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/Auth/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Recuperar Contraseña
<?= $this->endSection() ?>
<!-- Seccion Class Body -->
<?= $this->section('classBody') ?>
hold-transition login-page
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?= site_url(route_to('login')) ?>"><b>Admin</b> Website <b>BAUBYTE</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Ingresá el código que recibiste en tu e-mail, tu dirección de email, y tu nueva contraseña.</p>
            <!-- Incluimos Mensajes -->
            <?= $this->include('Admin/Auth/layout/message_block') ?>
            <form action="<?= site_url(route_to('reset-password')) ?>" method="post">
                <?= csrf_field() ?>
                <div class="input-group mb-3">
                    <input type="text" class="form-control <?= (session('errors.token')) ? 'is-invalid' : '' ?>" placeholder="Código" name="token" value="<?= old('token', $token ?? '') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-barcode"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        <?= session('errors.token') ?>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control <?= (session('errors.email')) ? 'is-invalid' : '' ?>" placeholder="Email" name="email" value="<?= old('email') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        <?= session('errors.email') ?>
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
                <div class="input-group mb-3">
                    <input type="password" class="form-control <?= (session('errors.pass_confirm')) ? 'is-invalid' : '' ?>" placeholder="Confirmar Contraseña" name="pass_confirm">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        <?= session('errors.pass_confirm') ?>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Recuperar Contraseña</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
<?= $this->endSection() ?>