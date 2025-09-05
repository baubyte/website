<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Perfil
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
<i class="nav-icon fab fa-teamspeak"></i> Perfil
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-success card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="<?= base_url('uploads/profile/images/'.$profile->avatar)?>" alt="User profile picture" id="preview">
                        </div>
                        <h3 class="profile-username text-center" ><span id="name-preview"><?=$profile->fullname?></h3>
                        <p class="text-muted text-center" id="specialty-preview"><?=$profile->specialty_es?></p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item text-center">
                                <a class="btn" href="<?=$profile->github_url?>" target="_blank" id="github_href"><i class="fab fa-github"></i></a>
                                <a class="btn" href="<?=$profile->linkedin_url?>" target="_blank" id="linkedin_href"><i class="fab fa-linkedin"></i></a>
                                <a class="btn" href="<?=$profile->instagram_url?>" target="_blank" id="instagram_href"><i class="fab fa-instagram"></i></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-8">
                <div class="card card-outline card-success">
                    <div class="card-header p-2">
                        <h4>Tu Perfil</h4>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">Nombre</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" name="name" class="form-control" value="<?=$profile->name?>" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="surname" class="col-sm-3 col-form-label">Apellido</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" name="surname" class="form-control" value="<?=$profile->surname ?>" readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email_contact" class="col-sm-3 col-form-label">Email de Contacto</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" name="email_contact" class="form-control" value="<?=$profile->email_contact ?>" readonly>
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
                                                <textarea rows="5" name="description_es" class="form-control" readonly><?=$profile->description_es ?></textarea>
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
                                                <textarea rows="5" name="description_en" class="form-control" readonly><?=$profile->description_en ?></textarea>
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
                                                <input type="text" name="specialty_es" class="form-control" value="<?=$profile->specialty_es ?>" readonly>
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
                                                <input type="text" name="specialty_en" class="form-control" value="<?=$profile->specialty_en ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="languages_es" class="col-sm-3 col-form-label">Idiomas Español</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-language"></i></span>
                                                </div>
                                                <input type="text" name="language_es" class="form-control" value="<?=$profile->language_es ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="language_en" class="col-sm-3 col-form-label">Idiomas Ingles</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-language"></i></span>
                                                </div>
                                                <input type="text" name="language_en" class="form-control" value="<?=$profile->language_en ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="github_url" class="col-sm-3 col-form-label">Github</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-github"></i></span>
                                                </div>
                                                <input type="url" name="github_url"  id="github_url" class="form-control" value="<?=$profile->github_url?>" readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="linkedin_url" class="col-sm-3 col-form-label">Linkedin</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                                </div>
                                                <input type="url" name="linkedin_url" id="linkedin_url"  class="form-control" value="<?=$profile->linkedin_url?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="instagram_url" class="col-sm-3 col-form-label">Instagram</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                </div>
                                                <input type="url" name="instagram_url" id="instagram_url"  class="form-control <?= session('errors.instagram_url') ? 'is-invalid' : '' ?>" value="<?=$profile->instagram_url?>" readonly>
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