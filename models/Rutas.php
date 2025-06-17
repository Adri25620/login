<?php

namespace Model;
use Model\ActiveRecord;

class Rutas extends ActiveRecord
{

    public static $tabla = 'rutas';
    public static $columnasDB = [

        'rut_aplicacion',
        'rut_ruta',
        'rut_descripcion',
        'rut_situacion'
    ];
    public static $idTabla = 'rut_id';

    public $rut_id;
    public $rut_aplicacion;
    public $rut_ruta;
    public $rut_descripcion;
    public $rut_situacion;


    public function __construct($args = []){
        $this->rut_id = $args['rut_id'] ?? null;
        $this->rut_aplicacion = $args['rut_aplicacion'] ?? '';
        $this->rut_ruta = $args['rut_ruta'] ?? '';
        $this->rut_descripcion = $args['rut_descripcion'] ?? '';
        $this->rut_situacion = $args['rut_situacion'] ?? 1;   
        
    }

    public static function EliminarRutas($id){
        $sql = "UPDATE rutas SET rut_situacion = 0 WHERE rut_id = $id";
        return self::SQL($sql);
    }

}
