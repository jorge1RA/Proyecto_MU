<?php
//mis_pedidos.php

include '../../includes/conexion.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Preparar la consulta para obtener los pedidos del usuario
$stmt_pedidos = $conexion->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC");
$stmt_pedidos->bind_param("i", $usuario_id);
$stmt_pedidos->execute();
$resultado_pedidos = $stmt_pedidos->get_result();
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Mis Pedidos</h2>
    <?php if ($resultado_pedidos->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Número de Pedido</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Método de Pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $resultado_pedidos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                        <td><?php echo number_format($pedido['total'], 2); ?>€</td>
                        <td><?php echo ucfirst(htmlspecialchars($pedido['metodo_pago'])); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($pedido['estado'])); ?></td>
                        <td>
                            <a href="/modaurbana/admin/pedidos/ver_pedido.php?id=<?php echo urlencode($pedido['id']); ?>" class="btn btn-primary btn-sm">Ver Detalles</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No has realizado ningún pedido.</p>
    <?php endif; ?>
</div>
<?php include '../../includes/templates/footer.php'; ?>