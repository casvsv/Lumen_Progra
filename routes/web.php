<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/prueba','PruebaController@index');

$router->group(['prefix'=>'clientes'], function($router){
	$router->get('/all','ClienteController@index');
	$router->get('/get/{cedula}','ClienteController@getCliente');
	$router->get('/getApellidos/{apellido}','ClienteController@getClienteApellidos');	
	$router->post('/create','ClienteController@createCliente');
	$router->put('/modify/{cedula}','ClienteController@modifyCliente');
});

$router->group(['prefix'=>'cuentas'], function($router){
	$router->get('/all','CuentaController@index');
	$router->get('/get/{numero}','CuentaController@getCuenta');	
#	$router->post('/create','CuentaController@createCuenta');
#	$router->put('/modify/{numero}','CuentaController@modifyCuenta');
});

$router->group(['prefix'=>'usuarios'], function($router){
	$router->post('/ingresar','UserController@login');
	$router->post('/cerrar_sesion','UserController@logout');	
});

$router->post('/transaccion','TransaccionController@realizarTransaccion');
