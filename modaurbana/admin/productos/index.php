<?php
// index.php - gestión de productos

session_start();
include '../../includes/conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

// Obtener los productos
$sql = "SELECT * FROM productos";
$resultado_productos = mysqli_query($conexion, $sql);
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Gestión de Productos</h2>
    <a href="/modaurbana/admin/productos/crear.php" class="btn btn-success mb-3">Agregar Producto</a>
    <?php if (mysqli_num_rows($resultado_productos) > 0): ?>
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Color</th>
                    <th>Talla</th>
                    <th>Stock</th>
                    <th>Acciones Producto</th>
                    <th>Acciones Variante</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = mysqli_fetch_assoc($resultado_productos)): ?>
                    <?php
                    // Obtener las variantes del producto
                    $producto_id = $producto['id'];
                    $sql_variantes = "SELECT * FROM producto_variantes WHERE producto_id = ?";
                    $stmt_variantes = $conexion->prepare($sql_variantes);
                    $stmt_variantes->bind_param("i", $producto_id);
                    $stmt_variantes->execute();
                    $resultado_variantes = $stmt_variantes->get_result();
                    $num_variantes = $resultado_variantes->num_rows;
                    ?>
                    <?php if ($num_variantes > 0): ?>
                        <?php $primera_fila = true; ?>
                        <?php while ($variante = $resultado_variantes->fetch_assoc()): ?>
                            <tr>
                                <?php if ($primera_fila): ?>
                                    <td rowspan="<?php echo $num_variantes; ?>" class="align-middle"><?php echo htmlspecialchars($producto['id']); ?></td>
                                    <td rowspan="<?php echo $num_variantes; ?>" class="align-middle"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td rowspan="<?php echo $num_variantes; ?>" class="align-middle"><?php echo number_format($producto['precio'], 2); ?>€</td>
                                <?php endif; ?>
                                <td><?php echo htmlspecialchars($variante['color']); ?></td>
                                <td><?php echo htmlspecialchars($variante['talla']); ?></td>
                                <td><?php echo htmlspecialchars($variante['stock']); ?></td>
                                <?php if ($primera_fila): ?>
                                    <td rowspan="<?php echo $num_variantes; ?>" class="align-middle text-center">
                                        <a href="/modaurbana/admin/productos/editar.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary btn-sm mb-1">Editar Producto</a><br>
                                        <a href="/modaurbana/admin/productos/eliminar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto y todas sus variantes?');">Eliminar Producto</a>
                                    </td>
                                <?php endif; ?>
                                <td class="text-center">
                                    <a href="/modaurbana/admin/productos/eliminar_variante.php?id=<?php echo $variante['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta variante?');">Eliminar Variante</a>
                                </td>
                            </tr>
                            <?php $primera_fila = false; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['id']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo number_format($producto['precio'], 2); ?>€</td>
                            <td colspan="3" class="text-center">No hay variantes disponibles.</td>
                            <td class="text-center">
                                <a href="/modaurbana/admin/productos/editar.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary btn-sm mb-1">Editar Producto</a><br>
                                <a href="/modaurbana/admin/productos/eliminar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar Producto</a>
                            </td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay productos disponibles.</p>
    <?php endif; ?>
</div>
<?php include '../../includes/templates/footer.php'; ?>