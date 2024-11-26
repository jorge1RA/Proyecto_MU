<?php

/**
 * crear_productos.php
 *
 * Página que permite al administrador crear un nuevo producto en la tienda.
 *
 * Permite agregar la información básica del producto, la imagen y variantes de tallas, colores y stock.
 *
 * @category Administración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 */

/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Verifica si el usuario es administrador.
 * Si no está autenticado como administrador, redirige a la página principal de administración.
 */
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/admin/index.php');
    exit();
}


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Procesa el formulario al enviarse mediante el método POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /**
     * Obtiene y sanitiza los datos enviados por el formulario.
     */
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $imagen = '';


    /**
     * Maneja la subida de la imagen del producto.
     */
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $extension = pathinfo($nombre_imagen, PATHINFO_EXTENSION);
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];


        /**
         * Verifica si la extensión de la imagen está permitida.
         */
        if (in_array(strtolower($extension), $extensiones_permitidas)) {
            $nuevo_nombre_imagen = uniqid('img_') . '.' . $extension;
            $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . '/modaurbana/assets/img/' . $nuevo_nombre_imagen;


            /**
             * Mueve el archivo subido a la ubicación de destino.
             */
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


    /**
     * Si no hay errores, inserta el producto en la base de datos.
     */
    if (!isset($error)) {
        /**
         *  Insertar el producto en la base de datos
         */
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, categoria) 
                VALUES ('$nombre', '$descripcion', '$precio', '$imagen', '$categoria')";

        if (mysqli_query($conexion, $sql)) {
            $producto_id = mysqli_insert_id($conexion);

            /**
             * Inserta las variantes del producto (tallas, colores, stock).
             */
            $tallas = $_POST['tallas'];
            $colores = $_POST['colores'];
            $stocks = $_POST['stocks'];

            /**
             * Itera sobre cada variante (talla, color, stock) proporcionada y la inserta en la base de datos.
             */
            for ($i = 0; $i < count($tallas); $i++) {

                /**
                 * Sanitiza la talla y el color, y convierte el stock a un entero
                 */
                $talla = mysqli_real_escape_string($conexion, $tallas[$i]);
                $color = mysqli_real_escape_string($conexion, $colores[$i]);
                $stock = intval($stocks[$i]);

                /**
                 * Inserta la variante en la base de datos
                 */
                $sql_variante = "INSERT INTO producto_variantes (producto_id, talla, color, stock) 
                     VALUES ('$producto_id', '$talla', '$color', '$stock')";
                mysqli_query($conexion, $sql_variante);
            }

            /**
             * Redirige al usuario si el producto se agrega correctamente, o muestra un error si falla.
             */
            header('Location: /modaurbana/admin/index.php');
            exit();
        } else {
            $error = "Error al agregar el producto: " . mysqli_error($conexion);
        }
    }
}

/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!-- 
Contenedor principal con margen superior. 
-->
<div class="container mt-4">
    <h2>Agregar Producto</h2>

    <!-- 
    Muestra un mensaje de error si se produjo alguno. 
    -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- 
    Formulario para agregar un producto. 
    -->
    <form method="POST" action="/modaurbana/admin/productos/crear.php" enctype="multipart/form-data">
        <!-- 
        Campos del formulario, para la información del producto. 
        -->
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

        <!-- 
        Sección para agregar variantes del producto. 
        -->
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
    /**
     * Añade un nuevo bloque de variantes (talla, color, stock).
     */
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

    /**
     * Elimina una variante de producto.
     */
    function removeVariante(button) {
        button.parentElement.remove();
    }
</script>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>