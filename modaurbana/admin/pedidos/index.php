<?php

/**
 * index.php - pedidos
 * 
 * Página principal para la gestión de pedidos.
 *
 * Muestra una lista de todos los pedidos realizados, permitiendo al administrador
 * ver los detalles y editar el estado de cada pedido.
 *
 * @category Administración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Verifica si el usuario está autenticado y es administrador.
 * Si no cumple las condiciones, redirige al login.
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
 * Consulta para obtener todos los pedidos junto con el nombre del usuario que los realizó.
 * 
 * Como información detayada:
 * ---------------------------
 * 
 * SELECT p.*, u.nombre AS nombre_usuario FROM pedidos p INNER JOIN usuarios u ON p.usuario_id = u.id: 
 * 
 * Selecciona todos los campos de la tabla pedidos (alias p) 
 * y el nombre del usuario asociado a cada pedido de la tabla usuarios (alias u).
 * INNER JOIN: Une ambas tablas donde el usuario_id del pedido coincide con el id del usuario.
 * ORDER BY p.fecha_pedido DESC: Ordena los resultados por la fecha del pedido en orden descendente, mostrando los pedidos más recientes primero.
 * 
 */
$sql_pedidos = "SELECT p.*, u.nombre AS nombre_usuario FROM pedidos p
                INNER JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY p.fecha_pedido DESC";

/**
 * Ejecuta la consulta en la base de datos y almacena el resultado en $resultado_pedidos.
 */
$resultado_pedidos = mysqli_query($conexion, $sql_pedidos);

/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!-- 
Doble salto de linea. 
-->
<br><br>
<h2>Gestión de Pedidos</h2>
<br>
<!-- 
Condición para verificar el número de filas resultante de la consulta, si es mayor de cero, hay 1 o más pedido. 
-->
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
            <!-- 
            Bucle para generar una fila por cada pedido obtenido de la consulta. 
            -->
            <?php while ($pedido = mysqli_fetch_assoc($resultado_pedidos)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['nombre_usuario']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($pedido['total'], 2)); ?>€</td>
                    <td><?php echo htmlspecialchars(ucfirst($pedido['metodo_pago'])); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?></td>
                    <td>
                        <a href="/modaurbana/admin/pedidos/ver_pedido.php?id=<?php echo $pedido['id']; ?>" class="btn btn-primary btn-sm">Ver Detalles</a>
                        <a href="/modaurbana/admin/pedidos/editar_estado.php?id=<?php echo $pedido['id']; ?>" class="btn btn-warning btn-sm">Editar Estado</a>
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
    <p>No hay pedidos registrados.</p>
<?php endif; ?>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>