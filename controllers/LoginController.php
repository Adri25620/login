<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;

class LoginController extends ActiveRecord
{
    public function index(Router $router)
    {
        $router->render('login/index', [], 'layouts/principal');
    }
}