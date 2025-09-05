<!DOCTYPE html>
<html lang="<?= session('locale') ?>">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>BAUBYTE | <?= $this->renderSection('title') ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Favicons -->
    <link href="<?= base_url('favicon.png')?>" rel="icon">
    <link href="<?= base_url('apple-touch-icon.png')?>" rel="apple-touch-icon">
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Vendor CSS Files -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('/assets/admin/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('/assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('/assets/admin/adminlte/css/adminlte.min.css') ?>">
    <!-- Render CSS File -->
    <?= $this->renderSection('styles') ?>
</head>

<body class="<?= $this->renderSection('classBody') ?>">
    <!-- Render de Contenido -->
    <?= $this->renderSection('content') ?>
    <!-- Vendor JS Files -->
    <!-- jQuery -->
    <script src="<?= base_url('/assets/admin/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('/assets/admin/adminlte/js/adminlte.min.js') ?>"></script>

    <!-- Render de JS -->
    <?= $this->renderSection('js') ?>
</body>

</html>