  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= site_url(route_to('dashboard')) ?>" class="nav-link">Dashboard</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= site_url(route_to('home')) ?>" class="nav-link" target="_blank">Website</a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="colorMode" href="#" role="button">
          <i class="fas fa-moon" id="colorModeIcon"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-power-off"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="card card-warning card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="<?= base_url('/assets/admin/adminlte/img/avatar.png') ?>" alt="User profile picture">
              </div>

              <h3 class="profile-username text-center"><?= user()->username ?></h3>

              <p class="text-muted text-center"><?= user()->email ?></p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <span>Miembro Hace: </span>
                  <div class="float-right">
                    <?= user()->created_at->humanize() ?>
                  </div>
                </li>
              </ul>

              <a href="<?= site_url(route_to('logout')) ?>" class="btn btn-primary btn-block"><b>Cerrar Sesión</b></a>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->