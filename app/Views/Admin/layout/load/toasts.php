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
if (!empty(session('error'))){
    echo "<script>toastr.error('".session('error')."')</script>";
}
if (!empty(session('info'))){
    echo "<script>toastr.info('".session('info')."')</script>";
}
if (!empty(session('warning'))){
    echo "<script>toastr.warning('".session('warning')."')</script>";
}
?>
<?= $this->endSection() ?>