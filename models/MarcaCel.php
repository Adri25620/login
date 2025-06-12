<?php

namespace Model;

class MarcaCel extends ActiveRecord
{

    public static $tabla = 'marcas_celulares';
    public static $columnasDB = [
        'mar_nombre',
        'mar_descripcion',
        'mar_fecha_creacion',
        'mar_situacion'
    ];
    public static $idTabla = 'mar_id';

    public $mar_id;
    public $mar_nombre;
    public $mar_descripcion;
    public $mar_fecha_creacion;
    public $mar_situacion;


    public function __construct($args = [])
    {
        $this->mar_id = $args['mar_id'] ?? null;
        $this->mar_nombre = $args['mar_nombre'] ?? '';
        $this->mar_descripcion = $args['mar_descripcion'] ?? '';
        $this->mar_fecha_creacion = $args['mar_fecha_creacion'] ?? '';
        $this->mar_situacion = $args['mar_situacion'] ?? 1;
    }

    public static function EliminarMarca($id)
    {
        $sql = "UPDATE marcas_celulares SET mar_situacion = 0 WHERE mar_id = $id";
        return self::SQL($sql);
    }

}