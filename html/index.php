<?php 
session_start();
if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
  header("Location: login.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tienda SyC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      
    .sidebar {
      min-width: 200px;
    }
    .logo {
      width: 40px;
      height: 40px;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <!-- Barra superior -->
    <div class="row bg-light align-items-center py-2">
      <div class="col-md-2 d-flex align-items-center justify-content-center">
       <img src="../img/logoSyC.png" alt="Logo SyC" class="logo me-2" style="height: 60px;width: 60px">

      </div>
      <div class="col-md-6">
        <input type="text" id="buscador" class="form-control" placeholder="Búsqueda...">
      </div>
      <div class="col-md-2 text-end">
        <span>Bienvenido, <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?></span>
      </div>
      <div class="col-md-2 text-end">
        <a href="../php/logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
      </div>
    </div>

    <!-- Cuerpo -->
    <div class="row mt-3">
      <!-- Filtro lateral -->
      <div class="col-md-2 sidebar">
        <h5>Filtrar por</h5>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="categoria" id="categoria">
          <label class="form-check-label" for="categoria">Categoría</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="marca" id="marca">
          <label class="form-check-label" for="marca">Marca</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="precio" id="precio">
          <label class="form-check-label" for="precio">Precio</label>
        </div>
      </div>

      <!-- Tabla y botón -->
      <div class="col-md-10">
        <div class="mb-3 text-end">
  <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregar">Añadir Producto</button>
</div>

          


        <!-- Tabla de productos -->
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Imagen</th>
              <th>Nombre</th>
              <th>Marca</th>
              <th>Categoría</th>
              <th>Stock</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaProductos">
            <!-- Aquí se insertarán los productos -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Agregar -->
  <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="../php/agregar_producto.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title">Agregar Producto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="mb-3">
              <label for="marca" class="form-label">Marca</label>
              <input type="text" class="form-control" name="marca" required>
            </div>
            <div class="mb-3">
              <label for="categoria" class="form-label">Categoría</label>
              <input type="text" class="form-control" name="categoria" required>
            </div>
            <div class="mb-3">
              <label for="cantidad" class="form-label">Stock</label>
              <input type="number" class="form-control" name="cantidad" required>
            </div>
              
            <div class="mb-3">
              <label for="imagen" class="form-label">Imagen</label>
              <input type="file" class="form-control" name="imagen" accept="image/*" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="mb-3 text-end">
<a href="../php/reporte.php" class="btn btn-info btn-sm">Generar Reporte</a>
<a href="../php/reporte.php" class="btn btn-info btn-sm" target="_blank">Ver Reporte</a>

</div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script para cargar productos -->
  <script>
    document.getElementById("buscador").addEventListener("input", function () {
      const valor = this.value.toLowerCase();
      const filas = document.querySelectorAll("#tablaProductos tr");
      filas.forEach(fila => {
        fila.style.display = fila.textContent.toLowerCase().includes(valor) ? "" : "none";
      });
    });

    function cargarProductos() {
      fetch("../php/listar_productos.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("tablaProductos");
          tbody.innerHTML = "";
          data.forEach(producto => {
            tbody.innerHTML += `
              <tr>
                <td><img src="../img/${producto.imagen}" alt="Producto" width="50"></td>
                <td>${producto.nombre}</td>
                <td>${producto.marca}</td>
                <td>${producto.categoria}</td>
                <td>${producto.cantidad}</td>
                <td>
                  <button class="btn btn-primary btn-sm" onclick="editarProducto(${producto.id})">Editar</button>
                  <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${producto.id})">Eliminar</button>
                </td>
              </tr>
            `;
          });
        });
    }

    function eliminarProducto(id) {
      if (confirm("¿Estás seguro de eliminar este producto?")) {
        fetch("../php/eliminar_producto.php?id=" + id)
          .then(res => res.text())
          .then(msg => {
            alert(msg);
            cargarProductos();
          });
      }
    }

function editarProducto(id) {
  window.location.href = "editar.php?id=" + id;
}


    window.onload = cargarProductos;
  </script>
</body>
</html>
