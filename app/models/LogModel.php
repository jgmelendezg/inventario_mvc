<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class LogModel extends BaseModel
{
    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    /**
     * Registra una acción en la tabla de logs.
     * @param string $accion Descripción de la acción.
     * @param string $usuario Nombre del usuario que realiza la acción.
     * @return bool
     */
    public function registrarLog(string $accion, string $usuario = 'sistema'): bool
    {
        try {
            $sql = "INSERT INTO logs (usuario, accion, fecha) VALUES (?, ?, NOW())";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("ss", $usuario, $accion);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al registrar log: " . $e->getMessage());
            return false;
        }
    }
}