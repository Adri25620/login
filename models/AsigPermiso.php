<?php

namespace Model;

class AsigPermiso extends ActiveRecord
{

    public static $tabla = 'asig_permiso';
    public static $columnasDB = [

        'asig_usuario',
        'asig_aplicacion',
        'asig_permiso',
        'asig_fecha',
        'asig_us_asignado',
        'asig_asig_motivo',
        'asig_situacion'
    ];
    public static $idTabla = 'asig_id';

    public $asig_id;
    public $asig_usuario;
    public $asig_aplicacion;
    public $asig_permiso;
    public $asig_fecha;
    public $asig_us_asignado;
    public $asig_asig_motivo;
    public $asig_situacion;


    public function __construct($args = []){
        $this->asig_id = $args['asig_id'] ?? null;
        $this->asig_usuario = $args['asig_usuario'] ?? '';
        $this->asig_aplicacion = $args['asig_aplicacion'] ?? '';
        $this->asig_permiso = $args['asig_permiso'] ?? '';
        $this->asig_fecha = $args['asig_fecha'] ?? '';
        $this->asig_us_asignado = $args['us_asignado'] ?? '';
        $this->asig_asig_motivo = $args['asig_motivo'] ?? '';
        $this->asig_situacion = $args['asig_situacion'] ?? 1;   
        
    }

    public static function EliminarPermiso($id){
        $sql = "UPDATE asig_permiso SET asig_situacion = 0 WHERE asig_id = $id";
        return self::SQL($sql);
    }

}