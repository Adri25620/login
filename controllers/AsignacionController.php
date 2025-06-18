<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Asignacion;
use Model\Usuarios;
use Model\Aplicacion;
use Model\Permisos;
use MVC\Router;

class AsignacionController extends ActiveRecord
{
    public function index(Router $router)
    {
        // Obtener datos para los selects
        $usuarios = Usuarios::all();
        $aplicaciones = Aplicacion::all();
        $permisos = Permisos::all();
        
        $router->render('asignacion/index', [
            'usuarios' => $usuarios,
            'aplicaciones' => $aplicaciones,
            'permisos' => $permisos
        ], 'layouts/layout');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validar usuario
        if (empty($_POST['asig_usuario'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario'
            ]);
            return;
        }

        // Validar aplicación
        if (empty($_POST['asig_aplicacion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una aplicación'
            ]);
            return;
        }

        // Validar permiso
        if (empty($_POST['asig_permisos'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un permiso'
            ]);
            return;
        }

        // Validar usuario asignador
        if (empty($_POST['asig_usuario_asignador'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario asignador'
            ]);
            return;
        }

        $_POST['asig_motivo'] = htmlspecialchars($_POST['asig_motivo']);
        $cantidad_motivo = strlen($_POST['asig_motivo']);

        if ($cantidad_motivo < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo debe contener al menos 5 caracteres'
            ]);
            return;
        }

        if ($cantidad_motivo > 250) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo no puede exceder 250 caracteres'
            ]);
            return;
        }

        // Verificar que no exista ya esta asignación
        $sql = "SELECT asig_id FROM asig_permisos WHERE asig_usuario = {$_POST['asig_usuario']} AND asig_aplicacion = {$_POST['asig_aplicacion']} AND asig_permisos = {$_POST['asig_permisos']} AND asig_situacion = '1'";
        $asignacionExistente = self::fetchArray($sql);
        if (!empty($asignacionExistente)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este usuario ya tiene asignado este permiso en esta aplicación'
            ]);
            return;
        }

        try {
            $data = new Asignacion([
                'asig_usuario' => $_POST['asig_usuario'],
                'asig_aplicacion' => $_POST['asig_aplicacion'],
                'asig_permisos' => $_POST['asig_permisos'],
                'asig_fecha' => date('Y-m-d H:i'),
                'asig_quitar_fechaPermiso' => date('Y-m-d H:i'),
                'asig_usuario_asignador' => $_POST['asig_usuario_asignador'],
                'asig_motivo' => ucfirst(strtolower($_POST['asig_motivo'])),
                'asig_situacion' => '1'
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, la asignación ha sido registrada correctamente'
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
            $data = Asignacion::obtenerAsignaciones();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las asignaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['asig_id'];

        // Validar usuario
        if (empty($_POST['asig_usuario'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario'
            ]);
            return;
        }

        // Validar aplicación
        if (empty($_POST['asig_aplicacion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una aplicación'
            ]);
            return;
        }

        // Validar permiso
        if (empty($_POST['asig_permisos'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un permiso'
            ]);
            return;
        }

        // Validar usuario asignador
        if (empty($_POST['asig_usuario_asignador'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario asignador'
            ]);
            return;
        }

        $_POST['asig_motivo'] = htmlspecialchars($_POST['asig_motivo']);
        $cantidad_motivo = strlen($_POST['asig_motivo']);

        if ($cantidad_motivo < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo debe contener al menos 5 caracteres'
            ]);
            return;
        }

        if ($cantidad_motivo > 250) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo no puede exceder 250 caracteres'
            ]);
            return;
        }

        // Verificar que no exista otra asignación igual (excluyendo la actual)
        $sql = "SELECT asig_id FROM asig_permisos WHERE asig_usuario = {$_POST['asig_usuario']} AND asig_aplicacion = {$_POST['asig_aplicacion']} AND asig_permisos = {$_POST['asig_permisos']} AND asig_id != $id AND asig_situacion = '1'";
        $existeAsignacion = self::fetchArray($sql);
        if (!empty($existeAsignacion)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otra asignación igual para este usuario'
            ]);
            return;
        }

        try {
            $data = Asignacion::find($id);
            $data->sincronizar([
                'asig_usuario' => $_POST['asig_usuario'],
                'asig_aplicacion' => $_POST['asig_aplicacion'],
                'asig_permisos' => $_POST['asig_permisos'],
                'asig_usuario_asignador' => $_POST['asig_usuario_asignador'],
                'asig_motivo' => ucfirst(strtolower($_POST['asig_motivo']))
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información de la asignación ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function eliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $ejecutar = Asignacion::EliminarAsignacion($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El registro ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function obtenerPermisosPorAplicacionAPI()
    {
        try {
            $aplicacion_id = filter_var($_GET['aplicacion_id'], FILTER_SANITIZE_NUMBER_INT);

            if (!$aplicacion_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de aplicación requerido'
                ]);
                return;
            }

            $permisos = Permisos::obtenerPermisosPorAplicacion($aplicacion_id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permisos obtenidos correctamente',
                'data' => $permisos
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener permisos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }


    public static function finPermisoAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            // Actualizar la fecha de quitar permiso y cambiar situación
            $sql = "UPDATE asig_permisos SET asig_quitar_fechaPermiso = CURRENT, asig_situacion = '0' WHERE asig_id = $id";
            $ejecutar = self::SQL($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Permiso finalizado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al finalizar permiso',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}