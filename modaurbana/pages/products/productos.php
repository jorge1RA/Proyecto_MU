<?php
//productos.php


include '../../includes/conexion.php';
session_start();

// Verifica si se ha pasado un ID de producto
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $producto_id = intval($_GET['id']); // Convierte a entero para mayor seguridad

    // Utiliza una sentencia preparada para prevenir inyecciones SQL
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();

    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "Producto no especificado.";
    exit();
}

// Inicializa variables de mensaje
$error = '';
$success = '';

// Maneja la adición al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y sanitizar los datos del formulario
    $talla = isset($_POST['talla']) ? trim($_POST['talla']) : '';
    $color = isset($_POST['color']) ? trim($_POST['color']) : '';
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    // Validar los datos
    if (!empty($producto['tallas']) && empty($talla)) {
        $error = "Por favor, selecciona una talla.";
    } elseif (!empty($producto['colores']) && empty($color)) {
        $error = "Por favor, selecciona un color.";
    } elseif ($cantidad < 1) {
        $error = "La cantidad debe ser al menos 1.";
    } elseif ($cantidad > intval($producto['stock'])) {
        $error = "La cantidad solicitada excede el stock disponible.";
    } else {
        // Crea una clave única para variantes de producto (talla y color)
        $clave = $producto_id;

        // Comprueba si ya existe en el carrito
        if (isset($_SESSION['carrito'][$clave])) {
            $nueva_cantidad = $_SESSION['carrito'][$clave]['cantidad'] + $cantidad;
            if ($nueva_cantidad > intval($producto['stock'])) {
                $error = "La cantidad total en el carrito excede el stock disponible.";
            } else {
                $_SESSION['carrito'][$clave]['cantidad'] = $nueva_cantidad;
                $success = "Cantidad actualizada en el carrito.";
            }
        } else {
            // Añade el producto al carrito
            $_SESSION['carrito'][$clave] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'talla' => !empty($talla) ? $talla : 'N/A',
                'color' => !empty($color) ? $color : 'N/A',
                'cantidad' => $cantidad,
                'imagen' => $producto['imagen'],
                'stock' => $producto['stock']
            ];
            $success = "Producto añadido al carrito.";
        }
    }
}

include '../../includes/templates/header.php';
?>
<div class="container mt-4">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <?php if ($producto['imagen']): ?>
                <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
            <?php else: ?>
                <img src="/modaurbana/assets/img/" class="img-fluid" alt="Producto">
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
            <p><strong>Precio:</strong> <?php echo number_format($producto['precio'], 2); ?>€</p>
            <p><strong>Stock disponible:</strong> <?php echo intval($producto['stock']); ?></p>
            <form action="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>" method="POST">
                <!-- Selección de talla -->
                <?php if (!empty($producto['tallas'])): ?>
                    <div class="form-group">
                        <label>Talla</label>
                        <select name="talla" class="form-control" required>
                            <option value="">Selecciona una talla</option>
                            <?php
                            $tallas = explode(',', $producto['tallas']);
                            foreach ($tallas as $talla_option):
                                $talla_trim = trim($talla_option);
                            ?>
                                <option value="<?php echo htmlspecialchars($talla_trim); ?>"><?php echo htmlspecialchars($talla_trim); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <!-- Selección de color -->
                <?php if (!empty($producto['colores'])): ?>
                    <div class="form-group">
                        <label>Color</label>
                        <select name="color" class="form-control" required>
                            <option value="">Selecciona un color</option>
                            <?php
                            $colores = explode(',', $producto['colores']);
                            foreach ($colores as $color_option):
                                $color_trim = trim($color_option);
                            ?>
                                <option value="<?php echo htmlspecialchars($color_trim); ?>"><?php echo htmlspecialchars($color_trim); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <!-- Selección de cantidad -->
                <div class="form-group">
                    <label>Cantidad</label>
                    <input type="number" name="cantidad" class="form-control" value="1" min="1" max="<?php echo intval($producto['stock']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                <a href="/modaurbana/pages/products/tienda.php" class="btn btn-secondary">Volver a la Tienda</a>
            </form>
        </div>
    </div>
</div>
<?php include '../../includes/templates/footer.php'; ?>