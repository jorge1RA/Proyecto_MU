<?php

/**
 * ver_pedido.php
 *
 * Página que muestra los detalles de un pedido específico del usuario actual.
 *
 * Permite al usuario ver la información detallada de un pedido seleccionado.
 *
 * @category Usuario
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Verifica si el usuario ha iniciado sesión,
 * si no lo está, redirige al login.php.
 */
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Verifica si se ha proporcionado el id del pedido y que no esté vacío,
 * si no, redirige a la página de mis pedidos.
 */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: /modaurbana/admin/pedidos/mis_pedidos.php');
    exit();
}


/**
 * Obtiene y sanitiza el id del pedido y el id del usuario.
 */
$pedido_id = intval($_GET['id']);
$usuario_id = $_SESSION['usuario_id'];
$usuario_rol = $_SESSION['usuario_rol'];


/**
 * Prepara la consulta para obtener el pedido y los datos del usuario,
 * sí el usuario es administrador, puede ver cualquier pedido.
 */
if ($usuario_rol === 'admin') {
    /**
     * El administrador puede ver cualquier pedido
     */
    $stmt_pedido = $conexion->prepare("
        SELECT p.*, u.nombre AS nombre_usuario, u.apellidos, u.email 
        FROM pedidos p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ?
    ");
    $stmt_pedido->bind_param("i", $pedido_id);
} else {
    /**
     * Los clientes solo pueden ver sus propios pedidos.
     */
    $stmt_pedido = $conexion->prepare("
        SELECT p.*, u.nombre AS nombre_usuario, u.apellidos, u.email 
        FROM pedidos p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ? AND p.usuario_id = ?
    ");
    $stmt_pedido->bind_param("ii", $pedido_id, $usuario_id);
}

/**
 * Ejecuta la consulta.
 */
$stmt_pedido->execute();
$resultado_pedido = $stmt_pedido->get_result();


/**
 * Verifica si se encontró el pedido.
 * Si no, muestra el mensaje de abajo y termina la ejecución.
 */
if ($resultado_pedido->num_rows === 0) {
    echo "Pedido no encontrado o no tienes permiso para verlo.";
    exit();
}


/**
 * Extrae una fila del resultado de la consulta como un array asociativo y la asigna a $pedido.
 */
$pedido = $resultado_pedido->fetch_assoc();


/**
 * Prepara la consulta para obtener los detalles del pedido incluyendo talla y color.
 */
$stmt_detalles = $conexion->prepare("
        SELECT dp.*, pr.nombre, pv.talla, pv.color
        FROM detalle_pedidos dp
        INNER JOIN productos pr ON dp.producto_id = pr.id
        LEFT JOIN producto_variantes pv ON dp.variante_id = pv.id
        WHERE dp.pedido_id = ?
    ");

/**
 * Verifica si la preparación de la consulta de detalles fue exitosa,
 * si no lo fue, muestra un mensaje de error y termina la ejecución.
 */
if (!$stmt_detalles) {
    echo "Error al preparar la consulta de detalles: " . $conexion->error;
    exit();
}

/**
 * Vincula el Iid del pedido al parámetro de la consulta.
 */
$stmt_detalles->bind_param("i", $pedido_id);
$stmt_detalles->execute();
$resultado_detalles = $stmt_detalles->get_result();


/**
 * Incluye el esqueleto de la cabecera.
 */
include_once '../../includes/templates/header.php';
?>

<!-- 
Contenedor principal con margen superior. 
-->
<div class="container mt-4">
    <h2>Detalles del Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h2>
    <!-- 
    Información del pedido. 
    -->
    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($pedido['fecha_pedido']); ?></p>
    <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['nombre_usuario'] . ' ' . $pedido['apellidos']); ?></p>
    <p><strong>Total:</strong> <?php echo number_format($pedido['total'], 2); ?>€</p>
    <p><strong>Método de Pago:</strong> <?php echo ucfirst(htmlspecialchars($pedido['metodo_pago'])); ?></p>
    <p><strong>Dirección de Envío:</strong> <?php echo htmlspecialchars($pedido['direccion_envio']); ?></p>
    <p><strong>Estado:</strong> <?php echo ucfirst(htmlspecialchars($pedido['estado'])); ?></p>

    <!-- 
    Sección de productos incluidos en el pedido. 
    -->
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
            <!-- 
            Condición para verificar el número de filas resultante de la consulta, si es mayor de cero, hay 1 o más pedido. 
            -->
            <?php if ($resultado_detalles->num_rows > 0): ?>
                <!-- 
                Bucle para generar una fila por cada pedido obtenido de la consulta. 
                -->
                <?php while ($detalle = $resultado_detalles->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detalle['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($detalle['talla'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($detalle['color'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                        <td><?php echo number_format($detalle['precio_unitario'], 2); ?>€</td>
                        <td><?php echo number_format($detalle['subtotal'], 2); ?>€</td>
                    </tr>

                    <!-- 
                    Fin del Bucle de detalles. 
                    -->
                <?php endwhile; ?>
                <!-- 
                Sí es cero, saca mensaje informativo, es decir, Si no hay pedidos, muestra un mensaje informativo al administrador. 
                -->
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay productos en este pedido.</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>

    <a href="/modaurbana/admin/pedidos/mis_pedidos.php" class="btn btn-secondary">Volver a Mis Pedidos</a>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>