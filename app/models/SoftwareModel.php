<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class SoftwareModel extends BaseModel
{
    private $tabla = 'programas';

    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    public function listarProgramas(): mysqli_result
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY nombre_programa ASC";
        return $this->conexion->query($sql);
    }
    
    public function registrar(array $datos): bool
    {
        $sql = "INSERT INTO {$this->tabla} (nombre_programa, version, fabricante, clave_licencia) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssss",
            $datos['nombre_programa'],
            $datos['version'],
            $datos['fabricante'],
            $datos['clave_licencia']
        );
        return $stmt->execute();
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = "UPDATE {$this->tabla} SET nombre_programa=?, version=?, fabricante=?, clave_licencia=? WHERE id_programa=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssssi",
            $datos['nombre_programa'],
            $datos['version'],
            $datos['fabricante'],
            $datos['clave_licencia'],
            $id
        );
        return $stmt->execute();
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM {$this->tabla} WHERE id_programa=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE id_programa = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }
}