<?php

namespace Model;
use Model\ActiveRecord;

class HistorialAct extends ActiveRecord
{

    public static $tabla = 'historial_act';
    public static $columnasDB = [

        'his_usuario',
        'his_fecha',
        'his_ruta',
        'his_ejecucion',
        'his_situacion'
    ];
    public static $idTabla = 'his_id';

    public $his_id;
    public $his_usuario;
    public $his_fecha;
    public $his_ruta;
    public $his_ejecucion;
    public $his_situacion;


    public function __construct($args = []){
        $this->his_id = $args['his_id'] ?? null;
        $this->his_usuario = $args['his_usuario'] ?? '';
        $this->his_fecha = $args['his_fecha'] ?? '';
        $this->his_ruta = $args['his_ruta'] ?? '';
        $this->his_ejecucion = $args['his_ejecucion'] ?? '';
        $this->his_situacion = $args['his_situacion'] ?? 1;   
        
    }

    public static function EliminarHistorial($id){
        $sql = "UPDATE historial_act SET his_situacion = 0 WHERE his_id = $id";
        return self::SQL($sql);
    }

}