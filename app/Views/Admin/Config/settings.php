<!-- Incluimos Toasts -->
<?= $this->include('Admin/layout/load/toasts') ?>
<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Configuración
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
Configuración
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<!-- Main content -->
<div class="content">
    <div class="container">
        <!-- <div class="row"> -->
        <!-- <div class="col-10 mx-auto"> -->
        <div class="card card-dark card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url(route_to("settings_index")) ?>">Configuración</a>
                    </li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="rol">
                        <div class="container mt-2">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 col-md-4">
                                        <label for="status">Modo Mantenimiento Website</label>
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                            </div>
                                            <select class="form-control form-control-sm select2-dark" data-dropdown-css-class="select2-dark" id="status" name="status" data-toggle="tooltip" select2-type="true" title="<u>Cambiar</u><b> el estado del website.</b>" data-html="true">
                                                <option value="false" <?= (!$status) ? "selected" : "" ?>>Inactivo</option>
                                                <option value="true" <?= ($status) ? "selected" : "" ?>>Activo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <label for="status_es">Crear Enlace Simbólicos Website</label>
                                        <button class="btn btn-sm btn-dark btn-block" id="symlink_create">Crear</button>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <label for="status_es">Limpiar Cache</label>
                                        <button class="btn btn-sm btn-dark btn-block" id="cache_clean">Limpiar</button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.container -->
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- </div> -->
        <!-- </div> -->
        <!-- /.card -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script type="text/javascript" defer>
    //Configuracion de toastr
    toastr.options = {
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "2000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    }
    /**
     * Se ejecuta después de cada submit del formulario
     * por medio de ajax tanto en caso de exito como de error
     * 
     * @param {} xhrAjaxSubmit
     * @param string statusAjaxSubmit
     * @param string errorAjaxSubmit
     * @param {} form
     * @returns {void}
     */
    const showResponse = async (xhrAjaxSubmit, statusAjaxSubmit, errorAjaxSubmit, form, reset = true) => {
        const {
            responseJSON,
            responseText,
            status,
            statusText
        } = xhrAjaxSubmit;
        const messages = responseJSON ? responseJSON.messages : xhrAjaxSubmit.messages;
        //Filtra el codigo de estado de la respuesta
        switch (status) {
            case 200:
                await toastr.success(messages, 'Éxito');
                if (reset) {
                    resetForm();
                }
                break;
            case 400:
                let listErrors = ``;
                Object.keys(messages).forEach((error) => {
                    listErrors += `${messages[error]}<br>`;
                })
                await toastr.error(`${listErrors}`, 'Error de validación');
                break;
            default:
                await toastr.error(`${(responseJSON && responseJSON.message) ? responseJSON.message: ''}`, `${messages.error ?? statusText}`);
                break;
        }
    }
    /**
     * Resetear el formulario
     */
    const resetForm = () => {

    }
    $("#status").change((event) => {
        const route = `<?= site_url('admin/settings/maintenance-update') ?>`;
        window.location.href = `${route}/${event.target.value}`
    });
    $("#cache_clean").click((event) => {
        event.preventDefault();
        const route = `<?= site_url(route_to('cache_clean_site')) ?>`;
        window.location.href = `${route}`
    });
    $("#symlink_create").click((event) => {
        event.preventDefault();
        const route = `<?= site_url(route_to('symlink')) ?>`;
        window.location.href = `${route}`
    });
</script>
<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
    #tableCategories tbody tr td button {
        margin: 0px 2px 0px 2px;
        border-radius: 25%;
    }

    #tableCategories tbody tr td {
        vertical-align: middle !important;
    }

    #tableCategories tbody tr td button.edit {
        padding: 4px 4px 4px 6px;
        padding-left: 6px !important;
    }

    #tableCategories tbody tr td button.delete {
        padding: 4px 6px 4px 6px;
    }

    button#create_subcategory {
        padding: 0.5px 5px 1px 5px;
        border-radius: 20px;
        margin-left: 5px;
    }

    @media(max-width:767px) {
        #tableCategories {
            font-size: 13px;
        }
    }
</style>
<?= $this->endSection() ?>