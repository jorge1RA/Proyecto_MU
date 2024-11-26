<?php
// ver_pedido.php

// Habilita la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configura MySQLi para lanzar excepciones en caso de error
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include '../../includes/conexion.php';
session_start();

try {
    // Verifica si el usuario ha iniciado sesión
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: /modaurbana/pages/login/login.php');
        exit();
    }

    // Verifica si se ha proporcionado el ID del pedido y que no esté vacío
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header('Location: /modaurbana/admin/pedidos/mis_pedidos.php');
        exit();
    }

    $pedido_id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verificación de que el pedido_id es numérico
    if (!is_numeric($pedido_id)) {
        echo "ID de pedido inválido.";
        exit();
    }

    // Prepara la consulta para obtener el pedido y los datos del usuario
    $stmt_pedido = $conexion->prepare("
        SELECT p.*, u.nombre AS nombre_usuario, u.apellidos, u.email 
        FROM pedidos p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ? AND p.usuario_id = ?
    ");

    if (!$stmt_pedido) {
        echo "Error al preparar la consulta: " . $conexion->error;
        exit();
    }

    $stmt_pedido->bind_param("ii", $pedido_id, $usuario_id);
    $stmt_pedido->execute();
    $resultado_pedido = $stmt_pedido->get_result();

    // Verifica si se encontró el pedido
    if ($resultado_pedido->num_rows === 0) {
        echo "Pedido no encontrado o no tienes permiso para verlo.";
        exit();
    }

    $pedido = $resultado_pedido->fetch_assoc();

    // Prepara la consulta para obtener los detalles del pedido incluyendo talla y color
    $stmt_detalles = $conexion->prepare("
        SELECT dp.*, pr.nombre, pv.talla, pv.color
        FROM detalle_pedidos dp
        INNER JOIN productos pr ON dp.producto_id = pr.id
        LEFT JOIN producto_variantes pv ON dp.variante_id = pv.id
        WHERE dp.pedido_id = ?
    ");

    if (!$stmt_detalles) {
        echo "Error al preparar la consulta de detalles: " . $conexion->error;
        exit();
    }

    $stmt_detalles->bind_param("i", $pedido_id);
    $stmt_detalles->execute();
    $resultado_detalles = $stmt_detalles->get_result();
} catch (Exception $e) {
    echo "Ocurrió un error: " . $e->getMessage();
    exit();
}

include '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2>Detalles del Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h2>
    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($pedido['fecha_pedido']); ?></p>
    <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['nombre_usuario'] . ' ' . $pedido['apellidos']); ?></p>
    <p><strong>Total:</strong> <?php echo number_format($pedido['total'], 2); ?>€</p>
    <p><strong>Método de Pago:</strong> <?php echo ucfirst(htmlspecialchars($pedido['metodo_pago'])); ?></p>
    <p><strong>Dirección de Envío:</strong> <?php echo htmlspecialchars($pedido['direccion_envio']); ?></p>
    <p><strong>Estado:</strong> <?php echo ucfirst(htmlspecialchars($pedido['estado'])); ?></p>

    <h4>Productos</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Color</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado_detalles->num_rows > 0): ?>
                <?php while ($detalle = $resultado_detalles->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detalle['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($detalle['talla'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($detalle['color'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                        <td><?php echo number_format($detalle['precio_unitario'], 2); ?>€</td>
                        <td><?php echo number_format($detalle['subtotal'], 2); ?>€</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay productos en este pedido.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="/modaurbana/admin/pedidos/mis_pedidos.php" class="btn btn-secondary">Volver a Mis Pedidos</a>
</div>
<?php include '../../includes/templates/footer.php'; ?>