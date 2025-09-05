v<?= $this->section('styles') ?>
<!-- Toastr -->
<link rel="stylesheet" href="<?= base_url('/assets/admin/plugins/toastr/toastr.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Toastr -->
<script src="<?= base_url('/assets/admin/plugins/toastr/toastr.min.js') ?>"></script>

<?php
if (!empty(session('success'))){
    echo "<script>toastr.success('".session('success')."')</script>";
}
?>
<?= $this->endSection() ?>