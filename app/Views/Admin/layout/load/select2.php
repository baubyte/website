<?= $this->section('styles') ?>
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('/assets/admin/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Select2  & Plugins -->
<script src="<?= base_url('/assets/admin/plugins/select2/js/select2.full.min.js') ?>"></script>
<script>
  $(function () {
       //Initialize Select2 Elements
       //$('.select2').select2()
       //Initialize Select2 Elements Bootstrap4
       $('.select2bs4').select2({
      theme: 'bootstrap4',
      placeholder:'Seleccione una Opción'
    })
    })
</script>
<?= $this->endSection() ?>