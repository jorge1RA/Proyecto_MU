<?php
session_start();

// Verificar si el usuario es administrador - Pendiente de probar funcionamiento y habilitar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/admin/index.php');
    exit();
}

// Incluir la conexión a la base de datos
include_once '../../includes/conexion.php';

// Procesa el formulario al ser enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $imagen = '';

    // Manejar la subida de la imagen 
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/modaurbana/assets/img/' . $imagen);
    } else {
        $error = "Debe seleccionar una imagen.";
    }


    // Insertar el producto en la base de datos
    if (!isset($error)) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, categoria) 
                VALUES ('$nombre', '$descripcion', '$precio', '$imagen', '$categoria')";

        if (mysqli_query($conexion, $sql)) {
            header('Location: /modaurbana/admin/index.php');
            exit();
        } else {
            $error = "Error al agregar el producto.";
        }
    }
}

include_once '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2>Agregar Producto</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="/modaurbana/admin/productos/crear.php" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nombre del Producto</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label>Precio</label>
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
        <button type="submit" class="btn btn-success">Agregar Producto</button>
        <a href="/modaurbana/admin/productos/index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include_once '../../includes/templates/footer.php'; ?>