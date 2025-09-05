<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Editar
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
<i class="nav-icon fas fa-jedi"></i> Editar
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center" ><span id="name-preview"><?=$skill->name?>
                        </h3>
                        <div class="text-center">
                            <div class="progress progress-xs progress-striped active">
                                <div id="progress" class="progress-bar bg-success" style="width: <?=$skill->percentage ?>%"></div>
                            </div>
                            <span class="text-right badge bg-success" id="percentage-preview"><?= $skill->percentage ?>%</span>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header p-2">
                        <h4>Tu Skill</h4>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">Nombre Skill</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input required type="text" name="name" class="form-control" readonly value="<?=$skill->name ?>" placeholder="Nombre Skill" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="percentage" class="col-sm-3 col-form-label">Porcentaje del Skill</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                </div>
                                                <input required min="1" max="100" type="number" name="percentage" class="form-control" value="$skill->percentage ?>"  readonly placeholder="Porcentaje Skill" autocomplete="off">
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