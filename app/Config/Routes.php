<?php

namespace Config;

use CodeIgniter\HTTP\ResponseInterface;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/',						'Home::index');
$routes->post('api/v1/register',		'Register::index');
$routes->post('api/v1/login',			'Login::index');
$routes->get('api/v1/me',				'Me::index', ['filter' => 'auth']);
//HERO IMAGES HANDLER
$routes->post('api/v1/upload-hero',		'Image::uploadImage');
$routes->get('api/v1/hero-images', 		'Image::listsHero');
$routes->get('api/v1/gallery-images', 	'Image::listGallery');
$routes->delete('api/v1/delete-hero', 	'Image::deleteImage');
$routes->set404Override(function () {
	return 'Sorry, route not found!';
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
