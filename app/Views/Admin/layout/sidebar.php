  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=site_url(route_to('dashboard'))?>" class="brand-link">
      <img src="<?= base_url('favicon.png') ?>" alt="Admin BAUBYTE" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Admin Website</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?= base_url('/assets/admin/adminlte/img/avatar.png') ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= user()->username ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?=site_url(route_to('profile'))?>" class="nav-link">
              <i class="nav-icon fab fa-teamspeak"></i>
              <p>
                Gestión Perfil
                <span class="right badge badge-success">+</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=site_url(route_to('skill'))?>" class="nav-link">
              <i class="nav-icon fas fa-jedi"></i>
              <p>
                Gestión Skill
                <span class="right badge badge-primary">+</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=site_url(route_to('experience'))?>" class="nav-link">
              <i class="nav-icon fas fa-industry"></i>
              <p>
                Gestión Experiencia
                <span class="right badge badge-danger">+</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=site_url(route_to('study'))?>" class="nav-link">
            <i class="nav-icon fas fa-user-graduate"></i>
              <p>
                Gestión Estudios
                <span class="right badge badge-warning">+</span>
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>