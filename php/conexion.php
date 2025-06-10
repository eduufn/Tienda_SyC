<?php
$conexion = new mysqli("localhost", "root", "", "Tienda_SyC");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>