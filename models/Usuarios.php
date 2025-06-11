<?php

namespace Model;

class Usuarios extends ActiveRecord
{

    public static $tabla = 'usuarios';
    public static $columnasDB = [
        'us_nombres',
        'us_apellidos',
        'us_telefono',
        'us_direccion',
        'us_dpi',
        'us_correo',
        'us_rol',
        'us_contrasenia',
        'us_confirmar_contra',
        'us_token',
        'us_fecha_creacion',
        'us_fecha_contrasenia',
        'us_foto',
        'us_situacion'
    ];
    public static $idTabla = 'us_id';

    public $us_id;
    public $us_nombres;
    public $us_apellidos;
    public $us_telefono;
    public $us_direccion;
    public $us_dpi;
    public $us_correo;
    public $us_rol;
    public $us_contrasenia;
    public $us_confirmar_contra;
    public $us_token;
    public $us_fecha_creacion;
    public $us_fecha_contrasenia;
    public $us_foto;
    public $us_situacion;


    public function __construct($args = [])
    {
        $this->us_id = $args['us_id'] ?? null;
        $this->us_nombres = $args['us_nombres'] ?? '';
        $this->us_apellidos = $args['us_apellidos'] ?? '';
        $this->us_telefono = $args['us_telefono'] ?? '';
        $this->us_direccion = $args['us_direccion'] ?? '';
        $this->us_dpi = $args['us_dpi'] ?? '';
        $this->us_correo = $args['us_correo'] ?? '';
        $this->us_rol = $args['us_rol'] ?? '';
        $this->us_contrasenia = $args['us_contrasenia'] ?? '';
        $this->us_confirmar_contra = $args['us_confirmar_contra'] ?? '';
        $this->us_token = $args['us_token'] ?? '';
        $this->us_fecha_creacion = $args['us_fecha_creacion'] ?? '';
        $this->us_fecha_contrasenia = $args['us_fecha_contrasenia'] ?? '';
        $this->us_foto = $args['us_foto'] ?? null;
        $this->us_situacion = $args['us_situacion'] ?? 1;    
    }

    public static function EliminarUsuarios($id)
    {
        $sql = "UPDATE usuarios SET us_situacion = 0 WHERE us_id = $id";
        return self::SQL($sql);
    }


    public static function obtenerUsuariosConRol()
    {
        $sql = "SELECT 
                u.us_id,
                u.us_nombres,
                u.us_apellidos,
                u.us_telefono,
                u.us_direccion,
                u.us_dpi,
                u.us_correo,
                u.us_foto,
                r.rol_nombre
              FROM usuarios u
              INNER JOIN rol r ON u.us_rol = r.rol_id
              WHERE u.us_situacion = 1
              ORDER BY u.us_id DESC";
        
        return self::fetchArray($sql);
    }

}