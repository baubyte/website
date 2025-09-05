<!-- Incluimos InputMask -->
<?= $this->include('Admin/layout/load/inputMask') ?>
<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Experiencia
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
<i class="nav-icon fas fa-industry"></i> Experiencia
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-danger card-outline">
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center" ><span id="company-preview"><?=$experience->company?></span></h3>
                        <p class="text-muted text-center" id="specialty-preview"><?=$experience->specialty_es?></p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item text-center">
                            <span id="start-preview"><?=$experience->start?></span> 
                            <span id="end-preview"><?=$experience->end?></span>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-8">
                <div class="card card-outline card-danger">
                    <div class="card-header p-2">
                        <h4>Experiencia</h4>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings">
                                    <div class="form-group row">
                                        <label for="company" class="col-sm-3 col-form-label">Empresa / Institucion</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                                </div>
                                                <input readonly type="text" name="company" class="form-control" value="<?=$experience->company ?>" placeholder="Empresa / Institucion" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="specialty_es" class="col-sm-3 col-form-label">Especialidad Español</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-jedi"></i></span>
                                                </div>
                                                <input readonly type="text" name="specialty_es" class="form-control" value="<?=$experience->specialty_es ?>" placeholder="Especialidad" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="specialty_en" class="col-sm-3 col-form-label">Especialidad Ingles</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-jedi"></i></span>
                                                </div>
                                                <input readonly type="text" name="specialty_en" class="form-control" value="<?=$experience->specialty_en ?>" placeholder="Especialidad" autocomplete="off">
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
                                                <textarea readonly name="description_es" class="form-control" placeholder="Reseña" autocomplete="off"><?= old('description_es')  ?? $experience->description_es ?></textarea>
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
                                                <textarea readonly name="description_en" class="form-control" placeholder="Reseña" autocomplete="off"><?=$experience->description_en ?></textarea>
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
                                                <input id="start" readonly type="text" name="start" class="form-control" value="<?= old('start')  ??$experience->start ?>" autocomplete="off"
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
                                                <input id="end" type="text" name="end" class="form-control" value="<?=$experience->end ?>" autocomplete="off"
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