<?php
//editar.php


include '../../includes/conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location:/modaurbana/admin/productos/index.php');
    exit();
}

// Obtener el ID del producto a editar
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Obtener los datos del producto
    $sql = "SELECT * FROM productos WHERE id='$producto_id'";
    $resultado = mysqli_query($conexion, $sql);
    $producto = mysqli_fetch_assoc($resultado);

    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    header('Location: /modaurbana/admin/productos/index.php');
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

    $imagen = $producto['imagen']; // Imagen actual

    // Manejo de imagen (si se sube una nueva)
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
            $ruta_destino = '/modaurbana/assets/img/' . $nuevo_nombre_imagen;

            // Mover la imagen a la carpeta 'img'
            if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
                // Eliminar la imagen anterior si existe
                if ($imagen && file_exists('/modaurbana/assets/img/' . $imagen)) {
                    unlink('/modaurbana/assets/img/' . $imagen);
                }
                $imagen = $nuevo_nombre_imagen;
            } else {
                $error = "Error al subir la imagen.";
            }
        } else {
            $error = "Formato de imagen no permitido. Solo se aceptan JPG, JPEG, PNG y GIF.";
        }
    }

    if (!isset($error)) {
        // Actualizar el producto en la base de datos
        $sql = "UPDATE productos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', imagen='$imagen', 
                tallas='$tallas', colores='$colores', stock='$stock', categoria='$categoria' WHERE id='$producto_id'";

        if (mysqli_query($conexion, $sql)) {
            // Redirigir al listado de productos
            header('Location: /modaurbana/admin/productos/index.php');
            exit();
        } else {
            $error = "Error al actualizar el producto: " . mysqli_error($conexion);
        }
    }
}
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Editar Producto</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="/modaurbana/admin/productos/editar.php?id=<?php echo $producto_id; ?>" enctype="multipart/form-data">
        <!-- Campos del formulario -->
        <div class="form-group">
            <label>Nombre del Producto</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="5" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
        </div>
        <div class="form-group">
            <label>Tallas Disponibles (separadas por comas)</label>
            <input type="text" name="tallas" class="form-control" value="<?php echo htmlspecialchars($producto['tallas']); ?>">
        </div>
        <div class="form-group">
            <label>Colores Disponibles (separados por comas)</label>
            <input type="text" name="colores" class="form-control" value="<?php echo htmlspecialchars($producto['colores']); ?>">
        </div>
        <div class="form-group">
            <label>Precio (€)</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
        </div>
        <div class="form-group">
            <label>Imagen Actual</label><br>
            <?php if ($producto['imagen'] && file_exists('/modaurbana/assets/img/' . $producto['imagen'])): ?>
                <img src="/modaurbana/assets/img/<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="150">
            <?php else: ?>
                <p>No hay imagen</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Cambiar Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control-file">
        </div>
        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($producto['stock']); ?>" required>
        </div>
        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>
        </div>

        <!-- Botones -->
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="/modaurbana/admin/productos/index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include '../../includes/templates/footer.php'; ?>