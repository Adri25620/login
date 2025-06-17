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

    public static function guardarAPI()
    {
        getHeadersApi();

        $_POST['ap_nombre_lg'] = htmlspecialchars($_POST['ap_nombre_lg']);
        $cantidad_nombre_lg = strlen($_POST['ap_nombre_lg']);

        if ($cantidad_nombre_lg < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre largo debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_nombre_lg > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre largo no puede exceder 100 caracteres'
            ]);
            return;
        }

        $_POST['ap_nombre_md'] = htmlspecialchars($_POST['ap_nombre_md']);
        $cantidad_nombre_md = strlen($_POST['ap_nombre_md']);

        if ($cantidad_nombre_md < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre medio debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_nombre_md > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre medio no puede exceder 50 caracteres'
            ]);
            return;
        }

        $_POST['ap_nombre_ct'] = htmlspecialchars($_POST['ap_nombre_ct']);
        $cantidad_nombre_ct = strlen($_POST['ap_nombre_ct']);

        if ($cantidad_nombre_ct < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto es obligatorio'
            ]);
            return;
        }

        if ($cantidad_nombre_ct > 20) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto no puede exceder 20 caracteres'
            ]);
            return;
        }

        // Verificar que no exista una aplicación con el mismo nombre largo
        $sql = "SELECT ap_id FROM aplicacion WHERE ap_nombre_lg = '{$_POST['ap_nombre_lg']}' AND ap_situacion = 1";
        $aplicacionExistente = self::fetchArray($sql);
        if (!empty($aplicacionExistente)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe una aplicación con este nombre largo'
            ]);
            return;
        }

        // Verificar que no exista una aplicación con el mismo nombre corto
        $sql = "SELECT ap_id FROM aplicacion WHERE ap_nombre_ct = '{$_POST['ap_nombre_ct']}' AND ap_situacion = 1";
        $aplicacionExistenteCorto = self::fetchArray($sql);
        if (!empty($aplicacionExistenteCorto)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe una aplicación con este nombre corto'
            ]);
            return;
        }

        try {
            $data = new Aplicacion([
                'ap_nombre_lg' => ucwords(strtolower($_POST['ap_nombre_lg'])),
                'ap_nombre_md' => ucwords(strtolower($_POST['ap_nombre_md'])),
                'ap_nombre_ct' => ucwords(strtolower($_POST['ap_nombre_ct'])),
                'ap_fecha_creacion' => date('Y-m-d H:i'),
                'ap_situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, la aplicación ha sido registrada correctamente'
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
            $sql = "SELECT * FROM aplicacion WHERE ap_situacion = 1 ORDER BY ap_fecha_creacion DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Aplicaciones obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las aplicaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['ap_id'];

        $_POST['ap_nombre_lg'] = htmlspecialchars($_POST['ap_nombre_lg']);
        $cantidad_nombre_lg = strlen($_POST['ap_nombre_lg']);

        if ($cantidad_nombre_lg < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre largo debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_nombre_lg > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre largo no puede exceder 100 caracteres'
            ]);
            return;
        }

        $_POST['ap_nombre_md'] = htmlspecialchars($_POST['ap_nombre_md']);
        $cantidad_nombre_md = strlen($_POST['ap_nombre_md']);

        if ($cantidad_nombre_md < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre medio debe contener al menos 2 caracteres'
            ]);
            return;
        }

        if ($cantidad_nombre_md > 50) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre medio no puede exceder 50 caracteres'
            ]);
            return;
        }

        $_POST['ap_nombre_ct'] = htmlspecialchars($_POST['ap_nombre_ct']);
        $cantidad_nombre_ct = strlen($_POST['ap_nombre_ct']);

        if ($cantidad_nombre_ct < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto es obligatorio'
            ]);
            return;
        }

        if ($cantidad_nombre_ct > 20) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre corto no puede exceder 20 caracteres'
            ]);
            return;
        }

        // Verificar que no exista otra aplicación con el mismo nombre largo (excluyendo la actual)
        $sql = "SELECT ap_id FROM aplicacion WHERE ap_nombre_lg = '{$_POST['ap_nombre_lg']}' AND ap_id != $id AND ap_situacion = 1";
        $existeNombreLargo = self::fetchArray($sql);
        if (!empty($existeNombreLargo)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otra aplicación con este nombre largo'
            ]);
            return;
        }

        // Verificar que no exista otra aplicación con el mismo nombre corto (excluyendo la actual)
        $sql = "SELECT ap_id FROM aplicacion WHERE ap_nombre_ct = '{$_POST['ap_nombre_ct']}' AND ap_id != $id AND ap_situacion = 1";
        $existeNombreCorto = self::fetchArray($sql);
        if (!empty($existeNombreCorto)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otra aplicación con este nombre corto'
            ]);
            return;
        }

        try {
            $data = Aplicacion::find($id);
            $data->sincronizar([
                'ap_nombre_lg' => ucwords(strtolower($_POST['ap_nombre_lg'])),
                'ap_nombre_md' => ucwords(strtolower($_POST['ap_nombre_md'])),
                'ap_nombre_ct' => ucwords(strtolower($_POST['ap_nombre_ct']))
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información de la aplicación ha sido modificada exitosamente'
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

            $ejecutar = Aplicacion::EliminarAplicacion($id);

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