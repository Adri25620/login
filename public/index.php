<?php
require_once __DIR__ . '/../includes/app.php';

use Controllers\AplicacionController;
use MVC\Router;
use Controllers\AppController;
use Controllers\AsignacionController;
use Controllers\ClienteController;
use Controllers\LoginController;
use Controllers\MarcaCelController;
use Controllers\PermisoController;
use Controllers\RegistroController;
use Controllers\RolController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);



//url's login
$router->get('/', [LoginController::class, 'index']);
$router->post('/API/login', [LoginController::class, 'login']);
$router->get('/inicio', [LoginController::class,'inicio']);
$router->get('/logout', [LoginController::class,'logout']); // ESTA FALTABA


//url's registrar usuario
$router->get('/registro', [RegistroController::class, 'index']);
$router->post('/registro/modificarAPI', [RegistroController::class, 'modificarAPI']);
$router->get('/registro/buscarAPI', [RegistroController::class, 'buscarAPI']);
$router->get('/registro/eliminarAPI', [RegistroController::class, 'eliminarAPI']);
$router->post('/registro/guardarAPI', [RegistroController::class, 'guardarAPI']);



//url's registrar aplicaciones
$router->get('/aplicacion', [AplicacionController::class, 'index']);
$router->post('/aplicacion/modificarAPI', [AplicacionController::class, 'modificarAPI']);
$router->get('/aplicacion/buscarAPI', [AplicacionController::class, 'buscarAPI']);
$router->get('/aplicacion/eliminarAPI', [AplicacionController::class, 'eliminarAPI']);
$router->post('/aplicacion/guardarAPI', [AplicacionController::class, 'guardarAPI']);



//url's registrar permisos
$router->get('/asignacion', [AsignacionController::class, 'index']);
$router->post('/asignacion/modificarAPI', [AsignacionController::class, 'modificarAPI']);
$router->get('/asignacion/buscarAPI', [AsignacionController::class, 'buscarAPI']);
$router->get('/asignacion/eliminarAPI', [AsignacionController::class, 'eliminarAPI']);
$router->get('/asignacion/finPermisoAPI', [AsignacionController::class, 'finPermisoAPI']);
$router->post('/asignacion/guardarAPI', [AsignacionController::class, 'guardarAPI']);



//url's registrar permiso
$router->get('/permiso', [PermisoController::class, 'index']);
$router->post('/permiso/modificarAPI', [PermisoController::class, 'modificarAPI']);
$router->get('/permiso/buscarAPI', [PermisoController::class, 'buscarAPI']);
$router->get('/permiso/eliminarAPI', [PermisoController::class, 'eliminarAPI']);
$router->post('/permiso/guardarAPI', [PermisoController::class, 'guardarAPI']);




//url's registrar marcas celulares
$router->get('/marcas', [MarcaCelController::class, 'index']);
$router->post('/marcas/modificarAPI', [MarcaCelController::class, 'modificarAPI']);
$router->get('/marcas/buscarAPI', [MarcaCelController::class, 'buscarAPI']);
$router->get('/marcas/eliminarAPI', [MarcaCelController::class, 'eliminarAPI']);
$router->post('/marcas/guardarAPI', [MarcaCelController::class, 'guardarAPI']);



//url's registrar clientes clientes
$router->get('/clientes', [ClienteController::class, 'index']);
$router->post('/clientes/modificarAPI', [ClienteController::class, 'modificarAPI']);
$router->get('/clientes/buscarAPI', [ClienteController::class, 'buscarAPI']);
$router->get('/clientes/eliminarAPI', [ClienteController::class, 'eliminarAPI']);
$router->post('/clientes/guardarAPI', [ClienteController::class, 'guardarAPI']);




// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
