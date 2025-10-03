<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class MantenimientoModel extends BaseModel
{
    private $tabla = 'mantenimientos';

    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    public function obtenerHistorialPorEquipo(int $id_equipo): mysqli_result
    {
        $sql = "SELECT m.*, u.nombre AS nombre_tecnico
                FROM {$this->tabla} m
                JOIN usuarios u ON m.id_usuario_tecnico = u.id_usuario
                WHERE m.id_equipo = ?
                ORDER BY m.fecha_mantenimiento DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_equipo);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    public function registrar(array $datos): bool
    {
        $sql = "INSERT INTO {$this->tabla} (id_equipo, id_usuario_tecnico, fecha_solicitud, fecha_mantenimiento, tipo_mantenimiento, motivos, diagnostico, soluciones, recomendaciones) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iisssssss",
            $datos['id_equipo'],
            $datos['id_usuario_tecnico'],
            $datos['fecha_solicitud'],
            $datos['fecha_mantenimiento'],
            $datos['tipo_mantenimiento'],
            $datos['motivos'],
            $datos['diagnostico'],
            $datos['soluciones'],
            $datos['recomendaciones']
        );
        return $stmt->execute();
    }
    
    public function obtenerListado(): mysqli_result
    {
        $sql = "SELECT m.*, e.nombre_pc, u.nombre AS nombre_tecnico 
                FROM {$this->tabla} m
                JOIN equipos e ON m.id_equipo = e.id_equipo
                JOIN usuarios u ON m.id_usuario_tecnico = u.id_usuario
                ORDER BY m.fecha_mantenimiento DESC";
        return $this->conexion->query($sql);
    }
    
    public function obtenerMantenimientosProgramados(): mysqli_result
    {
        $sql = "SELECT m.*, e.nombre_pc
                FROM {$this->tabla} m
                JOIN equipos e ON m.id_equipo = e.id_equipo
                WHERE m.estado_programacion = 'Programado'
                ORDER BY m.fecha_programada ASC";
        return $this->conexion->query($sql);
    }

    public function obtenerUltimoMantenimiento(int $id_equipo)
    {
        $sql = "SELECT fecha_mantenimiento
                FROM {$this->tabla}
                WHERE id_equipo = ? AND estado_programacion = 'Realizado'
                ORDER BY fecha_mantenimiento DESC
                LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_equipo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function programarMantenimiento(array $datos): bool
    {
        $sql = "INSERT INTO {$this->tabla} (id_equipo, id_usuario_tecnico, fecha_solicitud, fecha_programada, tipo_mantenimiento, motivos, observaciones_solicitud, estado_programacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Programado')";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iisssss",
            $datos['id_equipo'],
            $datos['id_usuario_tecnico'],
            $datos['fecha_solicitud'],
            $datos['fecha_programada'],
            $datos['tipo_mantenimiento'],
            $datos['motivos'],
            $datos['observaciones_solicitud']
        );
        return $stmt->execute();
    }
}