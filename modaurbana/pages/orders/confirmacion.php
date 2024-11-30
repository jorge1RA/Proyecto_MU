<?php
//confirmacion.php


session_start();
include '../../includes/conexion.php';

if (!isset($_GET['pedido_id'])) {
    header('Location: index.php');
    exit();
}

$pedido_id = $_GET['pedido_id'];

// Obteniene información del pedido
$sql_pedido = "SELECT * FROM pedidos WHERE id='$pedido_id'";
$resultado_pedido = mysqli_query($conexion, $sql_pedido);
$pedido = mysqli_fetch_assoc($resultado_pedido);

// Obtiene los detalles del pedido
$sql_detalles = "SELECT dp.*, p.nombre FROM detalle_pedidos dp INNER JOIN productos p ON dp.producto_id = p.id WHERE dp.pedido_id='$pedido_id'";
$resultado_detalles = mysqli_query($conexion, $sql_detalles);
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Pedido Realizado</h2>
    <p>Gracias por tu compra. Tu pedido ha sido procesado con éxito.</p>
    <h4>Detalles del Pedido</h4>
    <p><strong>Número de Pedido:</strong> <?php echo $pedido['id']; ?></p>
    <p><strong>Fecha:</strong> <?php echo $pedido['fecha_pedido']; ?></p>
    <p><strong>Total:</strong> €<?php echo number_format($pedido['total'], 2); ?></p>
    <p><strong>Método de Pago:</strong> <?php echo ucfirst($pedido['metodo_pago']); ?></p>
    <p><strong>Dirección de Envío:</strong> <?php echo $pedido['direccion_envio']; ?></p>
    <h5>Productos:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario (€)</th>
                <th>Subtotal (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($detalle = mysqli_fetch_assoc($resultado_detalles)): ?>
                <tr>
                    <td><?php echo $detalle['nombre']; ?></td>
                    <td><?php echo $detalle['cantidad']; ?></td>
                    <td><?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                    <td><?php echo number_format($detalle['subtotal'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-primary">Volver a la Tienda</a>
</div>
<?php include '../../includes/templates/footer.php'; ?>