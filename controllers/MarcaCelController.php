<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\MarcaCel;
use MVC\Router;

class MarcaCelController extends ActiveRecord
{

    public function index(Router $router)
    {
        $router->render('marcas/index', [], 'layouts/layout');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

         error_log("Llegó a guardarAPI");
    error_log("POST data: " . print_r($_POST, true));

        try {
   
            if (empty($_POST['mar_nombre'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre de la marca es obligatorio'
                ]);
                return;
            }


            if (strlen($_POST['mar_nombre']) > 50) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre de la marca no puede exceder 50 caracteres'
                ]);
                return;
            }

            if (!empty($_POST['mar_descripcion']) && strlen($_POST['mar_descripcion']) > 200) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción no puede exceder 200 caracteres'
                ]);
                return;
            }

 
            $marcaExistente = MarcaCel::where('mar_nombre', $_POST['mar_nombre']);
            if (!empty($marcaExistente)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe una marca con este nombre'
                ]);
                return;
            }

            $marca = new MarcaCel([
                'mar_nombre' => ucwords(strtolower(trim(htmlspecialchars($_POST['mar_nombre'])))),
                'mar_descripcion' => trim(htmlspecialchars($_POST['mar_descripcion'] ?? '')),
                'mar_fecha_creacion' => date('Y-m-d H:i'),
                'mar_situacion' => '1'
            ]);

            $resultado = $marca->crear();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca registrada exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar la marca'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $sql = "SELECT * FROM marcas_celulares WHERE mar_situacion = 1";
            $marcas = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode($marcas);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar las marcas'
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {

            if (empty($_POST['mar_id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de marca requerido'
                ]);
                return;
            }


            if (empty($_POST['mar_nombre'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre de la marca es obligatorio'
                ]);
                return;
            }


            if (strlen($_POST['mar_nombre']) > 50) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre de la marca no puede exceder 50 caracteres'
                ]);
                return;
            }


            if (!empty($_POST['mar_descripcion']) && strlen($_POST['mar_descripcion']) > 200) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción no puede exceder 200 caracteres'
                ]);
                return;
            }

  
            $sql = "SELECT * FROM marcas_celulares WHERE mar_nombre = '{$_POST['mar_nombre']}' AND mar_situacion = '1' AND mar_id != {$_POST['mar_id']}";
            $marcaExistente = self::fetchArray($sql);

            if (!empty($marcaExistente)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otra marca con este nombre'
                ]);
                return;
            }

            $marca = MarcaCel::find($_POST['mar_id']);
            if (!$marca) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Marca no encontrada'
                ]);
                return;
            }

            $marca->sincronizar([
                'mar_nombre' => ucwords(strtolower(trim(htmlspecialchars($_POST['mar_nombre'])))),
                'mar_descripcion' => trim(htmlspecialchars($_POST['mar_descripcion'] ?? ''))
            ]);

            $resultado = $marca->actualizar();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca modificada exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar la marca'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();

        try {
  
            if (empty($_GET['id'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de marca requerido'
                ]);
                return;
            }

            $id = $_GET['id'];

 
            $marca = MarcaCel::find($id);
            if (!$marca) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Marca no encontrada'
                ]);
                return;
            }

           
            $resultado = MarcaCel::EliminarMarca($id);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca eliminada exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al eliminar la marca'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }
}