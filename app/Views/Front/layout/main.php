<!DOCTYPE html>
<html lang="<?= session('locale') ?>">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= isset($seo_title) ? esc($seo_title) : 'Martín Pared Baez - Desarrollador Full Stack Senior | CV Online' ?></title>

  <!-- SEO Meta Tags Dinámicos -->
  <?= $this->renderSection('meta_tags') ?>

  <!-- Meta tags básicos -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="index,follow">
  <meta name="language" content="<?= session('locale') ?? 'es' ?>">
  <meta name="author" content="Martín Pared Baez">

  <!-- Datos estructurados para desarrollador -->
  <meta name="developer:name" content="Martín Pared Baez">
  <meta name="developer:alias" content="baubyte">
  <meta name="developer:experience_level" content="Senior">
  <meta name="developer:specialization" content="Full Stack Development">
  <meta name="developer:technologies" content="PHP, Laravel, CodeIgniter, JavaScript, React, Vue.js, MySQL, PostgreSQL">
  <meta name="developer:location" content="Buenos Aires, Argentina">
  <meta name="developer:availability" content="Freelance, Remote Work">
  <meta name="developer:email" content="paredbaez.martin@gmail.com">
  <meta name="developer:github" content="https://github.com/baubyte">
  <meta name="developer:linkedin" content="https://www.linkedin.com/in/mparedbaez/">

  <!-- Información profesional para IA y reclutadores -->
  <meta name="professional:category" content="Software Developer, Full Stack Developer, Web Developer">
  <meta name="professional:skills" content="Backend Development, Frontend Development, Database Design, API Development">
  <meta name="professional:languages" content="Spanish (Native), English (Professional)">
  <meta name="professional:industry" content="Technology, Software Development, Web Development">
  <meta name="professional:work_type" content="Remote, Freelance, Contract, Full-time">

  <!-- Twitter Cards específicas para desarrollador -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:creator" content="@baubyte">
  <meta name="twitter:image:alt" content="Martín Pared Baez - Desarrollador Full Stack Senior">

  <!-- Schema.org para perfil profesional -->
  <?= schema_org_script() ?>

  <!-- Favicons -->
  <link href="<?= base_url('favicon.png') ?>" rel="icon">
  <link href="<?= base_url('apple-touch-icon.png') ?>" rel="apple-touch-icon">

  <!-- Preconnect para optimización de carga -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://maxcdn.bootstrapcdn.com">

  <!-- CSS Files -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?= base_url('/assets/front/css/aos.css?ver=1.2.0') ?>" rel="stylesheet">
  <link href="<?= base_url('/assets/front/css/bootstrap.min.css?ver=1.2.0') ?>" rel="stylesheet">
  <link href="<?= base_url('/assets/front/css/main.css?ver=1.2.0') ?>" rel="stylesheet">

  <!-- Estilos adicionales por página -->
  <?= $this->renderSection('css') ?>

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

  <!-- JavaScript Files -->
  <script src="<?= base_url('/assets/front/js/core/jquery.3.2.1.min.js?ver=1.2.0') ?>"></script>
  <script src="<?= base_url('/assets/front/js/core/popper.min.js?ver=1.2.0') ?>"></script>
  <script src="<?= base_url('/assets/front/js/core/bootstrap.min.js?ver=1.2.0') ?>"></script>
  <script src="<?= base_url('/assets/front/js/now-ui-kit.js?ver=1.2.0') ?>"></script>
  <script src="<?= base_url('/assets/front/js/aos.js?ver=1.2.0') ?>"></script>
  <script src="<?= base_url('/assets/front/scripts/main.js?ver=1.2.0') ?>"></script>

  <!-- JavaScript adicional por página -->
  <?= $this->renderSection('js') ?>

  <!-- Analytics y tracking (si es necesario) -->
  <?= $this->renderSection('analytics') ?>

</body>

</html>