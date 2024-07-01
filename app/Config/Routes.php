<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::login');
$routes->get('logout', 'Login::logout');

// Dashboard
$routes->get('inicio', 'Inicio::index', ["filter" => "auth"]);

// Productos
$routes->resource('productos', ['placeholder' => '(:num)', 'except' => 'show', "filter" => "auth"]);
$routes->get('productos/autocompleteData?(:any)', 'Productos::autocompleteData/$1', ["filter" => "auth"]);

$routes->resource('clientes', ['placeholder' => '(:num)', 'except' => 'show', "filter" => "auth"]);
$routes->get('clientes/autocompleteData?(:any)', 'Clientes::autocompleteData/$1', ["filter" => "auth"]);

$routes->get('superadmin/cambiarSucursal/(:num)', 'Sucursal::cambiarSucursal/$1');

$routes->resource('sucursal', ["filter" => "auth"]);
$routes->resource('usuarios', ['placeholder' => '(:num)', 'except' => 'show', "filter" => "auth"]);
$routes->resource('roles', ['placeholder' => '(:num)', 'except' => 'show', "filter" => "auth"]);
// Nuevaventa
$routes->get('nuevaventa', 'Caja::index', ["filter" => "auth"]);
$routes->post('nuevaventa/inserta', 'Caja::inserta', ["filter" => "auth"]);
$routes->post('nuevaventa/elimina', 'Caja::elimina', ["filter" => "auth"]);

// Ventas
$routes->get('ventas', 'Ventas::index', ["filter" => "auth"]);
$routes->get('ventas/bajas', 'Ventas::bajas', ["filter" => "auth"]);
$routes->post('ventas', 'Ventas::guarda', ["filter" => "auth"]);
$routes->delete('ventas/(:num)', 'Ventas::cancelar/$1', ["filter" => "auth"]);
$routes->get('ventas/imprimirTicket/(:num)', 'Ventas::verTicket/$1', ["filter" => "auth"]);
$routes->get('ventas/generaTicket/(:num)', 'Ventas::generaTicket/$1', ["filter" => "auth"]);

// Reportes
$routes->get('reportes/crea_ventas', 'Reportes::creaVentas', ["filter" => "auth"]);
$routes->post('reportes/ventas', 'Reportes::verReporteVentas', ["filter" => "auth"]);
$routes->get('reportes/genera_ventas/(:segment)/(:segment)/(:num)', 'Reportes::generaVentas/$1/$2/$3', ["filter" => "auth"]);
$routes->get('reportes/productos', 'Reportes::verReporteProductos', ["filter" => "auth"]);
$routes->get('reportes/genera_productos', 'Reportes::generaProductos', ["filter" => "auth"]);
$routes->post('reportes/procesarImportacion', 'Reportes::procesarImportacion', ["filter" => "auth"]);

// Usuarios
$routes->get('perfil/cambiarClave', 'Login::cambiaPassword', ["filter" => "auth"]);
$routes->post('perfil/cambiarClave', 'Login::actualizaPassword', ["filter" => "auth"]);
