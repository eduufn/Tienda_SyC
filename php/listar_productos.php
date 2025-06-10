<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "Tienda_SyC");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['id'];

$sql = "SELECT * FROM productos WHERE id_usuario = $id_usuario";
$resultado = $conexion->query($sql);

$productos = array();
while ($fila = $resultado->fetch_assoc()) {
    $productos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($productos);

$conexion->close();
