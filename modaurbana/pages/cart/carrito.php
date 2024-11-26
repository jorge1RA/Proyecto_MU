<?php

/**
 * carrito.php
 * 
 * Página del carrito de compras.
 *
 * Muestra los productos añadidos al carrito, permitiendo actualizar cantidades o eliminar productos.
 *
 * @category Carrito
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */

/**
 * Iniciar la sesión para acceder a los datos del carrito.
 */
session_start();


/**
 * Incluir la conexión a la base de datos.
 */
include '../../includes/conexion.php';


/**
 * Incluir el encabezado de la página.
 */
include '../../includes/templates/header.php';
?>


<!--  
Contenedor Principal.
-->
<div class="container mt-4">
    <h2>Mi Carrito</h2>

    <!-- 
    Mostrar mensajes de error o éxito almacenados en la sesión. 
    -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- 
    Verificar si hay productos en el carrito. 
    -->
    <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>

        <table class="table table-bordered">
            <thead>

                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Precio (€)</th>
                    <th>Talla</th>
                    <th>Color</th>
                    <th>Cantidad</th>
                    <th>Subtotal (€)</th>
                    <th>Acciones</th>
                </tr>

            </thead>
            <tbody>
                <?php
                $total = 0;

                /**
                 * Iterar sobre cada producto en el carrito.
                 */
                foreach ($_SESSION['carrito'] as $clave => $producto):
                    /**
                     * Obtiene la talla, color y el stock del producto.
                     */
                    $talla = isset($producto['talla']) ? htmlspecialchars($producto['talla']) : 'N/A';
                    $color = isset($producto['color']) ? htmlspecialchars($producto['color']) : 'N/A';
                    $stock = isset($producto['stock']) ? intval($producto['stock']) : 0;

                    $cantidad = isset($producto['cantidad']) ? intval($producto['cantidad']) : 1;
                    $precio = isset($producto['precio']) ? floatval($producto['precio']) : 0.0;

                    $subtotal = $precio * $cantidad;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td>
                            <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="50">
                        </td>
                        <td><?php echo number_format($precio, 2); ?></td>
                        <td><?php echo $talla; ?></td>
                        <td><?php echo $color; ?></td>
                        <td>
                            <!-- 
                            Formulario para actualizar la cantidad de un producto. 
                            -->
                            <form method="post" action="/modaurbana/pages/cart/actualizar_carrito.php">
                                <input type="hidden" name="clave" value="<?php echo htmlspecialchars($clave); ?>">
                                <input type="number" name="cantidad" value="<?php echo $cantidad; ?>" min="1" max="<?php echo $stock > 0 ? $stock : 1; ?>" class="form-control" style="width: 80px;" required>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Actualizar</button>
                            </form>

                        </td>
                        <td><?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <!-- 
                            Formulario para eliminar un producto del carrito. 
                            -->
                            <form method="post" action="/modaurbana/pages/cart/eliminar_del_carrito.php">
                                <input type="hidden" name="clave" value="<?php echo htmlspecialchars($clave); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                        </td>
                    </tr>

                <?php endforeach; ?>

                <!-- 
                Mostrar el total del carrito. 
                -->
                <tr>
                    <td colspan="6" class="text-right"><strong>Total:</strong></td>
                    <td colspan="2"><strong><?php echo number_format($total, 2); ?></strong>€</td>
                </tr>

            </tbody>
        </table>

        <a href="/modaurbana/pages/products/tienda.php" class="btn btn-secondary">Seguir Comprando</a>
        <a href="/modaurbana/pages/cart/checkout.php" class="btn btn-success">Proceder al Pago</a>

    <?php else: ?>
        <!-- 
        Mostrar mensaje si el carrito está vacío. 
        -->
        <p>Tu carrito está vacío.</p>
        <a href="/modaurbana/pages/products/tienda.php" class="btn btn-primary">Ir a la Tienda</a>

    <?php endif; ?>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include '../../includes/templates/footer.php'; ?>