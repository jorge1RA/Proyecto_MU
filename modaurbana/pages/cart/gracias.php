<?php

/**
 * gracias.php
 * 
 * Página de agradecimiento.
 * 
 * Muestra un mensaje de confirmación al usuario después de realizar un pedido.
 * También proporciona un enlace para seguir explorando la tienda.
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
 * Obtener el número de pedido si está disponible en la URL o en la sesión.
 */
$pedido_id = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : (isset($_SESSION['pedido_id']) ? intval($_SESSION['pedido_id']) : null);


/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!--  
Contenedor Principal.
-->
<div class="container mt-4 text-center">
    <div class="card shadow-sm p-4">
        <h2 class="text-success mb-4">¡Gracias por tu pedido!</h2>
        <p class="lead">Tu pedido ha sido procesado exitosamente. Nos pondremos en contacto contigo en caso de cualquier actualización. Revisa tu correo para los detalles del pedido.</p>

        <!-- 
        Mostrar número de pedido si está disponible.
         -->
        <?php if ($pedido_id): ?>
            <p><strong>Número de pedido:</strong> <?php echo htmlspecialchars($pedido_id); ?></p>
        <?php endif; ?>

        <p class="text-muted">Puedes seguir explorando nuestras increíbles ofertas o revisar el estado de tu pedido.</p>
        <a href="/modaurbana/pages/products/tienda.php" class="btn btn-primary mt-3">Volver a la Tienda</a>
    </div>

</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>