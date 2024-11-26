<?php
// editar.php

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
    }
    if (!isset($error)) {
        // Actualizar el producto en la base de datos
        $sql = "UPDATE productos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', imagen='$imagen', categoria='$categoria' WHERE id='$producto_id'";

        if (mysqli_query($conexion, $sql)) {
            // Actualizar variantes existentes
            if (isset($_POST['variantes_existentes'])) {
                foreach ($_POST['variantes_existentes'] as $variante_id => $variante_data) {
                    $color = mysqli_real_escape_string($conexion, $variante_data['color']);
                    $talla = mysqli_real_escape_string($conexion, $variante_data['talla']);
                    $stock = mysqli_real_escape_string($conexion, $variante_data['stock']);

                    // Actualizar la variante existente
                    $sql_variante = "UPDATE producto_variantes SET color='$color', talla='$talla', stock='$stock' WHERE id='$variante_id' AND producto_id='$producto_id'";
                    mysqli_query($conexion, $sql_variante);
                }
            }

            // Procesar nuevas variantes
            if (isset($_POST['variantes'])) {
                foreach ($_POST['variantes'] as $variante) {
                    $color = mysqli_real_escape_string($conexion, $variante['color']);
                    $talla = mysqli_real_escape_string($conexion, $variante['talla']);
                    $stock = mysqli_real_escape_string($conexion, $variante['stock']);

                    // Verificar que los campos no estén vacíos
                    if (!empty($color) && !empty($talla) && $stock !== '') {
                        // Insertar la nueva variante
                        $sql_variante = "INSERT INTO producto_variantes (producto_id, color, talla, stock) VALUES ('$producto_id', '$color', '$talla', '$stock')";
                        mysqli_query($conexion, $sql_variante);
                    }
                }
            }
            // Redirigir al listado de productos
            header('Location: /modaurbana/admin/productos/index.php');
            exit();
        } else {
            $error = "Error al actualizar el producto: " . mysqli_error($conexion);
        }
    }
}

// Obtener las variantes existentes del producto
$sql_variantes = "SELECT * FROM producto_variantes WHERE producto_id = '$producto_id'";
$resultado_variantes = mysqli_query($conexion, $sql_variantes);
$variantes = [];
if ($resultado_variantes && mysqli_num_rows($resultado_variantes) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado_variantes)) {
        $variantes[] = $fila;
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
            <label>Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
        </div>
        <div class="form-group">
            <label>Imagen Actual</label><br>
            <?php if ($producto['imagen'] && file_exists($_SERVER['DOCUMENT_ROOT'] . '/modaurbana/assets/img/' . $producto['imagen'])): ?>
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
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>
        </div>

        <!-- Variantes existentes -->
        <h4>Variantes Existentes</h4>
        <?php if (!empty($variantes)): ?>
            <?php foreach ($variantes as $variante): ?>
                <div class="form-group border p-3 mb-3">
                    <label>Color</label>
                    <input type="text" name="variantes_existentes[<?php echo $variante['id']; ?>][color]" class="form-control" value="<?php echo htmlspecialchars($variante['color']); ?>">
                    <label>Talla</label>
                    <input type="text" name="variantes_existentes[<?php echo $variante['id']; ?>][talla]" class="form-control" value="<?php echo htmlspecialchars($variante['talla']); ?>">
                    <label>Stock</label>
                    <input type="number" name="variantes_existentes[<?php echo $variante['id']; ?>][stock]" class="form-control" value="<?php echo htmlspecialchars($variante['stock']); ?>">
                    <!-- Botón para eliminar variante existente -->
                    <a href="/modaurbana/admin/productos/eliminar_variante.php?id=<?php echo $variante['id']; ?>" class="btn btn-danger btn-sm mt-2" onclick="return confirm('¿Estás seguro de eliminar esta variante?');">Eliminar Variante</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay variantes disponibles.</p>
        <?php endif; ?>

        <!-- Agregar nuevas variantes -->
        <h4>Agregar Nuevas Variantes</h4>
        <div id="variantes-container">
            <!-- Sin variante inicial -->
        </div>
        <button type="button" id="agregar-variante" class="btn btn-secondary mb-3">Agregar Variante</button>
        <br>

        <!-- Botones -->
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="/modaurbana/admin/productos/index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    document.getElementById('agregar-variante').addEventListener('click', function() {
        const container = document.getElementById('variantes-container');
        const index = container.children.length;
        const newVariante = document.createElement('div');
        newVariante.classList.add('variante', 'form-group', 'border', 'p-3', 'mb-3');
        newVariante.innerHTML = `
            <label>Color</label>
            <input type="text" name="variantes[${index}][color]" class="form-control" required>
            <label>Talla</label>
            <input type="text" name="variantes[${index}][talla]" class="form-control" required>
            <label>Stock</label>
            <input type="number" name="variantes[${index}][stock]" class="form-control" required>
            <button type="button" class="btn btn-danger btn-sm mt-2 eliminar-variante">Eliminar Variante</button>
        `;
        container.appendChild(newVariante);

        // Agregar evento para eliminar variante
        newVariante.querySelector('.eliminar-variante').addEventListener('click', function() {
            container.removeChild(newVariante);
        });
    });
</script>

<?php include '../../includes/templates/footer.php'; ?>