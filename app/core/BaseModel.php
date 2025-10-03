<?php

abstract class BaseModel
{
    /**
     * @var mysqli
     */
    protected mysqli $conexion;

    public function __construct(mysqli $conexion)
    {
        $this->conexion = $conexion;

        if ($this->conexion->connect_error) {
            error_log("Error de conexión a la base de datos: " . $this->conexion->connect_error);
            die("Error de conexión a la base de datos.");
        }
    }
}