<?php

/**
 * checkout.php
 * 
 * Página de proceso de pago.
 *
 * Permite a los usuarios registrados realizar el pedido y confirmar la compra.
 *
 * @category Carrito
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
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Incluye las funciones para enviar correos.
 */
require_once '../../includes/email/enviar_correo.php';


/**
 * Verifica si el carrito no está vacío
 */
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
}


/**
 * Inicializa variables de error.
 */
$error = '';


/**
 * Procesa el formulario cuando se envía.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $metodo_pago = mysqli_real_escape_string($conexion, $_POST['metodo_pago']);
    $total = 0;

    /**
     * Calcula el total del pedido.
     */
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }

    /**
     * Inserta el pedido en la base de datos.
     */
    $sql_pedido = "INSERT INTO pedidos (usuario_id, direccion_envio, metodo_pago, total, fecha_pedido, confirmado) 
                   VALUES ('$usuario_id', '$direccion', '$metodo_pago', '$total', NOW(), 1)";

    if (mysqli_query($conexion, $sql_pedido)) {
        $pedido_id = mysqli_insert_id($conexion);


        /**
         * Inserta los detalles del pedido.
         */
        foreach ($_SESSION['carrito'] as $producto) {
            /**
             * Obtenemos el producto_id correcto.
             */
            $producto_id = $producto['producto_id'];
            $variante_id = $producto['variante_id'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];
            $subtotal = $precio * $cantidad;

            /**
             * Inserta los detalles del pedido.
             */
            $sql_detalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, variante_id, cantidad, precio_unitario, subtotal) 
                            VALUES ('$pedido_id', '$producto_id', '$variante_id', '$cantidad', '$precio', '$subtotal')";
            mysqli_query($conexion, $sql_detalle);

            /**
             * Actualiza el stock del producto o variante.
             */
            if ($variante_id > 0) {
                $sql_actualizar_stock = "UPDATE producto_variantes SET stock = stock - $cantidad WHERE id = $variante_id";
                mysqli_query($conexion, $sql_actualizar_stock);
            } else {

                /**
                 *  Sí no hay variante, actualizar el stock del producto.
                 */
                $sql_actualizar_stock = "UPDATE productos SET stock = stock - $cantidad WHERE id = $producto_id";
                mysqli_query($conexion, $sql_actualizar_stock);
            }
        }


        /**
         * Vacía el carrito.
         */
        unset($_SESSION['carrito']);


        /**
         * Envía un correo de confirmación del pedido.
         */
        $sql_usuario = "SELECT nombre, email FROM usuarios WHERE id='$usuario_id'";
        $resultado_usuario = mysqli_query($conexion, $sql_usuario);
        $usuario = mysqli_fetch_assoc($resultado_usuario);
        enviarCorreoConfirmacionPedido($usuario['email'], $usuario['nombre'], $pedido_id);

        /**
         * Redirige a una página de agradecimiento.
         */
        header('Location: /modaurbana/pages/cart/gracias.php?pedido_id=' . $pedido_id);
        exit();
    } else {
        $error = "Error al procesar el pedido: " . mysqli_error($conexion);
    }
}

/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!--  
Contenedor Principal.
-->
<div class="container mt-4">
    <h2>Proceso de Pago</h2>

    <!-- 
    Mostrar mensaje de error si existe. 
    -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- 
    Formulario para procesar el pago. 
    -->
    <form method="post" action="/modaurbana/pages/cart/checkout.php">
        <div class="form-group">
            <label>Dirección de Envío</label>
            <textarea name="direccion" class="form-control" rows="3" required><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
        </div>

        <!-- 
        Método de pago. 
        -->
        <div class="form-group">
            <label>Método de Pago</label>
            <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                <option value="">Seleccione un método de pago</option>
                <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                <option value="contrareembolso">Contrareembolso</option>
            </select>
        </div>

        <!-- 
        Formulario de datos de tarjeta de crédito. 
        -->
        <div id="datos_tarjeta" style="display: none;">

            <div class="form-group">
                <label>Nombre en la Tarjeta</label>
                <input type="text" name="nombre_tarjeta" class="form-control">
            </div>


            <div class="form-group">
                <label>Número de Tarjeta</label>
                <input type="text" name="numero_tarjeta" class="form-control">
            </div>


            <div class="form-group">
                <label>Fecha de Expiración</label>
                <input type="text" name="expiracion_tarjeta" class="form-control" placeholder="MM/AA">
            </div>


            <div class="form-group">
                <label>Código CVV</label>
                <input type="text" name="cvv_tarjeta" class="form-control">
            </div>

        </div>
        <!-- 
        Botón para finalizar la compra. 
        -->
        <button type="submit" class="btn btn-success">Finalizar Compra</button>
    </form>
</div>

<!-- 
Script para mostrar/ocultar el formulario de tarjeta. 
-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var metodoPagoSelect = document.getElementById('metodo_pago');
        var datosTarjetaDiv = document.getElementById('datos_tarjeta');

        metodoPagoSelect.addEventListener('change', function() {
            if (metodoPagoSelect.value === 'tarjeta') {
                datosTarjetaDiv.style.display = 'block';
            } else {
                datosTarjetaDiv.style.display = 'none';
            }
        });
    });
</script>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>