<?php

namespace Controllers;

use Exception;
use Model\Cliente;
use Model\ActiveRecord;
use MVC\Router;

class ClienteController extends ActiveRecord
{

    public function index(Router $router)
    {
        $router->render('clientes/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $_POST['cli_nombres'] = htmlspecialchars($_POST['cli_nombres']);
        $cantidad_nombres = strlen($_POST['cli_nombres']);

        if ($cantidad_nombres < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de caracteres que debe contener el nombre debe ser mayor a dos'
            ]);
            return;
        }

        $_POST['cli_apellidos'] = htmlspecialchars($_POST['cli_apellidos']);
        $cantidad_apellidos = strlen($_POST['cli_apellidos']);

        if ($cantidad_apellidos < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de caracteres que debe contener el apellido debe ser mayor a dos'
            ]);
            return;
        }

        // Validar teléfono si se proporciona
        if (!empty($_POST['cli_telefono'])) {
            $_POST['cli_telefono'] = filter_var($_POST['cli_telefono'], FILTER_VALIDATE_INT);
            if (strlen($_POST['cli_telefono']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La cantidad de dígitos de teléfono debe ser igual a 8'
                ]);
                return;
            }
        }

        // Validar NIT si se proporciona
        if (!empty($_POST['cli_nit'])) {
            $_POST['cli_nit'] = filter_var($_POST['cli_nit'], FILTER_SANITIZE_STRING);
        }

        // Validar correo si se proporciona
        if (!empty($_POST['cli_correo'])) {
            $_POST['cli_correo'] = filter_var($_POST['cli_correo'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($_POST['cli_correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico ingresado es inválido'
                ]);
                return;
            }
        }

        $_POST['cli_direccion'] = htmlspecialchars($_POST['cli_direccion']);

        try {
            $data = new Cliente([
                'cli_nombres' => ucwords(strtolower($_POST['cli_nombres'])),
                'cli_apellidos' => ucwords(strtolower($_POST['cli_apellidos'])),
                'cli_nit' => $_POST['cli_nit'] ?? '',
                'cli_telefono' => $_POST['cli_telefono'] ?? '',
                'cli_correo' => $_POST['cli_correo'] ?? '',
                'cli_direccion' => $_POST['cli_direccion'],
                'cli_situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, el cliente ha sido registrado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $sql = "SELECT * FROM clientes WHERE cli_situacion = 1";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los clientes',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['cli_id'];
        
        $_POST['cli_nombres'] = htmlspecialchars($_POST['cli_nombres']);
        $cantidad_nombres = strlen($_POST['cli_nombres']);

        if ($cantidad_nombres < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de caracteres que debe contener el nombre debe ser mayor a dos'
            ]);
            return;
        }

        $_POST['cli_apellidos'] = htmlspecialchars($_POST['cli_apellidos']);
        $cantidad_apellidos = strlen($_POST['cli_apellidos']);

        if ($cantidad_apellidos < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de caracteres que debe contener el apellido debe ser mayor a dos'
            ]);
            return;
        }

        // Validar teléfono si se proporciona
        if (!empty($_POST['cli_telefono'])) {
            $_POST['cli_telefono'] = filter_var($_POST['cli_telefono'], FILTER_VALIDATE_INT);
            if (strlen($_POST['cli_telefono']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La cantidad de dígitos de teléfono debe ser igual a 8'
                ]);
                return;
            }
        }

        // Validar NIT si se proporciona
        if (!empty($_POST['cli_nit'])) {
            $_POST['cli_nit'] = filter_var($_POST['cli_nit'], FILTER_SANITIZE_STRING);
        }

        // Validar correo si se proporciona
        if (!empty($_POST['cli_correo'])) {
            $_POST['cli_correo'] = filter_var($_POST['cli_correo'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($_POST['cli_correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico ingresado es inválido'
                ]);
                return;
            }
        }

        $_POST['cli_direccion'] = htmlspecialchars($_POST['cli_direccion']);

        try {
            $data = Cliente::find($id);
            $data->sincronizar([
                'cli_nombres' => ucwords(strtolower($_POST['cli_nombres'])),
                'cli_apellidos' => ucwords(strtolower($_POST['cli_apellidos'])),
                'cli_nit' => $_POST['cli_nit'] ?? '',
                'cli_telefono' => $_POST['cli_telefono'] ?? '',
                'cli_correo' => $_POST['cli_correo'] ?? '',
                'cli_direccion' => $_POST['cli_direccion'],
                'cli_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del cliente ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $ejecutar = Cliente::EliminarCliente($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El registro ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al Eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}