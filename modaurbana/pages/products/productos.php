<?php

/**
 * productos.php
 * 
 * Página para mostrar los detalles de un producto específico.
 * Permite al usuario seleccionar variantes y agregar el producto al carrito.
 * 
 * @category Productos
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Verifica si se ha pasado un id de producto.
 */
if (isset($_GET['id']) && !empty($_GET['id'])) {

    /**
     *  Convierte a entero para mayor seguridad.
     */
    $producto_id = intval($_GET['id']);

    /**
     * Utiliza una sentencia preparada para prevenir inyecciones SQL.
     */
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();

    /**
     * Verifica si el producto existe.
     */
    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    /**
     * Mensaje de error si no se especifica un producto.
     */
    echo "Producto no especificado.";
    exit();
}


/**
 * Inicializa variables de mensaje.
 */
$error = '';
$success = '';

/**
 * Obteniene las variantes del producto antes de procesar el formulario
 */
$hay_variantes = false;
$sql_variantes = "SELECT * FROM producto_variantes WHERE producto_id = ?";
$stmt_variantes = $conexion->prepare($sql_variantes);
$stmt_variantes->bind_param("i", $producto_id);
$stmt_variantes->execute();
$resultado_variantes = $stmt_variantes->get_result();

$variantes = [];
if ($resultado_variantes->num_rows > 0) {
    $hay_variantes = true;

    /**
     * Almacena las variantes en un array para usarlas después.
     */
    while ($variante = $resultado_variantes->fetch_assoc()) {
        $variantes[$variante['id']] = $variante;
    }
}

/**
 * Procesa el formulario de agregar al carrito.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /**
     * Obtiene y sanitiza los datos del formulario.
     */
    $variante_id = isset($_POST['variante_id']) ? intval($_POST['variante_id']) : null;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    /**
     * Validaciones de los datos del formulario.
     */
    if ($hay_variantes && ($variante_id === null || $variante_id <= 0)) {
        $error = "Por favor, selecciona una variante.";
    } elseif ($cantidad < 1) {
        $error = "La cantidad debe ser al menos 1.";
    } else {
        /**
         * Inicializa el carrito si no existe.
         */
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        /**
         * Genera una clave única para identificar el producto o variante en el carrito.
         */
        if ($hay_variantes) {
            $clave_carrito = 'variante_' . $variante_id;

            /**
             * Verifica que la variante seleccionada existe en el array $variantes
             */
            if (isset($variantes[$variante_id])) {
                $variante_seleccionada = $variantes[$variante_id];
                $talla = $variante_seleccionada['talla'];
                $color = $variante_seleccionada['color'];
                $stock = $variante_seleccionada['stock'];
            } else {
                $error = "La variante seleccionada no es válida.";
            }
        } else {
            $clave_carrito = 'producto_' . $producto['id'];
            $talla = null;
            $color = null;
            $stock = null;
        }

        /**
         * Añade el producto al carrito si no hay errores.
         */
        if (!$error) {

            /**
             * Si el producto ya está en el carrito, actualiza la cantidad.
             */
            if (isset($_SESSION['carrito'][$clave_carrito])) {
                $_SESSION['carrito'][$clave_carrito]['cantidad'] += $cantidad;
            } else {

                /**
                 * Añade el producto al carrito con los datos correspondientes.
                 */
                $_SESSION['carrito'][$clave_carrito] = [
                    'producto_id' => $producto['id'],
                    'variante_id' => $variante_id,
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'cantidad' => $cantidad,
                    'imagen' => $producto['imagen'],
                    'talla' => $talla,
                    'color' => $color,
                    'stock' => $stock
                ];
            }
            $success = "Producto añadido al carrito.";
        }
    }
}

/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!--  
Contenedor Principal.
-->
<div class="container mt-4">

    <!-- 
    Mensajes de éxito o error. 
    -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>


    <div class="row">
        <div class="col-md-6">

            <!-- 
            Muestra la imagen del producto. 
            -->
            <?php if ($producto['imagen']): ?>
                <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
            <?php else: ?>
                <img src="/modaurbana/assets/img/" class="img-fluid" alt="Producto">
            <?php endif; ?>

        </div>
        <div class="col-md-6">

            <!-- 
            Información del producto. 
            -->
            <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
            <p><strong>Precio:</strong> <?php echo number_format($producto['precio'], 2); ?>€</p>

            <!-- 
            Formulario para agregar el producto al carrito. 
            -->
            <form action="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>" method="POST">
                <?php if ($hay_variantes): ?>

                    <!-- 
                    Muestra las variantes disponibles del producto. 
                    -->
                    <div class="form-group">
                        <label>Variantes Disponibles</label>
                        <select name="variante_id" class="form-control" required>
                            <option value="">Seleccione una variante</option>
                            <?php foreach ($variantes as $variante): ?>
                                <option value="<?php echo $variante['id']; ?>">
                                    <?php echo htmlspecialchars($variante['talla'] . ' - ' . $variante['color']) . " (Stock: " . intval($variante['stock']) . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                <?php else: ?>
                    <p><strong>Este producto no tiene variantes disponibles.</strong></p>
                    <input type="hidden" name="variante_id" value="0">
                <?php endif; ?>

                <!-- 
                Selección de cantidad. 
                -->
                <div class="form-group">
                    <label>Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" value="1" min="1" required>
                </div>

                <!-- 
                Botones de acción. 
                -->
                <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                <a href="/modaurbana/pages/products/tienda.php" class="btn btn-secondary">Volver a la Tienda</a>
            </form>

        </div>
    </div>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>