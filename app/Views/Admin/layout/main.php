<!DOCTYPE html>
<html lang="<?= session('locale') ?>">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>BAUBYTE | <?= $this->renderSection('title') ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <?=csrf_meta()?>
  <!-- Favicons -->
  <link href="<?= base_url('favicon.png')?>" rel="icon">
  <link href="<?= base_url('apple-touch-icon.png')?>" rel="apple-touch-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Vendor CSS Files -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('/assets/admin/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Render CSS File -->
  <?= $this->renderSection('styles') ?>
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('/assets/admin/adminlte/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/admin/css/styles.css') ?>">
</head>
<body class="sidebar-mini control-sidebar-slide-open layout-fixed layout-navbar-fixed sidebar-closed sidebar-collapse">
  <div class="wrapper">
    <!-- Incluimos el NavBar -->
    <?= $this->include('Admin/layout/navbar') ?>
    <!-- Incluimos el SideBar -->
    <?= $this->include('Admin/layout/sidebar') ?>
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__wobble" src="<?= base_url('favicon.png') ?>" alt="BAUBYTE logo" height="80" width="80">
    </div>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Incluimos el Header -->
      <?= $this->include('Admin/layout/header') ?>
      <!-- Render de Contenido -->
      <?= $this->renderSection('content') ?>
    </div>
    <!-- /.content-wrapper -->
    <!-- Incluimos en Footer -->
    <?= $this->include('Admin/layout/footer') ?>
    <!-- Control Sidebar -->
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- Vendor JS Files -->
  <!-- jQuery -->
  <script src="<?= base_url('/assets/admin/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- Render de JS -->
  <?= $this->renderSection('js') ?>
  <!-- AdminLTE App -->
  <script src="<?= base_url('/assets/admin/adminlte/js/adminlte.min.js') ?>"></script>
  <!-- Todas las Peticiones Ajax se agrega el header -->
  <script>
  $.ajaxSetup({headers:{'<?=csrf_header() ?>':$('meta[name="<?=csrf_header() ?>"]').attr('content')}})
  </script>
  <!-- Scripts App -->
  <script src="<?= base_url('/assets/admin/js/scripts.js') ?>"></script>
</body>

</html>