<!-- Extendemos del Layout Principal -->
<?= $this->extend('Front/layout/main') ?>
<!-- Seccion Titulo -->
<?= $this->section('title') ?>
Developer
<?= $this->endSection() ?>
<!-- Seccion Contenido -->
<?= $this->section('content') ?>
<div>
      <div class="profile-page">
        <div class="wrapper">
          <div class="page-header page-header-small" filter-color="green">
            <div class="page-header-image" data-parallax="true" style="background-image: url('<?=base_url('/assets/front/images/cc-bg-1.jpg')?>')">
            </div>
            <div class="container">
              <div class="content-center">
                <div class="cc-profile-image"><a href="#"><img src="<?= base_url('uploads/profile/images/'.$profile->avatar)?>" alt="Image" /></a></div>
                <div class="h2 title"><?=$profile->fullName?></div>
                <p class="category text-white"><?=$profile->{lang('App.specialty')}?></p><a
                  class="btn btn-primary smooth-scroll mr-2" href="#contact" data-aos="zoom-in"
                  data-aos-anchor="data-aos-anchor"><?=lang('App.contact_me')?></a><a class="btn btn-primary" href="<?= base_url(route_to('generate_pdf'))?>" data-aos="zoom-in"
                  data-aos-anchor="data-aos-anchor" target="_blank">Download CV</a>
              </div>
            </div>
            <div class="section">
              <div class="container">
                <div class="button-container">
                  <a class="btn btn-default btn-round btn-lg btn-icon" href="<?=$profile->github_url?>"
                    rel="tooltip" title="<?=lang('App.tooltip_github')?>" target="_blank">
                    <i class="fa fa-github"></i>
                  </a>
                    <a class="btn btn-default btn-round btn-lg btn-icon" href="<?=$profile->linkedin_url?>" rel="tooltip"
                    title="<?=lang('App.tooltip_linkedin')?>" target="_blank">
                    <i class="fa fa-linkedin"></i>
                  </a>
                  <a class="btn btn-default btn-round btn-lg btn-icon" href="<?=$profile->instagram_url?>" rel="tooltip"
                    title="<?=lang('App.tooltip_instagram')?>" target="_blank">
                    <i class="fa fa-instagram"></i>
                  </a>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     <!-- About -->
      <div class="section" id="about">
        <div class="container">
          <div class="card" data-aos="fade-up" data-aos-offset="10">
            <div class="row">
              <div class="col-lg-6 col-md-12">
                <div class="card-body">
                  <div class="h4 mt-0 title"><?=lang('App.about')?></div>
                  <p><?=$profile->{lang('App.description')}?></p>
                </div>
              </div>
              <div class="col-lg-6 col-md-12">
                <div class="card-body">
                  <div class="h4 mt-0 title"><?=lang('App.info_basic_title')?></div>
                  <div class="row mt-3">
                    <div class="col-sm-4"><strong class="text-uppercase">Email:</strong></div>
                    <div class="col-sm-8"><?=$profile->email_contact?></div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-sm-4"><strong class="text-uppercase"><?=lang('App.language_title')?>:</strong></div>
                    <div class="col-sm-8"><?=$profile->{lang('App.language')}?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /About -->
      <!-- Skills -->
      <div class="section" id="skill">
        <div class="container">
          <div class="h4 text-center mb-4 title">Skills</div>
          <div class="card" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
            <div class="card-body">
              <div class="row">
                <?php foreach ($skills as $skill):?>
                <div class="col-md-6">
                  <div class="progress-container progress-primary"><span class="progress-badge"><?=$skill->name?></span>
                    <div class="progress">
                      <div class="progress-bar progress-bar-primary" data-aos="progress-full" data-aos-offset="10"
                        data-aos-duration="2000" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                        aria-valuemax="100" style="width: <?=$skill->percentage?>%;"></div><span class="progress-value"><?=$skill->percentage?>%</span>
                    </div>
                  </div>
                </div>
                <?php endforeach;?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /Skills -->
      <!-- Experience -->
      <div class="section" id="experience">
        <div class="container cc-experience">
          <div class="h4 text-center mb-4 title"><?=lang('App.experience')?></div>
          <?php foreach($experiences as $experience):?>
          <div class="card">
            <div class="row">
              <div class="col-md-3 bg-primary" data-aos="fade-right" data-aos-offset="50" data-aos-duration="500">
                <div class="card-body cc-experience-header">
                  <p><?=$experience->start?> - <?=$experience->end?></p>
                  <div class="h5"><?=$experience->company?> </div>
                </div>
              </div>
              <div class="col-md-9" data-aos="fade-left" data-aos-offset="50" data-aos-duration="500">
                <div class="card-body">
                  <div class="h5"><?=$experience->{lang('App.specialty')}?> </div>
                  <p><?=$experience->{lang('App.description')}?> </p>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <!-- /Experience -->
      <!-- Education -->
      <div class="section">
        <div class="container cc-education">
          <div class="h4 text-center mb-4 title"><?=lang('App.education')?></div>
          <?php foreach ($studies as $study):?>
          <div class="card">
            <div class="row">
              <div class="col-md-3 bg-primary" data-aos="fade-right" data-aos-offset="50" data-aos-duration="500">
                <div class="card-body cc-education-header">
                  <p><?=$study->start?> - <?=$study->end?></p>
                  <div class="h5"><?=$study->{lang('App.education_title')}?></div>
                </div>
              </div>
              <div class="col-md-9" data-aos="fade-left" data-aos-offset="50" data-aos-duration="500">
                <div class="card-body">
                  <div class="h5"><?=$study->{lang('App.education_title')}?></div>
                  <p class="category"><?=$study->entity?></p>
                  <p><?=$study->{lang('App.description')}?></p>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <!-- /Education -->
      <!-- Contact -->
      <div class="section" id="contact">
        <div class="cc-contact-information" style="background-image: url('<?=base_url('assets/front/images/contacto.jpg')?>')">
          <div class="container">
            <div class="cc-contact">
              <div class="row">
                <div class="col-md-9">
                  <div class="card mb-0" data-aos="zoom-in">
                    <div class="h4 text-center title"><?=lang('App.contact_me')?></div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="card-body">
                          <form action="<?=site_url(route_to('contact'))?>" method="POST">
                          <?= csrf_field() ?>
                            <div class="p pb-3"><strong><?=lang('App.contactDescription')?> </strong></div>
                            <div class="row mb-3">
                              <div class="col">
                                <div class="input-group"><span class="input-group-addon"><i
                                      class="fa fa-user-circle"></i></span>
                                  <input class="form-control  <?= session('errors.name') ? 'is-invalid' : '' ?>" type="text" name="name" placeholder="<?=lang('App.namePlaceHolder')?>"
                                    required="required" value="<?=old('name')?>"/>
                                </div>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col">
                                <div class="input-group"><span class="input-group-addon"><i
                                      class="fa fa-file-text"></i></span>
                                  <input class="form-control  <?= session('errors.subject') ? 'is-invalid' : '' ?>" type="text" name="subject" placeholder="<?=lang('App.subjectPlaceHolder')?>"
                                     value="<?=old('subject')?>" required/>
                                </div>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col">
                                <div class="input-group"><span class="input-group-addon"><i
                                      class="fa fa-envelope"></i></span>
                                  <input class="form-control  <?= session('errors.email') ? 'is-invalid' : '' ?>" type="email" name="email" placeholder="<?=lang('App.emailPlaceHolder')?>"
                                   value="<?=old('email')?>" required/>
                                </div>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col">
                                <div class="form-group">
                                <div class="input-group"><span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                  <textarea rows="3" class="form-control  <?= session('errors.message') ? 'is-invalid' : '' ?>" name="message" placeholder="<?=lang('App.messagePlaceHolder')?>"
                                    required><?=old('message')?></textarea>
                                </div>
                                </div>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col">
                                <div class="form-group">
                                  <?=reCaptcha2('reCaptcha2',['id' => 'reCaptcha_v2'],['theme' => 'light'])?>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <button class="btn btn-primary" type="submit"><?=lang('App.btnSendContact')?></button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="card-body">
                          <p class="mb-0"><strong>Email</strong></p>
                          <p><?=$profile->email_contact?></p>
                          <p class="mb-0"><strong><a href="<?=$profile->linkedin_url?>" target="_blank">Linkedin</a></strong></p>
                          <p></p>
                          <p class="mb-0"><strong><a href="<?=$profile->instagram_url?>" target="_blank">Instagram</a></strong> </p>
                              <!-- Incluimos Mensajes -->
                            <?= $this->include('Front/layout/message_block') ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /Contact -->
</div>
<?= $this->endSection() ?>