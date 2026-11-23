<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->addRedirect('profil', 'profil/sejarah', 301);
$routes->get('profil/sejarah', 'Publik::sejarah');
$routes->get('profil/visi-misi', 'Publik::visiMisi');
$routes->get('profil/struktur-majelis', 'Publik::strukturMajelis');
$routes->get('jadwal', 'Publik::jadwal');
$routes->get('warta', 'Publik::warta');
$routes->get('warta/(:segment)', 'Publik::wartaDetail/$1');
$routes->addRedirect('pelayanan', 'pelayanan/wadah', 301);
$routes->get('pelayanan/wadah', 'Publik::wadahPelayanan');
$routes->get('pelayanan/sektor-unit', 'Publik::sektorUnit');
$routes->get('galeri', 'Publik::galeri');
$routes->get('kontak', 'Publik::kontak');
$routes->post('kontak/kirim', 'Publik::kirimPesan', ['filter' => 'csrf']);

$routes->get('admin/login', 'Admin\Auth::loginForm');
$routes->post('admin/login', 'Admin\Auth::login', ['filter' => 'csrf']);
$routes->get('admin/setup', 'Admin\Auth::setupForm');
$routes->post('admin/setup', 'Admin\Auth::createFirstAdmin', ['filter' => 'csrf']);
$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes): void {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('panduan', 'Admin\Guide::index');
    $routes->post('logout', 'Admin\Auth::logout', ['filter' => 'csrf']);
    $routes->get('akun', 'Admin\Profile::index');
    $routes->post('akun/kata-sandi', 'Admin\Profile::updatePassword', ['filter' => 'csrf']);
    $routes->get('jadwal', 'Admin\Schedule::index');
    $routes->get('jadwal/tambah', 'Admin\Schedule::newSchedule');
    $routes->post('jadwal', 'Admin\Schedule::create', ['filter' => 'csrf']);
    $routes->get('jadwal/(:num)/ubah', 'Admin\Schedule::edit/$1');
    $routes->post('jadwal/(:num)', 'Admin\Schedule::update/$1', ['filter' => 'csrf']);
    $routes->post('jadwal/(:num)/hapus', 'Admin\Schedule::delete/$1', ['filter' => 'csrf']);
    $routes->get('pesan', 'Admin\Inbox::index');
    $routes->post('pesan/(:num)', 'Admin\Inbox::update/$1', ['filter' => 'csrf']);
    $routes->post('pesan/(:num)/hapus', 'Admin\Inbox::delete/$1', ['filter' => 'csrf']);
    $routes->get('konten', 'Admin\Content::index');
    $routes->get('konten/(:segment)', 'Admin\Content::index/$1');
    $routes->post('konten/beranda/foto', 'Admin\Content::updateImage', ['filter' => 'csrf']);
    $routes->post('konten/beranda/foto/hapus', 'Admin\Content::deleteImage', ['filter' => 'csrf']);
    $routes->post('konten/(:segment)', 'Admin\Content::save/$1', ['filter' => 'csrf']);
    $routes->post('konten/(:segment)/geser', 'Admin\Content::moveSection/$1', ['filter' => 'csrf']);
    $routes->get('galeri', 'Admin\Gallery::index');
    $routes->post('galeri', 'Admin\Gallery::upload', ['filter' => 'csrf']);
    $routes->get('galeri/(:num)/ubah', 'Admin\Gallery::edit/$1');
    $routes->post('galeri/(:num)/ubah', 'Admin\Gallery::update/$1', ['filter' => 'csrf']);
    $routes->post('galeri/(:num)/hapus', 'Admin\Gallery::delete/$1', ['filter' => 'csrf']);
    $routes->get('warta', 'Admin\Warta::index');
    $routes->get('warta/tambah', 'Admin\Warta::newArticle');
    $routes->post('warta', 'Admin\Warta::create', ['filter' => 'csrf']);
    $routes->get('warta/(:num)/ubah', 'Admin\Warta::edit/$1');
    $routes->post('warta/(:num)', 'Admin\Warta::update/$1', ['filter' => 'csrf']);
    $routes->post('warta/(:num)/hapus', 'Admin\Warta::delete/$1', ['filter' => 'csrf']);
    $routes->group('pengelola', ['filter' => 'adminRole:superadmin'], static function ($routes): void {
        $routes->get('/', 'Admin\Users::index');
        $routes->post('/', 'Admin\Users::create', ['filter' => 'csrf']);
        $routes->post('(:num)/status', 'Admin\Users::updateStatus/$1', ['filter' => 'csrf']);
        $routes->post('(:num)/hapus', 'Admin\Users::delete/$1', ['filter' => 'csrf']);
    });
});
