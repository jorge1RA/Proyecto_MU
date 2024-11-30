<?php
// carrito.php

session_start();
include '../../includes/conexion.php'
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Mi Carrito</h2>

    <!-- Mostrar Mensajes de Sesión -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

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
                foreach ($_SESSION['carrito'] as $clave => $producto):
                    // Manejar valores inexistentes para talla, color y stock
                    $talla = isset($producto['talla']) ? $producto['talla'] : 'N/A';
                    $color = isset($producto['color']) ? $producto['color'] : 'N/A';
                    $stock = isset($producto['stock']) ? intval($producto['stock']) : 0;

                    $subtotal = $producto['precio'] * $producto['cantidad'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td>
                            <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="50">
                        </td>
                        <td><?php echo number_format($producto['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($talla); ?></td>
                        <td><?php echo htmlspecialchars($color); ?></td>
                        <td>
                            <form method="post" action="/modaurbana/pages/cart/actualizar_carrito.php">
                                <input type="hidden" name="producto_id" value="<?php echo intval($producto['id']); ?>">
                                <?php if ($talla !== 'N/A'): ?>
                                    <input type="hidden" name="talla" value="<?php echo htmlspecialchars($talla); ?>">
                                <?php else: ?>
                                    <input type="hidden" name="talla" value="">
                                <?php endif; ?>
                                <?php if ($color !== 'N/A'): ?>
                                    <input type="hidden" name="color" value="<?php echo htmlspecialchars($color); ?>">
                                <?php else: ?>
                                    <input type="hidden" name="color" value="">
                                <?php endif; ?>
                                <input type="number" name="cantidad" value="<?php echo intval($producto['cantidad']); ?>" min="1" max="<?php echo $stock > 0 ? $stock : 1; ?>" class="form-control" style="width: 80px;" required>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Actualizar</button>
                            </form>
                        </td>
                        <td><?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="post" action="/modaurbana/pages/cart/eliminar_del_carrito.php">
                                <input type="hidden" name="id" value="<?php echo intval($producto['id']); ?>">
                                <?php if ($talla !== 'N/A'): ?>
                                    <input type="hidden" name="talla" value="<?php echo htmlspecialchars($talla); ?>">
                                <?php else: ?>
                                    <input type="hidden" name="talla" value="">
                                <?php endif; ?>
                                <?php if ($color !== 'N/A'): ?>
                                    <input type="hidden" name="color" value="<?php echo htmlspecialchars($color); ?>">
                                <?php else: ?>
                                    <input type="hidden" name="color" value="">
                                <?php endif; ?>
                                <input type="hidden" name="clave" value="<?php echo htmlspecialchars($clave); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="6" class="text-right"><strong>Total:</strong></td>
                    <td colspan="2"><strong><?php echo number_format($total, 2); ?></strong>€</td>
                </tr>
            </tbody>
        </table>
        <a href="/modaurbana/pages/products/tienda.php" class="btn btn-secondary">Seguir Comprando</a>
        <a href="/modaurbana/pages/cart/checkout.php" class="btn btn-success">Proceder al Pago</a>
    <?php else: ?>
        <p>Tu carrito está vacío.</p>
        <a href="/modaurbana/pages/products/tienda.php" class="btn btn-primary">Ir a la Tienda</a>
    <?php endif; ?>
</div>
<?php include '../../includes/templates/footer.php'; ?>