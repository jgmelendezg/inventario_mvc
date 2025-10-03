<?php
require_once(__DIR__ . '/../core/BaseModel.php');
require_once(__DIR__ . '/EquipoModel.php');

class DashboardModel extends BaseModel
{
    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    public function obtenerDatosDashboard(): array
    {
        $datos = [];
        try {
            // Contadores principales
            $datos['totalEquipos'] = $this->conexion->query("SELECT COUNT(*) FROM equipos")->fetch_row()[0];
            $datos['equiposActivos'] = $this->conexion->query("SELECT COUNT(*) FROM equipos WHERE estado = 'Activo'")->fetch_row()[0];
            $datos['equiposRetirados'] = $this->conexion->query("SELECT COUNT(*) FROM equipos WHERE estado = 'Retirado'")->fetch_row()[0];
            $datos['totalUsuarios'] = $this->conexion->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0];
            $datos['totalAsignaciones'] = $this->conexion->query("SELECT COUNT(*) FROM asignaciones")->fetch_row()[0];

            // Equipos sin software
            $datos['equiposSinSoftware'] = $this->conexion->query("
            SELECT COUNT(*) FROM equipos 
            WHERE id_equipo NOT IN (SELECT id_equipo FROM asignacion_software)
            ")->fetch_row()[0];

            // Equipos sin asignación activa
            $datos['equiposSinAsignacion'] = $this->conexion->query("
                SELECT COUNT(*) FROM equipos 
                WHERE id_equipo NOT IN (
                    SELECT id_equipo FROM asignaciones WHERE estado = 'Activo'
                )
            ")->fetch_row()[0];

            // Distribución por estado para el gráfico
            $resultado = $this->conexion->query("SELECT estado, COUNT(*) as cantidad FROM equipos GROUP BY estado");
            $datos['estadoEquipos'] = [];
            while ($fila = $resultado->fetch_assoc()) {
                $datos['estadoEquipos'][] = $fila;
            }

            // Últimos logs
            $datos['ultimosLogs'] = $this->conexion->query("
                SELECT accion, fecha FROM logs 
                ORDER BY fecha DESC LIMIT 5
            ")->fetch_all(MYSQLI_ASSOC);

            // Avisos de garantía
            $datos['avisosGarantia'] = $this->conexion->query("
                SELECT nombre_pc, fecha_garantia 
                FROM equipos 
                WHERE fecha_garantia IS NOT NULL 
                AND fecha_garantia >= CURDATE()
                AND DATEDIFF(fecha_garantia, CURDATE()) <= 30
                ORDER BY fecha_garantia ASC
            ")->fetch_all(MYSQLI_ASSOC);

            return $datos;
        } catch (Exception $e) {
            error_log("Error en DashboardModel: " . $e->getMessage());
            return [];
        }
    }
}