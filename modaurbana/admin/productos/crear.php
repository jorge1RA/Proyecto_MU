<?php
//crear_productos.php

include '../../includes/conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/admin/index.php');
    exit();
}

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);

    $imagen = '';

    // Manejo de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $extension = pathinfo($nombre_imagen, PATHINFO_EXTENSION);
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($extension), $extensiones_permitidas)) {
            $nuevo_nombre_imagen = uniqid('img_') . '.' . $extension;
            $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . '/modaurbana/assets/img/' . $nuevo_nombre_imagen;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                $imagen = $nuevo_nombre_imagen;
            } else {
                $error = "Error al subir la imagen.";
            }
        } else {
            $error = "Formato de imagen no permitido. Solo se aceptan JPG, JPEG, PNG y GIF.";
        }
    } else {
        $error = "Debe seleccionar una imagen.";
    }

    if (!isset($error)) {
        // Insertar el producto en la base de datos
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, categoria) 
                VALUES ('$nombre', '$descripcion', '$precio', '$imagen', '$categoria')";

        if (mysqli_query($conexion, $sql)) {
            $producto_id = mysqli_insert_id($conexion);

            // Insertar variantes de productos
            $tallas = $_POST['tallas'];
            $colores = $_POST['colores'];
            $stocks = $_POST['stocks'];

            for ($i = 0; $i < count($tallas); $i++) {
                $talla = mysqli_real_escape_string($conexion, $tallas[$i]);
                $color = mysqli_real_escape_string($conexion, $colores[$i]);
                $stock = intval($stocks[$i]);

                $sql_variante = "INSERT INTO producto_variantes (producto_id, talla, color, stock) 
                                VALUES ('$producto_id', '$talla', '$color', '$stock')";
                mysqli_query($conexion, $sql_variante);
            }

            header('Location: /modaurbana/admin/index.php');
            exit();
        } else {
            $error = "Error al agregar el producto: " . mysqli_error($conexion);
        }
    }
}
?>

<!-- Formulario para agregar producto -->
<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Agregar Producto</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="/modaurbana/admin/productos/crear.php" enctype="multipart/form-data">
        <!-- Campos del formulario -->
        <div class="form-group">
            <label>Nombre del Producto</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label>Precio (€)</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Imagen</label>
            <input type="file" name="imagen" class="form-control-file" required>
        </div>
        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" required>
        </div>

        <!-- Variantes de producto -->
        <div id="variantes">
            <div class="form-group variante">
                <label>Talla</label>
                <input type="text" name="tallas[]" class="form-control" required>
                <label>Color</label>
                <input type="text" name="colores[]" class="form-control" required>
                <label>Stock</label>
                <input type="number" name="stocks[]" class="form-control" min="0" required>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeVariante(this)">Eliminar Variante</button>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="addVariante()">Añadir Variante</button>

        <button type="submit" class="btn btn-success">Agregar Producto</button>
        <a href="/modaurbana/admin/productos/index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    function addVariante() {
        var variantesDiv = document.getElementById('variantes');
        var newVariante = document.createElement('div');
        newVariante.classList.add('form-group', 'variante');
        newVariante.innerHTML = `
            <label>Talla</label>
            <input type="text" name="tallas[]" class="form-control" required>
            <label>Color</label>
            <input type="text" name="colores[]" class="form-control" required>
            <label>Stock</label>
            <input type="number" name="stocks[]" class="form-control" min="0" required>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeVariante(this)">Eliminar Variante</button>
        `;
        variantesDiv.appendChild(newVariante);
    }

    function removeVariante(button) {
        button.parentElement.remove();
    }
</script>

<?php include '../../includes/templates/footer.php'; ?>