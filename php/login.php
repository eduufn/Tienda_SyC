<?php
session_start();
include 'conexion.php';

$correo = $_POST['correo'];
$contraseña = $_POST['contraseña'];

$sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    if (password_verify($contraseña, $usuario['contraseña'])) {
        // Guardar datos en la sesión
        $_SESSION['id'] = $usuario['id']; // NECESARIO para productos
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['apellido'] = $usuario['apellido'];

        header("Location: ../html/index.php");
        exit;
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}
?>
