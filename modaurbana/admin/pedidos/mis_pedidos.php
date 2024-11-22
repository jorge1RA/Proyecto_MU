<?php

/**
 * mis_pedidos.php
 *
 * Página que muestra los pedidos del usuario actual.
 *
 * Muestra una lista de los pedidos realizados por el usuario, permitiendo ver los detalles de cada pedido.
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
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';

/**
 * Verifica si el usuario ha iniciado sesión.
 * Si no está autenticado, redirige al login.
 */
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

/**
 * Obtiene el id del usuario actual de la sesión y lo asigna a $usuario_id.
 */
$usuario_id = $_SESSION['usuario_id'];

/**
 * Preparar la consulta para obtener los pedidos del usuario
 */
$stmt_pedidos = $conexion->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC");
$stmt_pedidos->bind_param("i", $usuario_id);
$stmt_pedidos->execute();
$resultado_pedidos = $stmt_pedidos->get_result();

/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!-- 
Contenedor principal con margen superior. 
-->
<div class="container mt-4">
    <h2>Mis Pedidos</h2>
    <!--
     Condición para verificar el número de filas resultante de la consulta, si es mayor de cero, hay 1 o más pedido. 
     -->
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
                <!-- 
                Bucle para generar una fila por cada pedido obtenido de la consulta. 
                -->
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
                    <!-- 
                    Fin del Bucle de pedidos. 
                    -->
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- 
        Sí es cero, saca mensaje informativo, es decir, Si no hay pedidos, muestra un mensaje informativo al administrador. 
        -->
    <?php else: ?>
        <p>No has realizado ningún pedido.</p>
    <?php endif; ?>
    <?php
    /**
     * Se Cierra el statement
     */
    $stmt_pedidos->close();
    ?>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>