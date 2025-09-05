  <!-- ======= Header ======= -->
  <header>
    <div class="profile-page sidebar-collapse">
      <nav class="navbar navbar-expand-lg fixed-top navbar-transparent bg-primary" color-on-scroll="400">
        <div class="container">
          <div class="navbar-translate"><a class="navbar-brand" href="#" rel="tooltip">BAUBYTE CV</a>
            <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
              aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation"><span
                class="navbar-toggler-bar bar1"></span><span class="navbar-toggler-bar bar2"></span><span
                class="navbar-toggler-bar bar3"></span></button>
          </div>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
              <li class="nav-item"><a class="nav-link smooth-scroll" href="#about"><?=lang('App.about')?></a></li>
              <li class="nav-item"><a class="nav-link smooth-scroll" href="#skill"><?=lang('App.skills')?></a></li>
              <li class="nav-item"><a class="nav-link smooth-scroll" href="#experience"><?=lang('App.experience')?></a></li>
              <li class="nav-item"><a class="nav-link smooth-scroll" href="#contact"><?=lang('App.contact')?></a></li>
              <li class="nav-item"><a class="nav-link smooth-scroll" href="<?=(session('locale') == 'es') ? site_url(route_to('locale', 'en')) : site_url(route_to('locale', 'es'))?>"><i class="fa fa-language"></i></a></li>  
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>
  <!-- End Header -->