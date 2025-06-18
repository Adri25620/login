<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Inventario;
use Model\MarcaCel;
use MVC\Router;

class InventarioController extends ActiveRecord
{
    public function index(Router $router)
    {
        // Obtener datos para los selects
        $marcas = MarcaCel::all();
        
        $router->render('inventario/index', [
            'marcas' => $marcas
        ], 'layouts/layout');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validar modelo
        if (empty($_POST['inv_modelo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe ingresar el modelo del celular'
            ]);
            return;
        }

        // Validar marca
        if (empty($_POST['inv_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        // Validar precio
        if (empty($_POST['inv_precio']) || $_POST['inv_precio'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe ingresar un precio válido'
            ]);
            return;
        }

        // Validar stock
        if (!isset($_POST['inv_stock']) || $_POST['inv_stock'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe ingresar una cantidad de stock válida'
            ]);
            return;
        }

        // Limpiar datos
        $_POST['inv_modelo'] = htmlspecialchars(trim($_POST['inv_modelo']));
        
        // Validar longitud del modelo
        if (strlen($_POST['inv_modelo']) < 3) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El modelo debe contener al menos 3 caracteres'
            ]);
            return;
        }

        if (strlen($_POST['inv_modelo']) > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El modelo no puede exceder 100 caracteres'
            ]);
            return;
        }

        try {
            $data = new Inventario([
                'inv_modelo' => ucwords(strtolower($_POST['inv_modelo'])),
                'inv_marca' => $_POST['inv_marca'],
                'inv_precio' => $_POST['inv_precio'],
                'inv_stock' => $_POST['inv_stock'],
                'inv_situacion' => '1'
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, el producto ha sido registrado correctamente en el inventario'
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
            $data = Inventario::obtenerInventario();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inventario obtenido correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el inventario',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['inv_id'];

        // Validar modelo
        if (empty($_POST['inv_modelo'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe ingresar el modelo del celular'
            ]);
            return;
        }

        // Validar marca
        if (empty($_POST['inv_marca'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        // Validar precio
        if (empty($_POST['inv_precio']) || $_POST['inv_precio'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe ingresar un precio válido'
            ]);
            return;
        }

        // Validar stock
        if (!isset($_POST['inv_stock']) || $_POST['inv_stock'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe ingresar una cantidad de stock válida'
            ]);
            return;
        }

        // Limpiar datos
        $_POST['inv_modelo'] = htmlspecialchars(trim($_POST['inv_modelo']));
        
        // Validar longitud del modelo
        if (strlen($_POST['inv_modelo']) < 3) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El modelo debe contener al menos 3 caracteres'
            ]);
            return;
        }

        if (strlen($_POST['inv_modelo']) > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El modelo no puede exceder 100 caracteres'
            ]);
            return;
        }

        try {
            $data = Inventario::find($id);
            $data->sincronizar([
                'inv_modelo' => ucwords(strtolower($_POST['inv_modelo'])),
                'inv_marca' => $_POST['inv_marca'],
                'inv_precio' => $_POST['inv_precio'],
                'inv_stock' => $_POST['inv_stock']
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del producto ha sido modificada exitosamente'
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

            $ejecutar = Inventario::EliminarInventario($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El producto ha sido eliminado correctamente del inventario'
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

    public static function obtenerDisponibleAPI()
    {
        try {
            $data = Inventario::obtenerInventarioDisponible();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos disponibles obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener productos disponibles',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function obtenerAgotadoAPI()
    {
        try {
            $data = Inventario::obtenerInventarioAgotado();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos agotados obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener productos agotados',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function obtenerBajoStockAPI()
    {
        try {
            $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 5;
            $data = Inventario::obtenerInventarioBajoStock($limite);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos con bajo stock obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener productos con bajo stock',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function actualizarStockAPI()
    {
        getHeadersApi();

        // Validar ID del producto
        if (empty($_POST['inv_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID del producto requerido'
            ]);
            return;
        }

        // Validar nueva cantidad
        if (!isset($_POST['nueva_cantidad']) || $_POST['nueva_cantidad'] < 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe ingresar una cantidad válida'
            ]);
            return;
        }

        try {
            $id = $_POST['inv_id'];
            $nueva_cantidad = $_POST['nueva_cantidad'];

            $ejecutar = Inventario::actualizarStock($id, $nueva_cantidad);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Stock actualizado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar stock',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function obtenerEstadisticasAPI()
    {
        try {
            $data = Inventario::obtenerEstadisticasInventario();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener estadísticas',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}