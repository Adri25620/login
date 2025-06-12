<?php

namespace Model;

class Cliente extends ActiveRecord
{

    public static $tabla = 'clientes';
    public static $columnasDB = [
        'cli_nombres',
        'cli_apellidos',
        'cli_nit',
        'cli_telefono',
        'cli_correo',
        'cli_direccion',
        'cli_situacion'
    ];
    public static $idTabla = 'cli_id';

    public $cli_id;
    public $cli_nombres;
    public $cli_apellidos;
    public $cli_nit;
    public $cli_telefono;
    public $cli_correo;
    public $cli_direccion;
    public $cli_situacion;


    public function __construct($args = [])
    {
        $this->cli_id = $args['cli_id'] ?? null;
        $this->cli_nombres = $args['cli_nombres'] ?? '';
        $this->cli_apellidos = $args['cli_apellidos'] ?? '';
        $this->cli_nit = $args['cli_nit'] ?? '';
        $this->cli_telefono = $args['cli_telefono'] ?? '';
        $this->cli_correo = $args['cli_correo'] ?? '';
        $this->cli_direccion = $args['cli_direccion'] ?? '';
        $this->cli_situacion = $args['cli_situacion'] ?? 1;
    }

    public static function EliminarCliente($id)
    {
        $sql = "UPDATE clientes SET cli_situacion = 0 WHERE cli_id = $id";
        return self::SQL($sql);
    }

}