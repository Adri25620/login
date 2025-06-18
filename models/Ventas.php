<?php

namespace Model;

class Ventas extends ActiveRecord
{

    public static $tabla = 'ventas';
    public static $columnasDB = [
        'ven_cliente',
        'ven_total',
        'ven_fecha',
        'ven_situacion'
    ];
    public static $idTabla = 'ven_id';

    public $ven_id;
    public $ven_cliente;
    public $ven_total;
    public $ven_fecha;
    public $ven_situacion;

    public function __construct($args = []){
        $this->ven_id = $args['ven_id'] ?? null;
        $this->ven_cliente = $args['ven_cliente'] ?? '';
        $this->ven_total = $args['ven_total'] ?? 0.00;
        $this->ven_fecha = $args['ven_fecha'] ?? date('Y-m-d H:i');
        $this->ven_situacion = $args['ven_situacion'] ?? 1;   
    }

    // Eliminar venta (cambiar situación a inactivo)
    public static function eliminarVenta($id){
        $sql = "UPDATE ventas SET ven_situacion = 0 WHERE ven_id = $id";
        return self::SQL($sql);
    }

    // Obtener todas las ventas con información del cliente
    public static function obtenerVentasConCliente(){
        $sql = "SELECT v.ven_id, v.ven_total, v.ven_fecha, 
                       c.cli_nombre, c.cli_apellidos, c.cli_telefono,
                       (SELECT COUNT(*) FROM detalle_ventas dv WHERE dv.dv_venta = v.ven_id AND dv.dv_situacion = '1') as total_celulares
                FROM ventas v 
                INNER JOIN clientes c ON v.ven_cliente = c.cli_id 
                WHERE v.ven_situacion = 1 
                ORDER BY v.ven_fecha DESC";
        return self::fetchArray($sql);
    }

    // Obtener una venta específica con información del cliente
    public static function obtenerVentaCompleta($venta_id){
        $sql = "SELECT v.*, c.cli_nombre, c.cli_apellidos, c.cli_telefono
                FROM ventas v 
                INNER JOIN clientes c ON v.ven_cliente = c.cli_id 
                WHERE v.ven_id = $venta_id AND v.ven_situacion = 1";
        return self::fetchArray($sql);
    }

    // Guardar venta completa con detalles
    public static function guardarVentaCompleta($cliente_id, $celulares_seleccionados) {
        try {
            if (empty($celulares_seleccionados)) {
                throw new \Exception("No se han seleccionado celulares");
            }

            // Verificar disponibilidad de todos los celulares
            foreach($celulares_seleccionados as $celular) {
                if (!DetalleVentas::verificarDisponibilidad($celular['id'], $celular['cantidad'])) {
                    throw new \Exception("Stock insuficiente para el celular ID: " . $celular['id']);
                }
            }

            // Calcular total de la venta
            $total_venta = 0;
            foreach($celulares_seleccionados as $celular) {
                $total_venta += ($celular['cantidad'] * $celular['precio']);
            }

            // Crear la venta
            $venta = new self([
                'ven_cliente' => $cliente_id,
                'ven_total' => $total_venta,
                'ven_fecha' => date('Y-m-d H:i'),
                'ven_situacion' => 1
            ]);

            $resultado = $venta->crear();
            $venta_id = $resultado['id'];

            // Guardar detalles de la venta
            foreach($celulares_seleccionados as $celular) {
                $precio_total = $celular['cantidad'] * $celular['precio'];
                
                $detalle = new DetalleVentas([
                    'dv_venta' => $venta_id,
                    'dv_producto' => $celular['id'],
                    'dv_descripcion' => $celular['descripcion'],
                    'dv_cantidad' => $celular['cantidad'],
                    'dv_precio' => $celular['precio'],
                    'dv_precio_total' => $precio_total,
                    'dv_situacion' => '1'
                ]);

                $detalle->crear();

                // Actualizar inventario
                DetalleVentas::actualizarInventario($celular['id'], $celular['cantidad']);
            }

            return [
                'success' => true,
                'venta_id' => $venta_id,
                'mensaje' => 'Venta guardada exitosamente'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    // Modificar venta completa
    public static function modificarVentaCompleta($venta_id, $cliente_id, $celulares_seleccionados) {
        try {
            // Obtener detalles actuales para restaurar inventario
            $detalles_actuales = DetalleVentas::obtenerDetallesVenta($venta_id);
            
            // Restaurar inventario de los celulares actuales
            foreach($detalles_actuales as $detalle) {
                DetalleVentas::restaurarInventario($detalle['dv_producto'], $detalle['dv_cantidad']);
            }

            // Eliminar detalles actuales
            DetalleVentas::eliminarDetallesVenta($venta_id);

            // Verificar disponibilidad de los nuevos celulares
            foreach($celulares_seleccionados as $celular) {
                if (!DetalleVentas::verificarDisponibilidad($celular['id'], $celular['cantidad'])) {
                    throw new \Exception("Stock insuficiente para el celular ID: " . $celular['id']);
                }
            }

            // Calcular nuevo total
            $total_venta = 0;
            foreach($celulares_seleccionados as $celular) {
                $total_venta += ($celular['cantidad'] * $celular['precio']);
            }

            // Actualizar la venta
            $venta = self::find($venta_id);
            $venta->sincronizar([
                'ven_cliente' => $cliente_id,
                'ven_total' => $total_venta,
                'ven_situacion' => 1
            ]);
            $venta->actualizar();

            // Guardar nuevos detalles
            foreach($celulares_seleccionados as $celular) {
                $precio_total = $celular['cantidad'] * $celular['precio'];
                
                $detalle = new DetalleVentas([
                    'dv_venta' => $venta_id,
                    'dv_producto' => $celular['id'],
                    'dv_descripcion' => $celular['descripcion'],
                    'dv_cantidad' => $celular['cantidad'],
                    'dv_precio' => $celular['precio'],
                    'dv_precio_total' => $precio_total,
                    'dv_situacion' => '1'
                ]);

                $detalle->crear();

                // Actualizar inventario
                DetalleVentas::actualizarInventario($celular['id'], $celular['cantidad']);
            }

            return [
                'success' => true,
                'mensaje' => 'Venta modificada exitosamente'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    // Eliminar venta completa
    public static function eliminarVentaCompleta($venta_id) {
        try {
            // Obtener detalles para restaurar inventario
            $detalles = DetalleVentas::obtenerDetallesVenta($venta_id);
            
            // Restaurar inventario
            foreach($detalles as $detalle) {
                DetalleVentas::restaurarInventario($detalle['dv_producto'], $detalle['dv_cantidad']);
            }

            // Eliminar detalles
            DetalleVentas::eliminarDetallesVenta($venta_id);

            // Eliminar venta
            self::eliminarVenta($venta_id);

            return [
                'success' => true,
                'mensaje' => 'Venta eliminada exitosamente'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    // Obtener ventas por cliente
    public static function obtenerVentasPorCliente($cliente_id) {
        $sql = "SELECT v.*, c.cli_nombre, c.cli_apellidos
                FROM ventas v 
                INNER JOIN clientes c ON v.ven_cliente = c.cli_id 
                WHERE v.ven_cliente = $cliente_id AND v.ven_situacion = 1 
                ORDER BY v.ven_fecha DESC";
        return self::fetchArray($sql);
    }

    // Obtener ventas por rango de fechas
    public static function obtenerVentasPorFecha($fecha_inicio, $fecha_fin) {
        $sql = "SELECT v.*, c.cli_nombre, c.cli_apellidos
                FROM ventas v 
                INNER JOIN clientes c ON v.ven_cliente = c.cli_id 
                WHERE DATE(v.ven_fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' 
                AND v.ven_situacion = 1 
                ORDER BY v.ven_fecha DESC";
        return self::fetchArray($sql);
    }

    // Obtener total de ventas del día
    public static function obtenerVentasDelDia() {
        $sql = "SELECT COUNT(*) as total_ventas, SUM(ven_total) as total_ingresos
                FROM ventas 
                WHERE DATE(ven_fecha) = CURDATE() AND ven_situacion = 1";
        return self::fetchArray($sql);
    }

    // Obtener últimas ventas
    public static function obtenerUltimasVentas($limite = 10) {
        $sql = "SELECT v.ven_id, v.ven_total, v.ven_fecha, 
                       c.cli_nombre, c.cli_apellidos
                FROM ventas v 
                INNER JOIN clientes c ON v.ven_cliente = c.cli_id 
                WHERE v.ven_situacion = 1 
                ORDER BY v.ven_fecha DESC 
                LIMIT $limite";
        return self::fetchArray($sql);
    }
}