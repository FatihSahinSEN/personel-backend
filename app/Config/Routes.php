<?php

namespace Config;

if(isset($_SERVER['REQUEST_METHOD'])){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin,X-Requested-With, Content-Type, Accept, Access-Control-Requested-Method, Authorization");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == "OPTIONS"){
        die();
    }
}
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

// Home
$routes->get('/', 'Api::index');

// Auth Login
$routes->post('/login', 'Auth::login');

// Auth Token Refresh
$routes->post('/refresh', 'Auth::refresh');

// Kullanıcılar
$routes->resource('kullanicilar', ['filter' => 'auth']);
// Evrak Tipleri
$routes->resource('evraktipleri', ['filter' => 'auth']);
// Sigorta Şirketleri
$routes->resource('sigortasirketleri', ['filter' => 'auth']);
// Meslek Gruplari
$routes->resource('meslekgruplari', ['filter' => 'auth']);
// Ülkeler
$routes->resource('ulkeler', ['filter' => 'auth']);
// Uyruklar
$routes->resource('uyruklar', ['filter' => 'auth']);
// Uyruklar
$routes->resource('personeller', ['filter' => 'auth']);
// Uyruklar
$routes->resource('dosyalar');
// Sözleşmeler
$routes->resource('firmalar');
// Sözleşmeler
$routes->resource('sozlesmeler');
// Download
$routes->get('/download/allfiles/(:id)', 'Download::allfiles/$1', ['filter' => 'auth']);
$routes->get('/download/single/(:id)', 'Download::single/$1', ['filter' => 'auth']);
// Şehirler
$routes->get('/sehirler', 'sehirler::index', ['filter' => 'auth']);
$routes->get('/sehirler/(:any)', 'sehirler::filter/$1', ['filter' => 'auth']);
$routes->get('/sehir/(:num)', 'sehirler::show/$1', ['filter' => 'auth']);
$routes->patch('/sehirler/(:num)', 'sehirler::update/$1', ['filter' => 'auth']);
$routes->put('/sehirler/(:num)', 'sehirler::update/$1', ['filter' => 'auth']);
$routes->post('/sehirler', 'sehirler::create', ['filter' => 'auth']);

//Posta Kodları
$routes->get('/postakodlari', 'PostaKodlari::index', ['filter' => 'auth']);
$routes->get('/postakodlari/(:num)', 'PostaKodlari::filter/$1', ['filter' => 'auth']);
$routes->get('/postakodu/(:num)', 'PostaKodlari::show/$1', ['filter' => 'auth']);
$routes->patch('/postakodlari/(:num)', 'PostaKodlari::update/$1', ['filter' => 'auth']);
$routes->put('/postakodlari/(:num)', 'PostaKodlari::update/$1', ['filter' => 'auth']);
$routes->post('/postakodlari', 'PostaKodlari::create', ['filter' => 'auth']);

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
