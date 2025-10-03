<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class AreaModel extends BaseModel
{
    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    public function listar(): mysqli_result
    {
        $sql = "SELECT * FROM areas ORDER BY nombre_area ASC";
        return $this->conexion->query($sql);
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM areas WHERE id_area = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function registrar(string $nombre): bool
    {
        $sql = "INSERT INTO areas (nombre_area) VALUES (?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        return $stmt->execute();
    }

    public function actualizar(int $id, string $nombre): bool
    {
        $sql = "UPDATE areas SET nombre_area = ? WHERE id_area = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM areas WHERE id_area = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}