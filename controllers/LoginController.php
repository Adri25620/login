<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class LoginController extends ActiveRecord
{
    public function index(Router $router)
    {
        $router->render('pages/index', [], 'layouts/principal');

    }

    public function inicio(Router $router)
    {
    
        $router->render('bienvenida/index', [], 'layouts/layout');
    
    }



}