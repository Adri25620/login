<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Roles;
use MVC\Router;

class RolController extends ActiveRecord
{

    public function index(Router $router)
    {
    
        $router->render('roles/index', [], 'layouts/layout');
    
    }



}