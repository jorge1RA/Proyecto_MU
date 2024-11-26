<?php
//checkout.php


session_start();
include '../../includes/conexion.php';
require_once '../../includes/email/enviar_correo.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /modaurbana/login/login.php');
    exit();
}

// Verifica si el carrito no está vacío
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
}

// Inicializa variables de error
$error = '';

// Procesa el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $metodo_pago = mysqli_real_escape_string($conexion, $_POST['metodo_pago']);
    $total = 0;

    // Calcula el total del pedido
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }

    // Inserta el pedido en la base de datos
    $sql_pedido = "INSERT INTO pedidos (usuario_id, direccion_envio, metodo_pago, total, fecha_pedido, confirmado) 
                   VALUES ('$usuario_id', '$direccion', '$metodo_pago', '$total', NOW(), 1)";

    if (mysqli_query($conexion, $sql_pedido)) {
        $pedido_id = mysqli_insert_id($conexion);

        // Inserta los detalles del pedido
        foreach ($_SESSION['carrito'] as $producto) {
            $producto_id = $producto['id'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];
            $subtotal = $precio * $cantidad;

            $sql_detalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
                            VALUES ('$pedido_id', '$producto_id', '$cantidad', '$precio', '$subtotal')";
            mysqli_query($conexion, $sql_detalle);

            // Actualiza el stock del producto
            $sql_actualizar_stock = "UPDATE productos SET stock = stock - $cantidad WHERE id = $producto_id";
            mysqli_query($conexion, $sql_actualizar_stock);
        }

        // Vacia el carrito
        unset($_SESSION['carrito']);

        // Envia un correo de confirmación del pedido
        $sql_usuario = "SELECT nombre, email FROM usuarios WHERE id='$usuario_id'";
        $resultado_usuario = mysqli_query($conexion, $sql_usuario);
        $usuario = mysqli_fetch_assoc($resultado_usuario);
        enviarCorreoConfirmacionPedido($usuario['email'], $usuario['nombre'], $pedido_id);

        // Redirige a una página de agradecimiento
        header('Location: /modaurbana/pages/cart/gracias.php?pedido_id=' . $pedido_id);
        exit();
    } else {
        $error = "Error al procesar el pedido: " . mysqli_error($conexion);
    }
}
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Proceso de Pago</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" action="/modaurbana/pages/cart/checkout.php">
        <div class="form-group">
            <label>Dirección de Envío</label>
            <textarea name="direccion" class="form-control" rows="3" required><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label>Método de Pago</label>
            <select name="metodo_pago" class="form-control" required>
                <option value="">Seleccione un método de pago</option>
                <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                <option value="contrareembolso">Contrareembolso</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Finalizar Compra</button>
    </form>
</div>

<?php include '../../includes/templates/footer.php'; ?>