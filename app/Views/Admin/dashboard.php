<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
Dashboard
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            <div class="card card-success card-outline">
            <div class="card-header">
                <h5 class="m-0"><i class="nav-icon fab fa-teamspeak"></i> Gestión de Perfil</h5>
              </div>
              <div class="card-body">
                <h5 class="card-title"></h5>
                <p class="card-text">
                  Podrás dar de alta, editar tu perfil o desactivar, si lo desactivas no se visualizara en el website.
                </p>
              </div>
            </div>
            <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0"><i class="nav-icon fas fa-jedi"></i> Gestión de Skill </h5>
              </div>
              <div class="card-body">
                <h5 class="card-title"></h5>
                <p class="card-text">
                  Podrás gestionar tus Skills Jedi. Algunas de las opciones son dar de alta, editar o desactivar.
                </p>
              </div>
            </div><!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
          <div class="card card-danger card-outline">
            <div class="card-header">
                <h5 class="m-0"><i class="nav-icon fas fa-industry"></i> Gestión del Experiencia</h5>
              </div>
              <div class="card-body">
                <h5 class="card-title"></h5>
                <p class="card-text">
                  Podrás gestionar tus experiencias para que se visualicen en el website. Algunas de las opciones son dar de alta, editar o desactivarlos.
                </p>
              </div>
            </div><!-- /.card -->
            <div class="card card-warning card-outline">
              <div class="card-header">
                <h5 class="m-0"><i class="nav-icon fas fa-user-graduate"></i> Gestión de Estudios</h5>
              </div>
              <div class="card-body">
                <h6 class="card-title"></h6>
                <p class="card-text">En la gestión de estudios como su nombre los dice podrás, dar de alta, ver los tus estudios, editar o desactivar.</p>
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

<?= $this->endSection() ?>