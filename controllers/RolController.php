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

    public static function guardarAPI()
    {
        getHeadersApi();

        $_POST['rol_nombre'] = htmlspecialchars($_POST['rol_nombre']);
        $cantidad_nombre = strlen($_POST['rol_nombre']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del rol debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_nombre > 75) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del rol no puede exceder 75 caracteres'
            ]);
            return;
        }

        $_POST['rol_nombre_ct'] = htmlspecialchars($_POST['rol_nombre_ct']);
        $cantidad_nombre_ct = strlen($_POST['rol_nombre_ct']);

        if ($cantidad_nombre_ct < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto es obligatorio'
            ]);
            return;
        }

        if ($cantidad_nombre_ct > 25) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto no puede exceder 25 caracteres'
            ]);
            return;
        }

        // Verificar que no exista un rol con el mismo nombre
        $sql = "SELECT rol_id FROM rol WHERE rol_nombre = '{$_POST['rol_nombre']}' AND rol_situacion = 1";
        $rolExistente = self::fetchArray($sql);
        if (!empty($rolExistente)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un rol con este nombre'
            ]);
            return;
        }

        // Verificar que no exista un rol con el mismo nombre corto
        $sql = "SELECT rol_id FROM rol WHERE rol_nombre_ct = '{$_POST['rol_nombre_ct']}' AND rol_situacion = 1";
        $rolExistenteCorto = self::fetchArray($sql);
        if (!empty($rolExistenteCorto)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un rol con este nombre corto'
            ]);
            return;
        }

        try {
            $data = new Roles([
                'rol_nombre' => ucwords(strtolower($_POST['rol_nombre'])),
                'rol_nombre_ct' => strtoupper($_POST['rol_nombre_ct']),
                'rol_situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, el rol ha sido registrado correctamente'
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
            $sql = "SELECT * FROM rol WHERE rol_situacion = 1 ORDER BY rol_nombre ASC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Roles obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los roles',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['rol_id'];

        $_POST['rol_nombre'] = htmlspecialchars($_POST['rol_nombre']);
        $cantidad_nombre = strlen($_POST['rol_nombre']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del rol debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_nombre > 75) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del rol no puede exceder 75 caracteres'
            ]);
            return;
        }

        $_POST['rol_nombre_ct'] = htmlspecialchars($_POST['rol_nombre_ct']);
        $cantidad_nombre_ct = strlen($_POST['rol_nombre_ct']);

        if ($cantidad_nombre_ct < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto es obligatorio'
            ]);
            return;
        }

        if ($cantidad_nombre_ct > 25) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto no puede exceder 25 caracteres'
            ]);
            return;
        }

        // Verificar que no exista otro rol con el mismo nombre (excluyendo el actual)
        $sql = "SELECT rol_id FROM rol WHERE rol_nombre = '{$_POST['rol_nombre']}' AND rol_id != $id AND rol_situacion = 1";
        $existeNombre = self::fetchArray($sql);
        if (!empty($existeNombre)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro rol con este nombre'
            ]);
            return;
        }

        // Verificar que no exista otro rol con el mismo nombre corto (excluyendo el actual)
        $sql = "SELECT rol_id FROM rol WHERE rol_nombre_ct = '{$_POST['rol_nombre_ct']}' AND rol_id != $id AND rol_situacion = 1";
        $existeNombreCorto = self::fetchArray($sql);
        if (!empty($existeNombreCorto)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro rol con este nombre corto'
            ]);
            return;
        }

        try {
            $data = Roles::find($id);
            $data->sincronizar([
                'rol_nombre' => ucwords(strtolower($_POST['rol_nombre'])),
                'rol_nombre_ct' => strtoupper($_POST['rol_nombre_ct'])
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del rol ha sido modificada exitosamente'
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

            $ejecutar = Roles::EliminarRol($id);

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