<?php

/**
 * editar_estado.php
 * 
 * Página para editar el estado de un pedido específico.
 *
 * Permite al administrador actualizar el estado de un pedido seleccionado.
 *
 * @category Administración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
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
 * Verifica si se ha proporcionado un id de pedido.
 * Si no se proporciona, redirige al índice de administración.
 */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: /modaurbana/admin/index.php');
    exit();
}


/**
 * Obtiene y sanitiza el id del pedido.
 * 
 * Convierte el id a entero para mayor seguridad.
 */
$pedido_id = intval($_GET['id']);


/**
 * Consulta para obtener los detalles del pedido.
 */
$sql_pedido = "SELECT * FROM pedidos WHERE id = '$pedido_id'";
$resultado_pedido = mysqli_query($conexion, $sql_pedido);
$pedido = mysqli_fetch_assoc($resultado_pedido);


/**
 * Verifica si el pedido existe.
 * Si no existe, muestra un mensaje y termina la ejecución.
 */
if (!$pedido) {
    echo "Pedido no encontrado.";
    exit();
}


/**
 * Procesa el formulario cuando se envía una actualización.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /**
     * Sanitiza el nuevo estado del pedido.
     */
    $nuevo_estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    /**
     * Actualiza el estado del pedido en la base de datos.
     */
    $sql_actualizar = "UPDATE pedidos SET estado = '$nuevo_estado' WHERE id = '$pedido_id'";
    if (mysqli_query($conexion, $sql_actualizar)) {
        echo "<div class='alert alert-success'>Estado actualizado correctamente.</div>";

        /**
         * Actualizar el estado en la variable $pedido
         */
        $pedido['estado'] = $nuevo_estado;
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar el estado: " . mysqli_error($conexion) . "</div>";
    }
}


/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!-- 
Doble salto de linea. 
-->
<br><br>
<h2>Editar Estado del Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h2>
<br>
<!-- 
Inicia un formulario que enviará los datos mediante el método POST a la misma página. 
-->
<form method="post" action="">
    <div class="form-group">
        <label for="estado">Estado Actual:</label>
        <select name="estado" id="estado" class="form-control">
            <option value="pendiente" <?php if ($pedido['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
            <option value="procesando" <?php if ($pedido['estado'] == 'procesando') echo 'selected'; ?>>Procesando</option>
            <option value="enviado" <?php if ($pedido['estado'] == 'enviado') echo 'selected'; ?>>Enviado</option>
            <option value="entregado" <?php if ($pedido['estado'] == 'entregado') echo 'selected'; ?>>Entregado</option>
            <option value="cancelado" <?php if ($pedido['estado'] == 'cancelado') echo 'selected'; ?>>Cancelado</option>
        </select>
    </div>
    <br>
    <br>
    <button type="submit" class="btn btn-primary">Actualizar Estado</button>
    <a href="index.php" class="btn btn-secondary">Volver a la lista de pedidos</a>
</form>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>