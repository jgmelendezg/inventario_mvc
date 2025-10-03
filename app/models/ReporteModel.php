<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class ReporteModel extends BaseModel
{
    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    public function obtenerEquiposActivos()
    {
        $sql = "SELECT * FROM equipos WHERE estado = 'Activo'";
        return $this->conexion->query($sql);
    }
    
    public function obtenerEquiposConUsuariosAsignados()
    {
        $sql = "SELECT e.*, u.nombre AS nombre_usuario
                FROM equipos e
                JOIN asignaciones a ON e.id_equipo = a.id_equipo
                JOIN usuarios u ON a.id_usuario = u.id_usuario
                WHERE a.estado = 'Activo'
                ORDER BY e.nombre_pc";
        return $this->conexion->query($sql);
    }

    public function obtenerEquiposConSoftwareAsignado()
    {
        $sql = "SELECT e.*, p.nombre_programa
                FROM equipos e
                JOIN asignacion_software aso ON e.id_equipo = aso.id_equipo
                JOIN programas p ON aso.id_programa = p.id_programa
                ORDER BY e.nombre_pc";
        return $this->conexion->query($sql);
    }

    public function obtenerDetalleEquipos()
    {
        $sql = "SELECT e.*, u.nombre AS nombre_usuario, aso.id_programa
                FROM equipos e
                LEFT JOIN asignaciones a ON e.id_equipo = a.id_equipo AND a.estado = 'Activo'
                LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario
                LEFT JOIN asignacion_software aso ON e.id_equipo = aso.id_equipo
                GROUP BY e.id_equipo
                ORDER BY e.nombre_pc";
        return $this->conexion->query($sql);
    }

    public function obtenerMantenimientos()
    {
        $sql = "SELECT m.*, e.nombre_pc, u.nombre AS nombre_tecnico
                FROM mantenimientos m
                JOIN equipos e ON m.id_equipo = e.id_equipo
                LEFT JOIN usuarios u ON u.id_usuario = m.id_usuario_tecnico
                ORDER BY m.fecha_mantenimiento DESC";
        return $this->conexion->query($sql);
    }
}