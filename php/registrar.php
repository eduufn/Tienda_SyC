<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

// Registrar usuario
$sql = "INSERT INTO usuarios (nombre, apellido, correo, contraseña) 
        VALUES ('$nombre', '$apellido', '$correo', '$contraseña')";

if ($conexion->query($sql) === TRUE) {
    $id_usuario = $conexion->insert_id; // ID del nuevo usuario

    // OPCIONAL: Agregar productos base al nuevo usuario
    // $conexion->query("INSERT INTO productos (id_usuario, nombre, marca, categoria, cantidad, imagen) VALUES ($id_usuario, 'Aceite X', 'Marca X', 'Motor', 0, 'default.jpg')");

    header("Location: ../html/login.html");
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}
?>
