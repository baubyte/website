<!DOCTYPE html>
<html lang="<?=session('locale')?>">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>BAUBYTE | <?= $this->renderSection('title') ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url('favicon.png')?>" rel="icon">
  <link href="<?= base_url('apple-touch-icon.png')?>" rel="apple-touch-icon">
  <!-- vendorapp CSS Files -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?= base_url('/assets/front/css/aos.css?ver=1.1.0')?>" rel="stylesheet">
  <link href="<?= base_url('/assets/front/css/bootstrap.min.css?ver=1.1.0')?>" rel="stylesheet">
  <link href="<?= base_url('/assets/front/css/main.css?ver=1.1.0')?>" rel="stylesheet">
  <noscript>
    <style type="text/css">
      [data-aos] {
        opacity: 1 !important;
        transform: translate(0) scale(1) !important;
      }
    </style>
  </noscript>

</head>

<body id="top">
  <!-- Incluimos en Header -->
  <?= $this->include('Front/layout/header') ?>
  <div class="page-content">
  <!-- Render de Contenido -->
  <?= $this->renderSection('content') ?>
  </div>
  <!-- Incluimos en Footer -->
  <?= $this->include('Front/layout/footer') ?>
  <!-- vendorapp JS Files -->
  <script src="<?= base_url('/assets/front/js/core/jquery.3.2.1.min.js?ver=1.1.0')?>"></script>
  <script src="<?= base_url('/assets/front/js/core/popper.min.js?ver=1.1.0')?>"></script>
  <script src="<?= base_url('/assets/front/js/core/bootstrap.min.js?ver=1.1.0')?>"></script>
  <script src="<?= base_url('/assets/front/js/now-ui-kit.js?ver=1.1.0')?>"></script>
  <script src="<?= base_url('/assets/front/js/aos.js?ver=1.1.0')?>"></script>
  <script src="<?= base_url('/assets/front/scripts/main.js?ver=1.1.0')?>"></script>
  <!-- Render de JS -->
  <?= $this->renderSection('js') ?>
</body>
</html>