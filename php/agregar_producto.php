<?php
session_start(); // Necesario para acceder a la sesión

if (!isset($_SESSION['id'])) {
    echo "Usuario no autenticado.";
    exit;
}

$conexion = new mysqli("localhost", "root", "", "Tienda_SyC");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['id']; // ID del usuario que inició sesión

$nombre = $_POST['nombre'];
$marca = $_POST['marca'];
$categoria = $_POST['categoria'];
$cantidad = $_POST['cantidad'];

$imagen = $_FILES['imagen']['name'];
$rutaTemporal = $_FILES['imagen']['tmp_name'];
$rutaDestino = "../img/" . $imagen;

// Campos adicionales
$estado = 'ACTIVO';
$fecha = date("Y-m-d H:i:s");

// Mover la imagen a la carpeta img
if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
    $sql = "INSERT INTO productos (id_usuario, nombre, marca, categoria, cantidad, imagen, estado, fecha_ingreso) 
            VALUES ($id_usuario, '$nombre', '$marca', '$categoria', $cantidad, '$imagen', '$estado', '$fecha')";

    if ($conexion->query($sql)) {
        echo "<script>alert('Producto agregado correctamente.'); window.location.href='../html/index.php';</script>";
    } else {
        echo "Error: " . $conexion->error;
    }
} else {
    echo "Error al subir la imagen.";
}

$conexion->close();
?>
