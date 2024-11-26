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
    $tallas = mysqli_real_escape_string($conexion, $_POST['tallas']);
    $colores = mysqli_real_escape_string($conexion, $_POST['colores']);
    $stock = mysqli_real_escape_string($conexion, $_POST['stock']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);

    $imagen = '';

    // Manejo de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $tipo_imagen = $_FILES['imagen']['type'];
        $tamaño_imagen = $_FILES['imagen']['size'];
        $ruta_temporal = $_FILES['imagen']['tmp_name'];

        // Extensiones permitidas
        $extensiones_permitidas = array('jpg', 'jpeg', 'png', 'gif');
        $extension = pathinfo($nombre_imagen, PATHINFO_EXTENSION);

        if (in_array(strtolower($extension), $extensiones_permitidas)) {
            // Generar un nombre único para la imagen
            $nuevo_nombre_imagen = uniqid('img_') . '.' . $extension;

            // Usar la ruta correcta basada en el directorio raíz del servidor
            $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . '/modaurbana/assets/img/' . $nuevo_nombre_imagen;

            // Mover la imagen a la carpeta 'img'
            if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
                // Eliminar la imagen anterior si existe
                if ($imagen && file_exists($_SERVER['DOCUMENT_ROOT'] . '/modaurbana/assets/img/' . $imagen)) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/modaurbana/assets/img/' . $imagen);
                }
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
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, tallas, colores, stock, categoria ) 
                VALUES ('$nombre', '$descripcion', '$precio', '$imagen', '$tallas', '$colores', '$stock', '$categoria' )";

        if (mysqli_query($conexion, $sql)) {
            // Redirigir al listado de productos
            header('Location: /modaurbana/admin/index.php');
            exit();
        } else {
            $error = "Error al agregar el producto: " . mysqli_error($conexion);
        }
    }
}
?>

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
            <label>Tallas Disponibles (separadas por comas)</label>
            <input type="text" name="tallas" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Colores Disponibles (separados por comas)</label>
            <input type="text" name="colores" class="form-control" required>
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
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" required>
        </div>

        <!-- Botones -->
        <button type="submit" class="btn btn-success">Agregar</button>
        <a href="/modaurbana/admin/productos/index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include '../../includes/templates/footer.php'; ?>