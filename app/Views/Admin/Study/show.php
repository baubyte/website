<!-- Incluimos InputMask -->
<?= $this->include('Admin/layout/load/inputMask') ?>
<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Estudio
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
<i class="nav-icon fas fa-user-graduate"></i> Estudio
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
                        <h3 class="profile-username text-center" ><span id="entity-preview"><?=$study->entity?></span></h3>
                        <p class="text-muted text-center" id="title-preview"><?=$study->title_es?></p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item text-center">
                            <span id="start-preview"><?=$study->start?></span> 
                            <span id="end-preview"><?=$study->end?></span>
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
                        <h4>Estudio</h4>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings">
                                    <div class="form-group row">
                                        <label for="entity" class="col-sm-3 col-form-label">Institucion</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                </div>
                                                <input readonly type="text" name="entity" class="form-control" value="<?=$study->entity ?>" placeholder="Institucion" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="title_es" class="col-sm-3 col-form-label">Especialidad Español</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                                </div>
                                                <input readonly type="text" name="title_es" class="form-control" value="<?=$study->title_es ?>" placeholder="Especialidad" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="title_en" class="col-sm-3 col-form-label">Especialidad Ingles</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                                </div>
                                                <input readonly type="text" name="title_en" class="form-control" value="<?=$study->title_en ?>" placeholder="Especialidad" autocomplete="off">
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
                                                <textarea readonly name="description_es" class="form-control" placeholder="Reseña" autocomplete="off"><?=$study->description_es ?></textarea>
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
                                                <textarea readonly name="description_en" class="form-control" placeholder="Reseña" autocomplete="off"><?=$study->description_en ?></textarea>
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
                                                <input id="start" readonly type="text" name="start" class="form-control" value="<?=$study->start ?>" autocomplete="off"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                >
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
                                                <input id="end" type="text" name="end" class="form-control" value="<?=$study->end ?>" autocomplete="off"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                >
                                            </div>
                                        </div>
                                    </div>
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