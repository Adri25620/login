<?php

namespace Model;

class DetalleVentas extends ActiveRecord
{

    public static $tabla = 'detalle_ventas';
    public static $columnasDB = [
        'dv_venta',
        'dv_producto',
        'dv_descripcion',
        'dv_cantidad',
        'dv_precio',
        'dv_precio_total',
        'dv_situacion'
    ];
    public static $idTabla = 'dv_id';

    public $dv_id;
    public $dv_venta;
    public $dv_producto;
    public $dv_descripcion;
    public $dv_cantidad;
    public $dv_precio;
    public $dv_precio_total;
    public $dv_situacion;

    public function __construct($args = []){
        $this->dv_id = $args['dv_id'] ?? null;
        $this->dv_venta = $args['dv_venta'] ?? '';
        $this->dv_producto = $args['dv_producto'] ?? '';
        $this->dv_descripcion = $args['dv_descripcion'] ?? '';
        $this->dv_cantidad = $args['dv_cantidad'] ?? 1;
        $this->dv_precio = $args['dv_precio'] ?? 0.00;
        $this->dv_precio_total = $args['dv_precio_total'] ?? 0.00;
        $this->dv_situacion = $args['dv_situacion'] ?? '1';   
    }

    // Obtener todos los detalles de una venta específica
    public static function obtenerDetallesVenta($venta_id){
        $sql = "SELECT d.*, i.inv_modelo, i.inv_marca, i.inv_color
                FROM detalle_ventas d 
                INNER JOIN inventario_celulares i ON d.dv_producto = i.inv_id 
                WHERE d.dv_venta = $venta_id AND d.dv_situacion = '1' 
                ORDER BY i.inv_marca, i.inv_modelo";
        return self::fetchArray($sql);
    }

    // Eliminar todos los detalles de una venta (cambiar situación a inactivo)
    public static function eliminarDetallesVenta($venta_id){
        $sql = "UPDATE detalle_ventas SET dv_situacion = '0' WHERE dv_venta = $venta_id";
        return self::SQL($sql);
    }

    // Verificar si un producto ya está en la venta
    public static function productoEnVenta($venta_id, $producto_id){
        $sql = "SELECT * FROM detalle_ventas 
                WHERE dv_venta = $venta_id AND dv_producto = $producto_id AND dv_situacion = '1'";
        $resultado = self::fetchArray($sql);
        return !empty($resultado);
    }

    // Actualizar cantidad y precio total de un detalle específico
    public static function actualizarCantidadDetalle($venta_id, $producto_id, $nueva_cantidad, $nuevo_precio_total){
        $sql = "UPDATE detalle_ventas 
                SET dv_cantidad = $nueva_cantidad, dv_precio_total = $nuevo_precio_total 
                WHERE dv_venta = $venta_id AND dv_producto = $producto_id";
        return self::SQL($sql);
    }

    // Verificar disponibilidad en inventario
    public static function verificarDisponibilidad($producto_id, $cantidad_requerida){
        $sql = "SELECT inv_cantidad FROM inventario_celulares WHERE inv_id = $producto_id";
        $resultado = self::fetchArray($sql);
        
        if(!empty($resultado)){
            return $resultado[0]['inv_cantidad'] >= $cantidad_requerida;
        }
        return false;
    }

    // Actualizar inventario después de la venta
    public static function actualizarInventario($producto_id, $cantidad_vendida){
        $sql = "UPDATE inventario_celulares SET inv_cantidad = inv_cantidad - $cantidad_vendida WHERE inv_id = $producto_id";
        return self::SQL($sql);
    }

    // Restaurar inventario (en caso de cancelación o devolución)
    public static function restaurarInventario($producto_id, $cantidad_a_restaurar){
        $sql = "UPDATE inventario_celulares SET inv_cantidad = inv_cantidad + $cantidad_a_restaurar WHERE inv_id = $producto_id";
        return self::SQL($sql);
    }

    // Obtener el total de una venta
    public static function obtenerTotalVenta($venta_id){
        $sql = "SELECT SUM(dv_precio_total) as total 
                FROM detalle_ventas 
                WHERE dv_venta = $venta_id AND dv_situacion = '1'";
        $resultado = self::fetchArray($sql);
        return $resultado[0]['total'] ?? 0.00;
    }

    // Obtener detalles con información completa del producto
    public static function obtenerDetallesCompletos($venta_id){
        $sql = "SELECT d.*, i.inv_modelo, i.inv_marca, i.inv_color, i.inv_imei, i.inv_precio_compra, i.inv_precio_venta
                FROM detalle_ventas d 
                INNER JOIN inventario_celulares i ON d.dv_producto = i.inv_id 
                WHERE d.dv_venta = $venta_id AND d.dv_situacion = '1' 
                ORDER BY d.dv_id";
        return self::fetchArray($sql);
    }
}