<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class LoginController extends ActiveRecord
{
    public static function index(Router $router)
    {
        $router->render('pages/index', [], 'layouts/principal');
    }


  public static function login()
    {
        getHeadersApi();

        try {
            $correo = htmlspecialchars($_POST['us_correo']);
            $password = htmlspecialchars($_POST['us_contrasenia']);

            $queryExisteUser = "SELECT us_id, us_nombres, us_apellidos, us_correo, us_contrasenia FROM usuarios WHERE us_correo = '$correo' AND us_situacion = '1'";

            $ExisteUsuario = ActiveRecord::fetchArray($queryExisteUser);

            if (!empty($ExisteUsuario)) {
                $usuario = $ExisteUsuario[0];
                $passDB = $usuario['us_contrasenia'];

                if (password_verify($password, $passDB)) {
                    session_start();

                    $_SESSION['user'] = $usuario['us_nombres'] . ' ' . $usuario['us_apellidos'];
                    $_SESSION['us_id'] = $usuario['us_id'];
                    $_SESSION['us_nombres'] = $usuario['us_nombres'];
                    $_SESSION['us_apellidos'] = $usuario['us_apellidos'];
                    $_SESSION['us_correo'] = $usuario['us_correo'];
                    $_SESSION['login'] = true;

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario logueado exitosamente',
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingresó es incorrecta',
                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario que intenta loguearse NO EXISTE',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar loguearse',
                'detalle' => $e->getMessage()
            ]);
        }
    }

 public static function logout()
{
    session_start();
    session_destroy();
    header('Location: /proyecto_uno/'); // CON BASE URL
    exit;
}


    public static function inicio(Router $router)
{
    session_start();
    
    // Verificar si está logueado
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: /');
        exit;
    }
    
    // Renderizar TU vista de bienvenida (no dashboard/index)
    $router->render('bienvenida/index', [
        'usuario' => $_SESSION['user'],
        'nombres' => $_SESSION['us_nombres'],
        'apellidos' => $_SESSION['us_apellidos'],
        'correo' => $_SESSION['us_correo']
    ]);
}
}
