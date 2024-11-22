<?php

/**
 * ver_mensaje.php
 * 
 * Página para ver los detalles de un mensaje de contacto específico.
 *
 * Administrador visualizar el contenido completo de un mensaje de contacto seleccionado.
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
 * Verifica si se ha proporcionado un id de mensaje válido.
 * Si no, redirige a la lista de contactos.
 */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: /modaurbana/admin/contactos/ver_contactos.php');
    exit();
}


/**
 * Sanitiza y valida el id del mensaje.
 */
$mensaje_id = intval($_GET['id']);


/**
 * Obtiene los detalles del mensaje desde la base de datos.
 */
$sql = "SELECT * FROM contactos WHERE id='$mensaje_id'";
$resultado = mysqli_query($conexion, $sql);
$mensaje = mysqli_fetch_assoc($resultado);


/**
 * Si no se encuentra el mensaje, redirige a la lista de contactos.
 */
if (!$mensaje) {
    header('Location: /modaurbana/admin/contactos/ver_contactos.php');
    exit();
}

/**
 * Después de verificar que el mensaje existe, actualiza el estado a "leído".
 */
$sql_marcar_leido = "UPDATE contactos SET leido = 1 WHERE id = ?";
$stmt_marcar_leido = mysqli_prepare($conexion, $sql_marcar_leido);
if ($stmt_marcar_leido) {
    mysqli_stmt_bind_param($stmt_marcar_leido, "i", $mensaje_id);
    mysqli_stmt_execute($stmt_marcar_leido);
    mysqli_stmt_close($stmt_marcar_leido);
}


/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>


<!--  
Contenedor Principal, con clases de bootstrap. 
-->
<div class="container mt-4">
    <h2>Detalles del Mensaje</h2>
    <div class="card">
        <div class="card-header">
            <strong>Asunto:</strong> <?php echo htmlspecialchars($mensaje['asunto']); ?>
            <!-- 
            Se utiliza htmlspecialchars() para evitar ataques XSS al mostrar datos provenientes de la base de datos. 
            -->
        </div>
        <div class="card-body">
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($mensaje['nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($mensaje['email']); ?></p>
            <p><strong>Fecha de Envío:</strong> <?php echo htmlspecialchars($mensaje['fecha_envio']); ?></p>
            <p><strong>Mensaje:</strong></p>
            <!-- 
            Convierte caracteres especiales para evitar XSS y preserva los saltos de línea del mensaje original. 
            -->
            <p><?php echo nl2br(htmlspecialchars($mensaje['mensaje'])); ?></p>
        </div>
    </div>
    <a href="/modaurbana/admin/contactos/ver_contactos.php" class="btn btn-secondary mt-3">Volver a la Lista de Mensajes</a>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>