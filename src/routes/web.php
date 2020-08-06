<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// public route
$router->get('/public/barang', 'PublicController\barangController@index');
$router->get('/public/barang/{id}', 'PublicController\barangController@show');
$router->get('/public/barang/image/{imageName}', 'PublicController\BarangController@image');



$router->group(['prefix' => 'auth'], function () use ($router) {
	$router->post('/register','AuthController@register');
	$router->post('/login','AuthController@login');
});

$router->group(['middleware' => 'auth'], function () use ($router) {

	// user
	$router->post('/user', 'UsersController@store');
	$router->get('/users', 'UsersController@index');
	$router->get('/user/{id}', 'UsersController@show');
	$router->put('/user/{id}', 'UsersController@update');
	$router->delete('/user/{id}', 'UsersController@destroy');

	// penyewa
	$router->post('/penyewa', 'penyewaController@store');
	$router->get('/penyewa', 'penyewaController@index');
	$router->get('/penyewa/{id}', 'penyewaController@show');
	$router->put('/penyewa/{id}', 'penyewaController@update');
	$router->delete('/penyewa/{id}', 'penyewaController@destroy');

	// barang
	$router->post('/barang', 'barangController@store');
	$router->get('/barang', 'barangController@index');
	$router->get('/barang/{id}', 'barangController@show');
	$router->put('/barang/{id}', 'barangController@update');
	$router->delete('/barang/{id}', 'barangController@destroy');
	$router->get('/barang/image/{imageName}', 'BarangController@image');

	// sewa
	$router->post('/sewa', 'SewaController@store');
	$router->get('/sewa', 'SewaController@index');
	$router->get('/sewa/{id}', 'SewaController@show');
	$router->put('/sewa/{id}', 'SewaController@update');
	$router->delete('/sewa/{id}', 'SewaController@destroy');

	// pembayaran
	$router->post('/pembayaran', 'PembayaranController@store');
	$router->get('/pembayaran', 'PembayaranController@index');
	$router->get('/pembayaran/{id}', 'PembayaranController@show');
	$router->put('/pembayaran/{id}', 'PembayaranController@update');
	$router->delete('/pembayaran/{id}', 'PembayaranController@destroy');
		
});


	