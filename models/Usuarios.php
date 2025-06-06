<?php

namespace Model;

class Usuarios extends ActiveRecord
{

    public static $tabla = 'usuarios';
    public static $columnasDB = [

        'us_pri_nombre',
        'us_seg_nombre',
        'us_pri_apellido',
        'us_seg_apellido',
        'us_telefono',
        'us_direccion',
        'us_dpi',
        'us_correo',
        'us_contrasenia',
        'us_token',
        'us_fecha_creacion',
        'us_fecha_contrasenia',
        'us_situacion'
    ];
    public static $idTabla = 'us_id';

    public $us_id;
    public $us_pri_nombre;
    public $us_seg_nombre;
    public $us_pri_apellido;
    public $us_seg_apellido;
    public $us_telefono;
    public $us_direccion;
    public $us_dpi;
    public $us_correo;
    public $us_contrasenia;
    public $us_token;
    public $us_fecha_creacion;
    public $us_fecha_contrasenia;
    public $us_situacion;


    public function __construct($args = []){
        $this->us_id = $args['us_id'] ?? null;
    $this->us_pri_nombre = $args['us_pri_nombre'] ?? '';
    $this->us_seg_nombre = $args['us_seg_nombre'] ?? '';
    $this->us_pri_apellido = $args['us_pri_apellido'] ?? '';
    $this->us_seg_apellido = $args['us_seg_apellido'] ?? '';
    $this->us_telefono = $args['us_telefono'] ?? '';
    $this->us_direccion = $args['us_direccion'] ?? '';
    $this->us_dpi = $args['us_dpi'] ?? '';
    $this->us_correo = $args['us_correo'] ?? '';
    $this->us_contrasenia = $args['us_contrasenia'] ?? '';
    $this->us_token = $args['us_token'] ?? '';
    $this->us_fecha_creacion = $args['us_fecha_creacion'] ?? '';
    $this->us_fecha_contrasenia = $args['us_fecha_contrasenia'] ?? '';
    $this->us_situacion = $args['us_situacion'] ?? 1;    
        
    }

    public static function EliminarUsuarios($id){
        $sql = "UPDATE usuarios SET us_situacion = 0 WHERE us_id = $id";
        return self::SQL($sql);
    }

}