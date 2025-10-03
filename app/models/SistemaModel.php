<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class SistemaModel extends BaseModel
{
    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    public function listar(): mysqli_result
    {
        $sql = "SELECT * FROM sistemas_operativos ORDER BY nombre_sistema ASC";
        return $this->conexion->query($sql);
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM sistemas_operativos WHERE id_sistema = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function registrar(string $nombre): bool
    {
        $sql = "INSERT INTO sistemas_operativos (nombre_sistema) VALUES (?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        return $stmt->execute();
    }

    public function actualizar(int $id, string $nombre): bool
    {
        $sql = "UPDATE sistemas_operativos SET nombre_sistema = ? WHERE id_sistema = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM sistemas_operativos WHERE id_sistema = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}