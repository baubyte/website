<!-- Extendemos del Layout Principal -->
<?= $this->extend('Admin/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Editar 
<?= $this->endSection() ?>
<!-- Seccion Titulo -->
<?= $this->section('header') ?>
<i class="nav-icon fab fa-teamspeak"></i> Editar
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
                            <img class="profile-user-img img-fluid img-circle" src="<?=!empty($profile->avatar) ? base_url('uploads/profile/images/'.$profile->avatar):'https://cdn.jsdelivr.net/npm/admin-lte@3.0.2/dist/img/avatar.png'?>" alt="User profile picture" id="preview">
                        </div>
                        <h3 class="profile-username text-center" ><span id="name-preview"><?= old('name') ?? $profile->name?></span> <span id="surname-preview"><?= old('surname') ?? $profile->surname?></span></h3>
                        <p class="text-muted text-center" id="specialty-preview"><?= old('specialty_es') ?? $profile->specialty_es?></p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item text-center">
                                <a class="btn" href="<?= old('github_url') ?? $profile->github_url?>" target="_blank" id="github_href"><i class="fab fa-github"></i></a>
                                <a class="btn" href="<?= old('linkedin_url') ?? $profile->linkedin_url?>" target="_blank" id="linkedin_href"><i class="fab fa-linkedin"></i></a>
                                <a class="btn" href="<?= old('instagram_url') ?? $profile->instagram_url?>" target="_blank" id="instagram_href"><i class="fab fa-instagram"></i></a>
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
                        <h4>Edita tu Perfil</h4>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings">
                                <form enctype="multipart/form-data" action="<?= site_url(route_to('update_profile'))?>" method="post" class="form-horizontal" name="formProfile">
                                    <?= csrf_field() ?>
                                    <div class="form-group row">
                                        <label for="avatar" class="col-sm-3 col-form-label">Foto de Perfil</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input <?= session('errors.avatar') ? 'is-invalid' : '' ?>" id="avatar" name="avatar">
                                                    <label class="custom-file-label" for="avatar">Subir Imagen</label>
                                                </div>
                                                <?php if (session('errors.avatar')) : ?>
                                                    <div class="invalid-feedback d-block">
                                                        <h6><?=session('errors.avatar') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">Nombre</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input required type="text" name="name" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" value="<?= old('name') ?? $profile->name ?>" placeholder="Nombre" autocomplete="off">
                                                <?php if (session('errors.name')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.name') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="text" name="surname" class="form-control <?= session('errors.surname') ? 'is-invalid' : '' ?>" value="<?= old('surname') ?? $profile->surname?>" placeholder="Apellido" autocomplete="off">
                                                <?php if (session('errors.surname')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.surname') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="email" name="email_contact" class="form-control <?= session('errors.email_contact') ? 'is-invalid' : '' ?>" value="<?= old('email_contact') ?? $profile->email_contact ?>" placeholder="Email" autocomplete="off">
                                                <?php if (session('errors.email_contact')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.email_contact') ?></h6>
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
                                                <textarea required rows="5" name="description_es" class="form-control <?= session('errors.description_es') ? 'is-invalid' : '' ?>" placeholder="Reseña" autocomplete="off"><?= old('description_es')  ?? $profile->description_es ?></textarea>
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
                                                <textarea required rows="5" name="description_en" class="form-control <?= session('errors.description_en') ? 'is-invalid' : '' ?>" placeholder="Reseña" autocomplete="off"><?= old('description_en')  ?? $profile->description_en ?></textarea>
                                                <?php if (session('errors.description_en')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.description_en') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="text" name="specialty_es" class="form-control <?= session('errors.specialty_es') ? 'is-invalid' : '' ?>" value="<?= old('specialty_es') ?? $profile->specialty_es ?>" placeholder="Especialidad" autocomplete="off">
                                                <?php if (session('errors.specialty_es')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.specialty_es') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="text" name="specialty_en" class="form-control <?= session('errors.specialty_en') ? 'is-invalid' : '' ?>" value="<?= old('specialty_en') ?? $profile->specialty_en ?>" placeholder="Especialidad" autocomplete="off">
                                                <?php if (session('errors.specialty_en')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.specialty_en') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="text" name="language_es" class="form-control <?= session('errors.language_es') ? 'is-invalid' : '' ?>" value="<?= old('language_es') ?? $profile->language_es ?>" placeholder="Idiomas" autocomplete="off">
                                                <?php if (session('errors.language_es')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.language_es') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="text" name="language_en" class="form-control <?= session('errors.language_en') ? 'is-invalid' : '' ?>" value="<?= old('language_en') ?? $profile->language_en ?>" placeholder="Idiomas" autocomplete="off">
                                                <?php if (session('errors.language_en')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.language_en') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="url" name="github_url"  id="github_url" class="form-control <?= session('errors.github_url') ? 'is-invalid' : '' ?>" value="<?= old('github_url') ?? $profile->github_url ?>" placeholder="URL Perfil Github" autocomplete="off">
                                                <?php if (session('errors.github_url')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.github_url') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="url" name="linkedin_url" id="linkedin_url"  class="form-control <?= session('errors.linkedin_url') ? 'is-invalid' : '' ?>" value="<?= old('linkedin_url') ?? $profile->linkedin_url ?>" placeholder="URL Perfil Likedin" autocomplete="off">
                                                <?php if (session('errors.linkedin_url')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.linkedin_url') ?></h6>
                                                    </div>
                                                <?php endif; ?>
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
                                                <input required type="url" name="instagram_url" id="instagram_url"  class="form-control <?= session('errors.instagram_url') ? 'is-invalid' : '' ?>" value="<?= old('instagram_url') ?? $profile->instagram_url ?>" placeholder="URL Perfil Instagram" autocomplete="off">
                                                <?php if (session('errors.instagram_url')) : ?>
                                                    <div class="invalid-feedback">
                                                        <h6><?= session('errors.instagram_url') ?></h6>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?= $profile->id ?>">
                                    <input type="hidden" name="oldAvatar" value="<?= $profile->avatar?>">
                                    <div class="form-group row">
                                        <div class="col-sm-10">
                                            <div class="float-right">
                                                <div class="btn-group">
                                                    <button type="submit" class="btn btn-block btn-success">
                                                        Editar Perfil
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
<script src="<?= base_url('/assets/admin/js/createProfile.js') ?>"></script> 
<?= $this->endSection() ?>