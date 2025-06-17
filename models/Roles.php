<?php

namespace Model;
use Model\ActiveRecord;

class Roles extends ActiveRecord
{

    public static $tabla = 'rol';
    public static $columnasDB = [
        'rol_nombre',
        'rol_nombre_ct',
        'rol_situacion'
    ];
    public static $idTabla = 'rol_id';

    public $rol_id;
    public $rol_nombre;
    public $rol_nombre_ct;
    public $rol_situacion;


    public function __construct($args = [])
    {
        $this->rol_id = $args['rol_id'] ?? null;
        $this->rol_nombre = $args['rol_nombre'] ?? '';
        $this->rol_nombre_ct = $args['rol_nombre_ct'] ?? '';
        $this->rol_situacion = $args['rol_situacion'] ?? 1;   
    }

    public static function EliminarRol($id)
    {
        $sql = "UPDATE rol SET rol_situacion = 0 WHERE rol_id = $id";
        return self::SQL($sql);
    }

}