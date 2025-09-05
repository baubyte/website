<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultController('HomeController');
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
/**Grupo de Rutas Front*/
$routes->group('/',['namespace' => 'App\Controllers\Front'],function($routes){
	$routes->get('', 'HomeController::index', ['as' => 'home']);
	$routes->post('contact/', 'HomeController::contact', ['as' => 'contact']);
    $routes->get('/download-cv', 'HomeController::generatePDF', ['as' => 'generate_pdf']);
});
//Ruta para establecer el idioma
$routes->get('locale/(:segment)', 'LocaleController::set/$1', ['as' => 'locale']);
/**
 *Rutas Render Files
 **/
$routes->group('/files',['namespace' => 'App\Controllers\Files'],function($routes){
	$routes->match(['get','post'],'image-profile/(:segment)', 'RenderImageController::profile/$1', ['as' => 'image_profile']);
});

/**Grupo de Rutas Administrador 
 * usamos un filtro para validar si el usuario inicio sesión
 * también le pasamos parámetros para ver que roles pueden ingresar
*/

$routes->group('admin',['filter'=>'role:admin'],function($routes){
    /**
     * Rutas Admin.
     **/
    $routes->group('/', [
        'filter'    => 'permission:admin-website',
        'namespace' => 'App\Controllers\Admin',
    ], function ($routes) {
        $routes->get('/',  'DashboardController::index', ['as' => 'dashboard']);
    });
    /**
     * Rutas Profile.
     **/
    $routes->group('profile', [
        'filter'    => 'permission:admin-website',
        'namespace' => 'App\Controllers\Admin',
    ], function ($routes) {
        $routes->get('/',  'ProfileController::index', ['as' => 'profile']);
        $routes->get('datatable/',  'ProfileController::getDataTableProfile', ['as' => 'datatable_profile']);
        $routes->get('create/',  'ProfileController::create', ['as' => 'create_profile']);
        $routes->post('store/',  'ProfileController::store', ['as' => 'store_profile']);
        $routes->get('show/(:num)',  'ProfileController::show/$1', ['as' => 'show_profile']);
        $routes->get('edit/(:num)',  'ProfileController::edit/$1', ['as' => 'edit_profile']);
        $routes->post('update/',  'ProfileController::update', ['as' => 'update_profile']);
        $routes->get('delete/(:num)',  'ProfileController::delete/$1', ['as' => 'delete_profile']);
    });
    /**
     * Rutas Skill.
     **/
    $routes->group('skill', [
        'filter'    => 'permission:admin-website',
        'namespace' => 'App\Controllers\Admin',
    ], function ($routes) {
        $routes->get('/',  'SkillController::index', ['as' => 'skill']);
        $routes->get('datatable/',  'SkillController::getDataTableSkill', ['as' => 'datatable_skill']);
        $routes->get('create/',  'SkillController::create', ['as' => 'create_skill']);
        $routes->post('store/',  'SkillController::store', ['as' => 'store_skill']);
        $routes->get('show/(:num)',  'SkillController::show/$1', ['as' => 'show_skill']);
        $routes->get('edit/(:num)',  'SkillController::edit/$1', ['as' => 'edit_skill']);
        $routes->post('update/',  'SkillController::update', ['as' => 'update_skill']);
        $routes->get('delete/(:num)',  'SkillController::delete/$1', ['as' => 'delete_skill']);
    });
    /**
     * Rutas Experience.
     **/
    $routes->group('experience', [
        'filter'    => 'permission:admin-website',
        'namespace' => 'App\Controllers\Admin',
    ], function ($routes) {
        $routes->get('/',  'ExperienceController::index', ['as' => 'experience']);
        $routes->get('datatable/',  'ExperienceController::getDataTableexperience', ['as' => 'datatable_experience']);
        $routes->get('create/',  'ExperienceController::create', ['as' => 'create_experience']);
        $routes->post('store/',  'ExperienceController::store', ['as' => 'store_experience']);
        $routes->get('show/(:num)',  'ExperienceController::show/$1', ['as' => 'show_experience']);
        $routes->get('edit/(:num)',  'ExperienceController::edit/$1', ['as' => 'edit_experience']);
        $routes->post('update/',  'ExperienceController::update', ['as' => 'update_experience']);
        $routes->get('delete/(:num)',  'ExperienceController::delete/$1', ['as' => 'delete_experience']);
    });
    /**
     * Rutas Study.
     **/
    $routes->group('study', [
        'filter'    => 'permission:admin-website',
        'namespace' => 'App\Controllers\Admin',
    ], function ($routes) {
        $routes->get('/',  'StudyController::index', ['as' => 'study']);
        $routes->get('datatable/',  'StudyController::getDataTablestudy', ['as' => 'datatable_study']);
        $routes->get('create/',  'StudyController::create', ['as' => 'create_study']);
        $routes->post('store/',  'StudyController::store', ['as' => 'store_study']);
        $routes->get('show/(:num)',  'StudyController::show/$1', ['as' => 'show_study']);
        $routes->get('edit/(:num)',  'StudyController::edit/$1', ['as' => 'edit_study']);
        $routes->post('update/',  'StudyController::update', ['as' => 'update_study']);
        $routes->get('delete/(:num)',  'StudyController::delete/$1', ['as' => 'delete_study']);
    });
    /**
     * Ruta para establecer enlace simbolico
     */
    $routes->group('symlink', [
        'filter'    => 'permission:admin-website',
        'namespace' => 'App\Controllers\Admin',
    ], function ($routes) {
        $routes->get('', 'DashboardController::set', ['as' => 'symlink']);
    });
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
