<!-- Incluimos Datatables -->
<?= $this->include('Admin/layout/load/dataTables') ?>
<!-- Incluimos Toasts -->
<?= $this->include('Admin/layout/load/toasts') ?>
<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Skills
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
<i class="nav-icon fas fa-jedi"></i> Gestión de Skills
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tableSkill" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Porcentaje</th>
                  <th>Fecha Alta</th>
                  <th>Fecha Actualización</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Porcentaje</th>
                  <th>Fecha Alta</th>
                  <th>Fecha Actualización</th>
                  <th>Acciones</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- Page specific script -->
<script>
    tableSkill = $('#tableSkill').DataTable({
        language: {
            'url': '<?=base_url('/assets/admin/plugins/dttranslate').'/'.session('locale').'.json' ?>'
        },
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],
        dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B> <'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [{
                //Botón para Agregar Usuario
                action: function(e, dt, button, config) {
                    $(location).attr('href', '<?=site_url(route_to('create_skill')) ?>');
                },
                className: 'btn btn-info',
                text: '<i class="nav-icon fas fa-jedi"> +</i>'
            },
            {
                //Botón para CSV
                extend: 'csvHtml5',
                title: 'CSV',
                filename: 'ExportCSV',
                className: 'btn btn-warning',
                text: '<i class="fas fa-file-csv"></i>',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                //Botón para Excel
                extend: 'excelHtml5',
                title: 'Excel',
                filename: 'ExportExcel',
                className: 'btn btn-success',
                text: '<i class="fas fa-file-excel"></i>',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                //Botón para PDF
                extend: 'pdfHtml5',
                title: 'PDF',
                filename: 'ExportPDF',
                className: 'btn btn-danger',
                text: '<i class="far fa-file-pdf"></i>',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }

            },
            {
                //Botón para Copiar a Portapapeles
                extend: 'copyHtml5',
                title: 'Portapapeles',
                className: 'btn btn-secondary',
                text: '<i class="fas fa-copy"></i>',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                //Botón para Imprimir
                extend: 'print',
                //orientation: 'landscape',
                title: 'Imprimir',
                className: 'btn btn-dark',
                text: '<i class="fas fa-print"></i>',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            /* {
                //Botón para Ocultar Columnas
                extend: 'colvis',
                title: 'Ocultar',
                className: 'btn btn-info',
                text: '<i class="fas fa-eye-slash"></i>'
            } */
        ],
        order: [
            [1, "ASC"]
        ], //Orden por DEFAULT Columna Apellido
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?= site_url(route_to('datatable_skill')); ?>',
            method: 'GET',
            //Envió información  para el filtro si se usa
            data: function(data) {
                data.team = $('#skill').val();
            },
        },
        columns: [
            {
                data : 'no',
                'orderable': false,
            },
            {
                data: 'name',
                'orderable': true,
                'responsivePriority': 1,
            },
            {
                data: 'percentage',
                'orderable': true,
            },
            {
                data: 'created_at',
                'orderable': true,
            },
            {
                data: 'updated_at',
                'orderable': true,
            },
            {
                data: 'action',
                'orderable': false,
                'responsivePriority': 2,
            },
        ],

    });
    //Captura los cambios del select y recarga la tabla
      $('#tableSkill').change(function(event) {
        tableSkill.ajax.reload();
    });
</script>
<?= $this->endSection() ?>