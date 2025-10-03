<?php
require_once(__DIR__ . '/../core/BaseModel.php');

class ComboModel extends BaseModel
{
    public function __construct(mysqli $conexion)
    {
        parent::__construct($conexion);
    }

    public function areas(): array
    {
        return $this->conexion->query("SELECT id_area, nombre_area FROM areas ORDER BY nombre_area")->fetch_all(MYSQLI_ASSOC);
    }
    
    public function sistemas(): array
    {
        return $this->conexion->query("SELECT id_sistema, nombre_sistema FROM sistemas_operativos ORDER BY nombre_sistema")->fetch_all(MYSQLI_ASSOC);
    }
}