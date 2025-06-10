<?php
include("../php/conexion.php");

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    echo "Producto no encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h2>Editar Producto</h2>
    <form action="../php/editar_producto.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Marca</label>
        <input type="text" name="marca" class="form-control" value="<?php echo htmlspecialchars($producto['marca']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Categoría</label>
        <input type="text" name="categoria" class="form-control" value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Cantidad</label>
        <input type="number" name="cantidad" class="form-control" value="<?php echo $producto['cantidad']; ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Imagen (opcional)</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
        <br>
        <img src="../<?php echo $producto['imagen']; ?>" alt="Imagen actual" width="120">
      </div>
      <button type="submit" class="btn btn-primary">Actualizar Producto</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</body>
</html>
