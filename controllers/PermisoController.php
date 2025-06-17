<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Permisos;
use Model\Aplicacion;
use MVC\Router;

class PermisoController extends ActiveRecord
{
    public function index(Router $router)
    {
        // Obtener todas las aplicaciones activas para el select
        $aplicaciones = Aplicacion::all();
        
        $router->render('permisos/index', [
            'aplicaciones' => $aplicaciones
        ], 'layouts/layout');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validar aplicación
        if (empty($_POST['per_aplicacion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una aplicación'
            ]);
            return;
        }

        $_POST['per_nombre_permiso'] = htmlspecialchars($_POST['per_nombre_permiso']);
        $cantidad_nombre = strlen($_POST['per_nombre_permiso']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del permiso debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_nombre > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del permiso no puede exceder 100 caracteres'
            ]);
            return;
        }

        $_POST['per_clave_permiso'] = htmlspecialchars($_POST['per_clave_permiso']);
        $cantidad_clave = strlen($_POST['per_clave_permiso']);

        if ($cantidad_clave < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La clave del permiso debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_clave > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La clave del permiso no puede exceder 50 caracteres'
            ]);
            return;
        }

        $_POST['per_descripcion'] = htmlspecialchars($_POST['per_descripcion']);
        $cantidad_descripcion = strlen($_POST['per_descripcion']);

        if ($cantidad_descripcion > 500) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción no puede exceder 500 caracteres'
            ]);
            return;
        }

        // Verificar que no exista un permiso con la misma clave en la misma aplicación
        $sql = "SELECT per_id FROM permisos WHERE per_clave_permiso = '{$_POST['per_clave_permiso']}' AND per_aplicacion = {$_POST['per_aplicacion']} AND per_situacion = 1";
        $permisoExistente = self::fetchArray($sql);
        if (!empty($permisoExistente)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un permiso con esta clave en la aplicación seleccionada'
            ]);
            return;
        }

        try {
            $data = new Permisos([
                'per_aplicacion' => $_POST['per_aplicacion'],
                'per_nombre_permiso' => ucwords(strtolower($_POST['per_nombre_permiso'])),
                'per_clave_permiso' => strtoupper($_POST['per_clave_permiso']),
                'per_descripcion' => ucfirst(strtolower($_POST['per_descripcion'])),
                'per_fecha' => date('Y-m-d H:i'),
                'per_situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, el permiso ha sido registrado correctamente'
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
            $sql = "SELECT p.*, a.ap_nombre_lg as aplicacion_nombre 
                    FROM permisos p 
                    INNER JOIN aplicacion a ON p.per_aplicacion = a.ap_id 
                    WHERE p.per_situacion = 1 
                    ORDER BY a.ap_nombre_lg ASC, p.per_nombre_permiso ASC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los permisos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['per_id'];

        // Validar aplicación
        if (empty($_POST['per_aplicacion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una aplicación'
            ]);
            return;
        }

        $_POST['per_nombre_permiso'] = htmlspecialchars($_POST['per_nombre_permiso']);
        $cantidad_nombre = strlen($_POST['per_nombre_permiso']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del permiso debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_nombre > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del permiso no puede exceder 100 caracteres'
            ]);
            return;
        }

        $_POST['per_clave_permiso'] = htmlspecialchars($_POST['per_clave_permiso']);
        $cantidad_clave = strlen($_POST['per_clave_permiso']);

        if ($cantidad_clave < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La clave del permiso debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_clave > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La clave del permiso no puede exceder 50 caracteres'
            ]);
            return;
        }

        $_POST['per_descripcion'] = htmlspecialchars($_POST['per_descripcion']);
        $cantidad_descripcion = strlen($_POST['per_descripcion']);

        if ($cantidad_descripcion > 500) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La descripción no puede exceder 500 caracteres'
            ]);
            return;
        }

        // Verificar que no exista otro permiso con la misma clave en la misma aplicación (excluyendo el actual)
        $sql = "SELECT per_id FROM permisos WHERE per_clave_permiso = '{$_POST['per_clave_permiso']}' AND per_aplicacion = {$_POST['per_aplicacion']} AND per_id != $id AND per_situacion = 1";
        $existeClave = self::fetchArray($sql);
        if (!empty($existeClave)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro permiso con esta clave en la aplicación seleccionada'
            ]);
            return;
        }

        try {
            $data = Permisos::find($id);
            $data->sincronizar([
                'per_aplicacion' => $_POST['per_aplicacion'],
                'per_nombre_permiso' => ucwords(strtolower($_POST['per_nombre_permiso'])),
                'per_clave_permiso' => strtoupper($_POST['per_clave_permiso']),
                'per_descripcion' => ucfirst(strtolower($_POST['per_descripcion']))
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del permiso ha sido modificada exitosamente'
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

            $ejecutar = Permisos::EliminarPermisos($id);

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