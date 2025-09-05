<!-- Incluimos InputMask -->
<?= $this->include('Admin/layout/load/inputMask') ?>
<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Alta
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
<i class="nav-icon fas fa-user-graduate"></i> Alta
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-warning card-outline">
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center" ><span id="entity-preview"><?= old('entity') ?? '***'?></span></h3>
                        <p class="text-muted text-center" id="title-preview"><?= old('title_es') ?? '***'?></p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item text-center">
                            <span id="start-preview">Fecha Inicio</span> 
                            <span id="end-preview">Actualmente</span>
                            </li>
                            
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-8">
                <div class="card card-outline card-warning">
                    <div class="card-header p-2">
                        <h4>Completa tu Estudio</h4>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings">
                                <form enctype="multipart/form-data" action="<?= site_url(route_to('store_study'))?>" method="post" class="form-horizontal" name="formStudy">
                                    <?= csrf_field() ?>
                                    <div class="form-group row">
                                        <label for="entity" class="col-sm-3 col-form-label">Institucion</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                </div>
                                                <input required type="text" name="entity" class="form-control <?= session('errors.entity') ? 'is-invalid' : '' ?>" value="<?= old('entity') ?>" placeholder="Institucion" autocomplete="off">
                                                <?php if (session('errors.entity')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.entity') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="title_es" class="col-sm-3 col-form-label">Titulo Español</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                                </div>
                                                <input required type="text" name="title_es" class="form-control <?= session('errors.title_es') ? 'is-invalid' : '' ?>" value="<?= old('title_es') ?>" placeholder="Titulo" autocomplete="off">
                                                <?php if (session('errors.title_es')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.title_es') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="title_en" class="col-sm-3 col-form-label">Titulo Ingles</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                                </div>
                                                <input required type="text" name="title_en" class="form-control <?= session('errors.title_en') ? 'is-invalid' : '' ?>" value="<?= old('title_en') ?>" placeholder="Titulo" autocomplete="off">
                                                <?php if (session('errors.title_en')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.title_en') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description_es" class="col-sm-3 col-form-label">Breve Reseña Español</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                </div>
                                                <textarea name="description_es" class="form-control <?= session('errors.description_es') ? 'is-invalid' : '' ?>" placeholder="Reseña" autocomplete="off"><?= old('description_es') ?></textarea>
                                                <?php if (session('errors.description_es')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.description_es') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="description_en" class="col-sm-3 col-form-label">Breve Reseña Ingles</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                </div>
                                                <textarea name="description_en" class="form-control <?= session('errors.description_en') ? 'is-invalid' : '' ?>" placeholder="Reseña" autocomplete="off"><?= old('description_en') ?></textarea>
                                                <?php if (session('errors.description_en')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.description_en') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="start" class="col-sm-3 col-form-label">Fecha Inicio</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input id="start" required type="text" name="start" class="form-control <?= session('errors.start') ? 'is-invalid' : '' ?>" value="<?= old('start') ?>" autocomplete="off"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                >
                                                <?php if (session('errors.start')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.start') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="end" class="col-sm-3 col-form-label">Fecha Fin</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input id="end" type="text" name="end" class="form-control <?= session('errors.end') ? 'is-invalid' : '' ?>" value="<?= old('end') ?>" autocomplete="off"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                >
                                                <?php if (session('errors.end')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.end') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-10">
                                            <div class="float-right">
                                                <div class="btn-group">
                                                    <button type="submit" class="btn btn-block btn-success">
                                                        Guardar Estudio
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
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
<script src="<?= base_url('/assets/admin/js/createStudy.js') ?>"></script> 
<?= $this->endSection() ?>