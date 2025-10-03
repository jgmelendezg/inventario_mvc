<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class SoftwareAsignacionModel extends BaseModel
{
    private string $tabla = 'asignacion_software';

    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }
    /**
     * Registra una nueva asignación de software a un equipo.
     */
    public function registrar(array $datos): bool
    {
        $sql = "INSERT INTO {$this->tabla} (id_equipo, id_programa, fecha_instalacion, observaciones) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iiss", 
            $datos['id_equipo'], 
            $datos['id_programa'], 
            $datos['fecha_instalacion'], 
            $datos['observaciones']
        );
        return $stmt->execute();
    }

    /**
     * Obtiene el listado completo de asignaciones de software.
     */
    public function obtenerListado(): mysqli_result
    {
        $sql = "SELECT 
                    a.id_asignacion_software,
                    e.nombre_pc,
                    p.nombre_programa,
                    a.fecha_instalacion,
                    a.observaciones
                FROM {$this->tabla} a
                JOIN equipos e ON a.id_equipo = e.id_equipo
                JOIN programas p ON a.id_programa = p.id_programa
                ORDER BY a.fecha_instalacion DESC";
        return $this->conexion->query($sql);
    }

    /**
     * Verifica si un programa específico ya está asignado a un equipo.
     */
    public function existeAsignacion(int $id_equipo, int $id_programa): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->tabla} WHERE id_equipo = ? AND id_programa = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_equipo, $id_programa);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_row();
        return (int)$resultado[0] > 0;
    }
}