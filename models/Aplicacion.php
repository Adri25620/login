<?php

namespace Model;
use Model\ActiveRecord;

class Aplicacion extends ActiveRecord
{

    public static $tabla = 'aplicacion';
    public static $columnasDB = [

        'ap_nombre_lg',
        'ap_nombre_md',
        'ap_nombre_ct',
        'ap_fecha_creacion',
        'ap_situacion'
    ];
    public static $idTabla = 'ap_id';

    public $ap_id;
    public $ap_nombre_lg;
    public $ap_nombre_md;
    public $ap_nombre_ct;
    public $ap_fecha_creacion;
    public $ap_situacion;


    public function __construct($args = []){
        $this->ap_id = $args['ap_id'] ?? null;
        $this->ap_nombre_lg = $args['ap_nombre_lg'] ?? '';
        $this->ap_nombre_md = $args['ap_nombre_md'] ?? '';
        $this->ap_nombre_ct = $args['ap_nombre_ct'] ?? '';
        $this->ap_fecha_creacion = $args['ap_fecha_creacion'] ?? '';
        $this->ap_situacion = $args['ap_situacion'] ?? 1;   
        
    }

    public static function EliminarAplicacion($id){
        $sql = "UPDATE aplicacion SET ap_situacion = 0 WHERE ap_id = $id";
        return self::SQL($sql);
    }

}
