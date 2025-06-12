<?php
require_once __DIR__ . '/../includes/app.php';

use Controllers\AplicacionController;
use MVC\Router;
use Controllers\AppController;
use Controllers\LoginController;
use Controllers\PermisoController;
use Controllers\RegistroController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);



//url's login
$router->get('/', [LoginController::class, 'index']);
$router->post('/API/login', [LoginController::class, 'index']);
$router->get('/inicio', [LoginController::class,'inicio']);



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



//url's registrar aplicaciones
$router->get('/permisos', [PermisoController::class, 'index']);
$router->post('/permisos/modificarAPI', [PermisoController::class, 'modificarAPI']);
$router->get('/permisos/buscarAPI', [PermisoController::class, 'buscarAPI']);
$router->get('/permisos/eliminarAPI', [PermisoController::class, 'eliminarAPI']);
$router->post('/permisos/guardarAPI', [PermisoController::class, 'guardarAPI']);





// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
