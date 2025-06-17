<?php

namespace Model;
use Model\ActiveRecord;

class Permisos extends ActiveRecord
{

    public static $tabla = 'permisos';
    public static $columnasDB = [

        'per_aplicacion',
        'per_nombre_permiso',
        'per_clave_permiso',
        'per_descripcion',
        'per_fecha',
        'per_situacion'
    ];
    public static $idTabla = 'per_id';

    public $per_id;
    public $per_aplicacion;
    public $per_nombre_permiso;
    public $per_clave_permiso;
    public $per_descripcion;
    public $per_fecha;
    public $per_situacion;


    public function __construct($args = []){
        $this->per_id = $args['per_id'] ?? null;
        $this->per_aplicacion = $args['per_aplicacion'] ?? '';
        $this->per_nombre_permiso = $args['per_nombre_permiso'] ?? '';
        $this->per_clave_permiso = $args['per_clave_permiso'] ?? '';
        $this->per_descripcion = $args['per_descripcion'] ?? '';
        $this->per_fecha = $args['per_fecha'] ?? '';
        $this->per_situacion = $args['per_situacion'] ?? 1;   
        
    }

    public static function EliminarPermisos($id){
        $sql = "UPDATE permisos SET per_situacion = 0 WHERE per_id = $id";
        return self::SQL($sql);
    }

}