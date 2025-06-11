<?php
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\LoginController;
use Controllers\RegistroController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);



//url's login
$router->get('/', [LoginController::class, 'index']);
$router->post('/API/login', [LoginController::class, 'index']);
$router->get('/inicio', [LoginController::class,'inicio']);



//url's registrar
$router->get('/registro', [RegistroController::class, 'index']);
$router->post('/registro/modificarAPI', [RegistroController::class, 'modificarAPI']);
$router->get('/registro/buscarAPI', [RegistroController::class, 'buscarAPI']);
$router->get('/registro/eliminarAPI', [RegistroController::class, 'eliminarAPI']);
$router->post('/registro/guardarAPI', [RegistroController::class, 'guardarAPI']);





// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
