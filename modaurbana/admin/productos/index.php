<?php
//index.php - productos


session_start();
include '../../includes/conexion.php';


if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/admin/index.php');
    exit();
}

// Obtener los productos
$sql = "SELECT * FROM productos";
$resultado = mysqli_query($conexion, $sql);
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Gestión de Productos</h2>
    <a href="/modaurbana/admin/productos/crear.php" class="btn btn-success mb-3">Agregar Producto</a>
    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody>
                <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $producto['id']; ?></td>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo number_format($producto['precio'], 2); ?>€</td>
                        <td><?php echo $producto['stock']; ?></td>
                        <td>
                            <a href="/modaurbana/admin/productos/editar.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="/modaurbana/admin/productos/eliminar.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay productos disponibles.</p>
    <?php endif; ?>
</div>
<?php include '../../includes/templates/footer.php'; ?>