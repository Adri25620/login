<?php

namespace Model;

use Model\ActiveRecord;

class Inventario extends ActiveRecord
{

    public static $tabla = 'inventario_celulares';
    public static $columnasDB = [
        'inv_modelo',
        'inv_marca',
        'inv_precio',
        'inv_stock',
        'inv_estado',
        'inv_situacion'
    ];
    public static $idTabla = 'inv_id';

    public $inv_id;
    public $inv_modelo;
    public $inv_marca;
    public $inv_precio;
    public $inv_stock;
    public $inv_estado;
    public $inv_situacion;

    public function __construct($args = [])
    {
        $this->inv_id = $args['inv_id'] ?? null;
        $this->inv_modelo = $args['inv_modelo'] ?? '';
        $this->inv_marca = $args['inv_marca'] ?? '';
        $this->inv_precio = $args['inv_precio'] ?? 0.00;
        $this->inv_stock = $args['inv_stock'] ?? 0;
        $this->inv_estado = $args['inv_estado'] ?? '';
        $this->inv_situacion = $args['inv_situacion'] ?? 1;
    }

    // Método para establecer el estado automáticamente basado en el stock
    public function establecerEstadoPorStock()
    {
        if ($this->inv_stock > 0) {
            $this->inv_estado = 'disponible';
        } else {
            $this->inv_estado = 'no disponible';
        }
    }

    // método crear para establecer estado automáticamente
    public function crear()
    {
        $this->establecerEstadoPorStock();
        return parent::crear();
    }

    // método actualizar para establecer estado automáticamente
    public function actualizar()
    {
        $this->establecerEstadoPorStock();
        return parent::actualizar();
    }

    public static function EliminarInventario($id)
    {
        $sql = "UPDATE inventario_celulares SET inv_situacion = '0' WHERE inv_id = $id";
        return self::SQL($sql);
    }

    public static function obtenerInventario()
    {
        $sql = "SELECT 
            i.inv_id,
            i.inv_modelo,
            i.inv_marca,
            i.inv_precio,
            i.inv_stock,
            i.inv_estado,
            i.inv_situacion,
            m.mar_nombre as marca_nombre
          FROM inventario_celulares i
          INNER JOIN marcas_celulares m ON i.inv_marca = m.mar_id
          WHERE i.inv_situacion = '1'
          ORDER BY i.inv_id DESC";

        $resultado = self::fetchArray($sql);

        // Corregir el estado basado en el stock después de obtener los datos
        if ($resultado) {
            foreach ($resultado as &$item) {
                if ($item['inv_stock'] > 0) {
                    $item['inv_estado'] = 'disponible';
                } else {
                    $item['inv_estado'] = 'no disponible';
                }
            }
        }

        return $resultado ?: [];
    }

    public static function obtenerInventarioPorId($id)
    {
        $sql = "SELECT 
                i.inv_id,
                i.inv_modelo,
                i.inv_marca,
                i.inv_precio,
                i.inv_stock,
                CASE 
                    WHEN i.inv_stock > 0 THEN 'disponible'
                    ELSE 'no disponible'
                END as inv_estado,
                m.mar_nombre as marca_nombre
              FROM inventario_celulares i
              INNER JOIN marcas_celulares m ON i.inv_marca = m.mar_id
              WHERE i.inv_id = $id AND i.inv_situacion = '1'";

        $resultado = self::fetchArray($sql);

        return $resultado ? $resultado[0] : null;
    }

    public static function obtenerInventarioPorMarca($marca_id)
    {
        $sql = "SELECT 
                i.inv_id,
                i.inv_modelo,
                i.inv_precio,
                i.inv_stock,
                CASE 
                    WHEN i.inv_stock > 0 THEN 'disponible'
                    ELSE 'no disponible'
                END as inv_estado
              FROM inventario_celulares i
              WHERE i.inv_marca = $marca_id 
              AND i.inv_situacion = '1'
              ORDER BY i.inv_modelo ASC";

        $resultado = self::fetchArray($sql);

        return $resultado ?: [];
    }

    public static function verificarStockDisponible($id, $cantidad)
    {
        $sql = "SELECT inv_stock 
              FROM inventario_celulares 
              WHERE inv_id = $id 
              AND inv_situacion = '1'";

        $resultado = self::fetchArray($sql);

        if ($resultado && $resultado[0]['inv_stock'] >= $cantidad) {
            return true;
        }

        return false;
    }

    public static function actualizarStock($id, $nueva_cantidad)
    {
        $nuevo_estado = $nueva_cantidad > 0 ? 'disponible' : 'no disponible';

        $sql = "UPDATE inventario_celulares 
              SET inv_stock = $nueva_cantidad,
                  inv_estado = '$nuevo_estado'
              WHERE inv_id = $id 
              AND inv_situacion = '1'";

        return self::SQL($sql);
    }

    public static function reducirStock($id, $cantidad)
    {
        $sql = "UPDATE inventario_celulares 
              SET inv_stock = inv_stock - $cantidad,
                  inv_estado = CASE 
                    WHEN (inv_stock - $cantidad) > 0 THEN 'disponible'
                    ELSE 'no disponible'
                  END
              WHERE inv_id = $id 
              AND inv_situacion = '1'";

        return self::SQL($sql);
    }

    public static function aumentarStock($id, $cantidad)
    {
        $sql = "UPDATE inventario_celulares 
              SET inv_stock = inv_stock + $cantidad,
                  inv_estado = CASE 
                    WHEN (inv_stock + $cantidad) > 0 THEN 'disponible'
                    ELSE 'no disponible'
                  END
              WHERE inv_id = $id 
              AND inv_situacion = '1'";

        return self::SQL($sql);
    }

    public static function obtenerInventarioBajoStock($limite = 5)
    {
        $sql = "SELECT 
                i.inv_id,
                i.inv_modelo,
                i.inv_stock,
                CASE 
                    WHEN i.inv_stock > 0 THEN 'disponible'
                    ELSE 'no disponible'
                END as inv_estado,
                m.mar_nombre as marca_nombre
              FROM inventario_celulares i
              INNER JOIN marcas_celulares m ON i.inv_marca = m.mar_id
              WHERE i.inv_stock <= $limite 
              AND i.inv_situacion = '1'
              ORDER BY i.inv_stock ASC";

        $resultado = self::fetchArray($sql);

        return $resultado ?: [];
    }

    public static function cambiarEstado($id, $nuevo_estado)
    {
        $sql = "UPDATE inventario_celulares 
              SET inv_estado = '$nuevo_estado'
              WHERE inv_id = $id 
              AND inv_situacion = '1'";

        return self::SQL($sql);
    }

    public static function obtenerEstadisticasInventario()
    {
        $sql = "SELECT 
                COUNT(*) as total_productos,
                SUM(inv_stock) as total_stock,
                AVG(inv_precio) as precio_promedio,
                SUM(inv_precio * inv_stock) as valor_total_inventario,
                COUNT(CASE WHEN inv_stock > 0 THEN 1 END) as productos_disponibles,
                COUNT(CASE WHEN inv_stock = 0 THEN 1 END) as productos_agotados
              FROM inventario_celulares 
              WHERE inv_situacion = '1'";

        $resultado = self::fetchArray($sql);

        return $resultado ? $resultado[0] : null;
    }

    // Método para actualizar todos los estados basados en stock actual
    public static function actualizarTodosLosEstados()
    {
        $sql = "UPDATE inventario_celulares 
              SET inv_estado = CASE 
                WHEN inv_stock > 0 THEN 'disponible'
                ELSE 'no disponible'
              END
              WHERE inv_situacion = '1'";

        return self::SQL($sql);
    }

    // Obtener solo productos disponibles
    public static function obtenerInventarioDisponible()
    {
        $sql = "SELECT 
                i.inv_id,
                i.inv_modelo,
                i.inv_marca,
                i.inv_precio,
                i.inv_stock,
                m.mar_nombre as marca_nombre
              FROM inventario_celulares i
              INNER JOIN marcas_celulares m ON i.inv_marca = m.mar_id
              WHERE i.inv_stock > 0 
              AND i.inv_situacion = '1'
              ORDER BY i.inv_modelo ASC";

        $resultado = self::fetchArray($sql);

        return $resultado ?: [];
    }

    // Obtener productos agotados
    public static function obtenerInventarioAgotado()
    {
        $sql = "SELECT 
                i.inv_id,
                i.inv_modelo,
                i.inv_marca,
                i.inv_precio,
                m.mar_nombre as marca_nombre
              FROM inventario_celulares i
              INNER JOIN marcas_celulares m ON i.inv_marca = m.mar_id
              WHERE i.inv_stock = 0 
              AND i.inv_situacion = '1'
              ORDER BY i.inv_modelo ASC";

        $resultado = self::fetchArray($sql);

        return $resultado ?: [];
    }
}
