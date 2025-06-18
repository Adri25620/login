<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Ventas;
use Model\DetalleVentas;
use Model\Cliente;
use Model\Inventario;
use MVC\Router;

class VentaController extends ActiveRecord
{

    public function index(Router $router)
    {
        $clientes = Cliente::all();
        $inventario = Inventario::obtenerDisponibles(); // Solo celulares disponibles
        
        $router->render('ventas/index', [
            'clientes' => $clientes,
            'inventario' => $inventario
        ]);
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            if (empty($_POST['ven_cliente'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un cliente'
                ]);
                return;
            }

            if (empty($_POST['celulares_seleccionados'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar al menos un celular'
                ]);
                return;
            }

            $celulares_seleccionados = json_decode($_POST['celulares_seleccionados'], true);

            if (empty($celulares_seleccionados)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se han seleccionado celulares válidos'
                ]);
                return;
            }

            // Validar datos de cada celular
            foreach ($celulares_seleccionados as $celular) {
                if (!isset($celular['id']) || !isset($celular['cantidad']) || !isset($celular['precio']) || !isset($celular['descripcion'])) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Datos de celulares incompletos'
                    ]);
                    return;
                }

                if ($celular['cantidad'] <= 0) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La cantidad debe ser mayor a 0'
                    ]);
                    return;
                }

                // Verificar disponibilidad en inventario
                if (!DetalleVentas::verificarDisponibilidad($celular['id'], $celular['cantidad'])) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Stock insuficiente para el celular seleccionado'
                    ]);
                    return;
                }
            }

            $resultado = Ventas::guardarVentaCompleta($_POST['ven_cliente'], $celulares_seleccionados);

            if ($resultado['success']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Venta registrada exitosamente',
                    'venta_id' => $resultado['venta_id']
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => $resultado['mensaje']
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la venta',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $ventas = Ventas::obtenerVentasConCliente();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Ventas obtenidas satisfactoriamente',
                'data' => $ventas
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las ventas',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarDetalleAPI()
    {
        try {
            $venta_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            if (!$venta_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de venta inválido'
                ]);
                return;
            }

            $venta = Ventas::obtenerVentaCompleta($venta_id);
            $detalles = DetalleVentas::obtenerDetallesCompletos($venta_id);

            if (empty($venta)) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Venta no encontrada'
                ]);
                return;
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Detalles de venta obtenidos exitosamente',
                'venta' => $venta[0],
                'detalles' => $detalles
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los detalles de la venta',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $venta_id = $_POST['ven_id'];

            if (empty($venta_id)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de venta requerido'
                ]);
                return;
            }

            if (empty($_POST['ven_cliente'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un cliente'
                ]);
                return;
            }

            if (empty($_POST['celulares_seleccionados'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar al menos un celular'
                ]);
                return;
            }

            $celulares_seleccionados = json_decode($_POST['celulares_seleccionados'], true);

            if (empty($celulares_seleccionados)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se han seleccionado celulares válidos'
                ]);
                return;
            }

            // Validar datos de cada celular
            foreach ($celulares_seleccionados as $celular) {
                if (!isset($celular['id']) || !isset($celular['cantidad']) || !isset($celular['precio']) || !isset($celular['descripcion'])) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Datos de celulares incompletos'
                    ]);
                    return;
                }

                if ($celular['cantidad'] <= 0) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La cantidad debe ser mayor a 0'
                    ]);
                    return;
                }
            }

            $resultado = Ventas::modificarVentaCompleta($venta_id, $_POST['ven_cliente'], $celulares_seleccionados);

            if ($resultado['success']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Venta modificada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => $resultado['mensaje']
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la venta',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function eliminarAPI()
    {
        try {
            $venta_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            if (!$venta_id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de venta inválido'
                ]);
                return;
            }

            $resultado = Ventas::eliminarVentaCompleta($venta_id);

            if ($resultado['success']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Venta eliminada exitosamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => $resultado['mensaje']
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la venta',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // Método adicional para obtener inventario disponible por AJAX
    public static function obtenerInventarioAPI()
    {
        try {
            $inventario = Inventario::obtenerDisponibles();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inventario obtenido exitosamente',
                'data' => $inventario
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el inventario',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    // Método para verificar disponibilidad de un celular específico
    public static function verificarDisponibilidadAPI()
    {
        try {
            $celular_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $cantidad = filter_var($_GET['cantidad'], FILTER_SANITIZE_NUMBER_INT);

            if (!$celular_id || !$cantidad) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Parámetros inválidos'
                ]);
                return;
            }

            $disponible = DetalleVentas::verificarDisponibilidad($celular_id, $cantidad);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Verificación completada',
                'disponible' => $disponible
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al verificar disponibilidad',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}