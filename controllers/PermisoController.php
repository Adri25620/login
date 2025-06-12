<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Permisos;
use MVC\Router;

class PermisoController extends ActiveRecord
{

    public function index(Router $router)
    {
    
        $router->render('permisos/index', [], 'layouts/layout');
    
    }



}