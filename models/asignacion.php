<?php

namespace Model;
use Model\ActiveRecord;

class Asignacion extends ActiveRecord
{

    public static $tabla = 'asig_permisos';
    public static $columnasDB = [
        'asig_usuario',
        'asig_aplicacion',
        'asig_permisos',
        'asig_fecha',
        'asig_quitar_fechaPermiso',
        'asig_usuario_asignador',
        'asig_motivo',
        'asig_situacion'
    ];
    public static $idTabla = 'asig_id';

    public $asig_id;
    public $asig_usuario;
    public $asig_aplicacion;
    public $asig_permisos;
    public $asig_fecha;
    public $asig_quitar_fechaPermiso;
    public $asig_usuario_asignador;
    public $asig_motivo;
    public $asig_situacion;


    public function __construct($args = [])
    {
        $this->asig_id = $args['asig_id'] ?? null;
        $this->asig_usuario = $args['asig_usuario'] ?? null;
        $this->asig_aplicacion = $args['asig_aplicacion'] ?? null;
        $this->asig_permisos = $args['asig_permisos'] ?? null;
        $this->asig_fecha = $args['asig_fecha'] ?? date('Y-m-d H:i');
        $this->asig_quitar_fechaPermiso = $args['asig_quitar_fechaPermiso'] ?? date('Y-m-d H:i');
        $this->asig_usuario_asignador = $args['asig_usuario_asignador'] ?? null;
        $this->asig_motivo = $args['asig_motivo'] ?? '';
        $this->asig_situacion = $args['asig_situacion'] ?? '1';
    }

    public static function EliminarAsignacion($id)
    {
        $sql = "UPDATE asig_permisos SET asig_situacion = '0' WHERE asig_id = $id";
        return self::SQL($sql);
    }

    public static function obtenerAsignaciones()
    {
        $sql = "SELECT 
                a.asig_id,
                a.asig_usuario,
                a.asig_aplicacion,
                a.asig_permisos,
                a.asig_fecha,
                a.asig_quitar_fechaPermiso,
                a.asig_usuario_asignador,
                a.asig_motivo,
                u.us_nombres || ' ' || u.us_apellidos as usuario_nombre,
                ap.ap_nombre_lg as aplicacion_nombre,
                p.per_nombre_permiso as permiso_nombre,
                ua.us_nombres || ' ' || ua.us_apellidos as asignador_nombre
              FROM asig_permisos a
              INNER JOIN usuarios u ON a.asig_usuario = u.us_id
              INNER JOIN aplicacion ap ON a.asig_aplicacion = ap.ap_id
              INNER JOIN permisos p ON a.asig_permisos = p.per_id
              INNER JOIN usuarios ua ON a.asig_usuario_asignador = ua.us_id
              WHERE a.asig_situacion = '1'
              ORDER BY a.asig_id DESC";
        
        $resultado = self::fetchArray($sql);
        
        // Asegurar que siempre devuelva un array
        return $resultado ?: [];
    }

    public static function obtenerAsignacionesPorUsuario($usuario_id)
    {
        $sql = "SELECT 
                a.asig_id,
                a.asig_usuario,
                a.asig_aplicacion,
                a.asig_permisos,
                a.asig_fecha,
                a.asig_quitar_fechaPermiso,
                a.asig_motivo,
                ap.ap_nombre,
                p.per_nombre_permiso,
                p.per_clave_permiso
              FROM asig_permisos a
              INNER JOIN aplicacion ap ON a.asig_aplicacion = ap.ap_id
              INNER JOIN permisos p ON a.asig_permisos = p.per_id
              WHERE a.asig_usuario = $usuario_id AND a.asig_situacion = '1'
              ORDER BY a.asig_fecha DESC";
        
        $resultado = self::fetchArray($sql);
        
        return $resultado ?: [];
    }

    public static function verificarPermisoExistente($usuario_id, $aplicacion_id, $permiso_id)
    {
        $sql = "SELECT COUNT(*) as total 
              FROM asig_permisos 
              WHERE asig_usuario = $usuario_id 
              AND asig_aplicacion = $aplicacion_id 
              AND asig_permisos = $permiso_id 
              AND asig_situacion = '1'";
        
        $resultado = self::fetchArray($sql);
        
        return $resultado[0]['total'] > 0;
    }

    public static function quitarPermiso($usuario_id, $aplicacion_id, $permiso_id)
    {
        $sql = "UPDATE asig_permisos 
              SET asig_situacion = '0', 
                  asig_quitar_fechaPermiso = CURRENT 
              WHERE asig_usuario = $usuario_id 
              AND asig_aplicacion = $aplicacion_id 
              AND asig_permisos = $permiso_id 
              AND asig_situacion = '1'";
        
        return self::SQL($sql);
    }
}