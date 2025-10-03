<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class AsignacionModel extends BaseModel
{
    private $tabla = 'asignaciones';

    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    
    }
    
    public function obtenerHistorial(): mysqli_result
    {
        $sql = "SELECT a.*, e.nombre_pc, u.nombre AS nombre_usuario 
                FROM {$this->tabla} a
                JOIN equipos e ON a.id_equipo = e.id_equipo
                JOIN usuarios u ON a.id_usuario = u.id_usuario
                ORDER BY a.fecha_asignacion DESC";
        return $this->conexion->query($sql);
    }

    public function obtenerAsignacionesActivas(): mysqli_result
    {
        $sql = "SELECT a.*, e.nombre_pc, u.nombre AS nombre_usuario 
                FROM {$this->tabla} a
                JOIN equipos e ON a.id_equipo = e.id_equipo
                JOIN usuarios u ON a.id_usuario = u.id_usuario
                WHERE a.estado = 'Activo'
                ORDER BY a.fecha_asignacion DESC";
        return $this->conexion->query($sql);
    }

    public function obtenerEquiposSinAsignacion()
    {
        $sql = "SELECT * FROM {$this->tabla} 
                WHERE estado = 'Activo' 
                AND id_equipo NOT IN (
                    SELECT id_equipo FROM asignaciones WHERE estado = 'Activo'
                )
                ORDER BY nombre_pc ASC";
        return $this->conexion->query($sql);
    }

    public function registrar(array $datos): bool
    {
        $sql = "INSERT INTO {$this->tabla} (id_equipo, id_usuario, fecha_asignacion, estado, observaciones)
                VALUES (?, ?, ?, 'Activo', ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iiss", $datos['id_equipo'], $datos['id_usuario'], $datos['fecha_asignacion'], $datos['observaciones']);
        return $stmt->execute();
    }

    public function desasignar(int $id_asignacion): bool
    {
        $sql = "UPDATE {$this->tabla} SET estado = 'Finalizado', fecha_desasignacion = CURDATE() WHERE id_asignacion = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_asignacion);
        return $stmt->execute();
    }
}