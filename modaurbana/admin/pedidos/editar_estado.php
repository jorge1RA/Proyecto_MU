<?php
//editar_estado.php

include '../../includes/conexion.php';
include '../../includes/templates/header.php';

if (!isset($_GET['id'])) {
    header('Location: /modaurbana/admin/index.php');
    exit();
}

$pedido_id = $_GET['id'];

// Obtener el pedido
$sql_pedido = "SELECT * FROM pedidos WHERE id = '$pedido_id'";
$resultado_pedido = mysqli_query($conexion, $sql_pedido);
$pedido = mysqli_fetch_assoc($resultado_pedido);

if (!$pedido) {
    echo "Pedido no encontrado.";
    exit();
}

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_estado = mysqli_real_escape_string($conexion, $_POST['estado']);

    $sql_actualizar = "UPDATE pedidos SET estado = '$nuevo_estado' WHERE id = '$pedido_id'";
    if (mysqli_query($conexion, $sql_actualizar)) {
        echo "<div class='alert alert-success'>Estado actualizado correctamente.</div>";
        // Actualizar el estado en la variable $pedido
        $pedido['estado'] = $nuevo_estado;
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar el estado: " . mysqli_error($conexion) . "</div>";
    }
}
?>
<br>
<br>
<h2>Editar Estado del Pedido #<?php echo $pedido['id']; ?></h2>
<br>
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

<?php include '../../includes/templates/footer.php'; ?>