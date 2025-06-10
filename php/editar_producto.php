<?php
include("conexion.php");

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$marca = $_POST['marca'];
$categoria = $_POST['categoria'];
$cantidad = $_POST['cantidad'];

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["size"] > 0) {
    $nombreImg = $_FILES["imagen"]["name"];
    $ruta = "../img/" . $nombreImg;
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
    $imagen = "img/" . $nombreImg;

    $query = "UPDATE productos SET nombre=?, marca=?, categoria=?, cantidad=?, imagen=? WHERE id=?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssisi", $nombre, $marca, $categoria, $cantidad, $imagen, $id);
} else {
    $query = "UPDATE productos SET nombre=?, marca=?, categoria=?, cantidad=? WHERE id=?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssii", $nombre, $marca, $categoria, $cantidad, $id);
}

if ($stmt->execute()) {
    echo "<script>alert('Producto actualizado correctamente'); window.location.href='../html/index.php';</script>";
} else {
    echo "Error al actualizar: " . $stmt->error;
}
?>
