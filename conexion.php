<?php
// Parámetros de conexión
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "inventario_equipos";

function get_db_connection(): mysqli
{
    global $host, $usuario, $contrasena, $base_datos;
    $conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    $conexion->set_charset("utf8");

    return $conexion;
}
?>