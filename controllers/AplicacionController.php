<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Aplicacion;
use MVC\Router;

class AplicacionController extends ActiveRecord
{

    public function index(Router $router)
    {
    
        $router->render('aplicaciones/index', [], 'layouts/layout');
    
    }



}