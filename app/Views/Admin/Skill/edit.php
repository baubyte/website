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
                        <h3 class="profile-username text-center" ><span id="name-preview"><?= old('name') ?? $skill->name?>
                        </h3>
                        <div class="text-center">
                            <div class="progress progress-xs progress-striped active">
                                <div id="progress" class="progress-bar bg-success" style="width: <?= old('percentage') ?? $skill->percentage ?>%"></div>
                            </div>
                            <span class="text-right badge bg-success" id="percentage-preview"><?= old('percentage') ?? $skill->percentage ?>%</span>
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
                        <h4>Edita tu Skill</h4>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings">
                                <form enctype="multipart/form-data" action="<?= site_url(route_to('update_skill'))?>" method="post" class="form-horizontal" name="formSkill">
                                    <?= csrf_field() ?>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">Nombre Skill</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input required type="text" name="name" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" value="<?= old('name') ?? $skill->name ?>" placeholder="Nombre Skill" autocomplete="off">
                                                <?php if (session('errors.name')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.name') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required min="1" max="100" type="number" name="percentage" class="form-control <?= session('errors.percentage') ? 'is-invalid' : '' ?>" value="<?= old('percentage') ?? $skill->percentage ?>" placeholder="Porcentaje Skill" autocomplete="off">
                                                <?php if (session('errors.percentage')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.percentage') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?=$skill->id?>">
                                    <div class="form-group row">
                                        <div class="col-sm-10">
                                            <div class="float-right">
                                                <div class="btn-group">
                                                    <button type="submit" class="btn btn-block btn-success">
                                                        Editar Skill
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
<script src="<?= base_url('/assets/admin/js/createSkill.js') ?>"></script> 
<?= $this->endSection() ?>