<?php
//index.php - pedidos


include '../../includes/conexion.php';
include '../../includes/templates/header.php';


// Obtener todos los pedidos
$sql_pedidos = "SELECT p.*, u.nombre AS nombre_usuario FROM pedidos p
                INNER JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY p.fecha_pedido DESC";
$resultado_pedidos = mysqli_query($conexion, $sql_pedidos);
?>
<br>
<br>
<h2>Gestión de Pedidos</h2>
<br>
<?php if (mysqli_num_rows($resultado_pedidos) > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Número de Pedido</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Total</th>
                <th>Método de Pago</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pedido = mysqli_fetch_assoc($resultado_pedidos)): ?>
                <tr>
                    <td><?php echo $pedido['id']; ?></td>
                    <td><?php echo $pedido['fecha_pedido']; ?></td>
                    <td><?php echo $pedido['nombre_usuario']; ?></td>
                    <td><?php echo number_format($pedido['total'], 2); ?>€</td>
                    <td><?php echo ucfirst($pedido['metodo_pago']); ?></td>
                    <td><?php echo ucfirst($pedido['estado']); ?></td>
                    <td>
                        <a href="/modaurbana/admin/pedidos/ver_pedido.php?id=<?php echo $pedido['id']; ?>" class="btn btn-primary btn-sm">Ver Detalles</a>
                        <a href="/modaurbana/admin/pedidos/editar_estado.php?id=<?php echo $pedido['id']; ?>" class="btn btn-warning btn-sm">Editar Estado</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay pedidos registrados.</p>
<?php endif; ?>

<?php include '../../includes/templates/footer.php'; ?>