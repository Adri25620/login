<?php
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\LoginController;
use Controllers\RegistroController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class, 'index']);



//url's login
$router->get('/', [LoginController::class, 'index']);
$router->post('/API/login', [LoginController::class, 'index']);



//url's registrar
$router->get('/registro', [RegistroController::class, 'index']);
$router->get('/api/registro/buscar', [RegistroController::class, 'buscarAPI']);
$router->get('/api/registro/eliminar', [RegistroController::class, 'eliminarAPI']);
$router->post('/api/registro/guardar', [RegistroController::class, 'guardarAPI']);



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
