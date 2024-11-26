<?php
// ver_mensaje.php

include '../../includes/conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

// Verificar si se ha proporcionado un ID de mensaje
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: /modaurbana/admin/contactos/ver_contactos.php');
    exit();
}

$mensaje_id = intval($_GET['id']); // Convertir el ID a entero para mayor seguridad

// Obtener los detalles del mensaje
$sql = "SELECT * FROM contactos WHERE id='$mensaje_id'";
$resultado = mysqli_query($conexion, $sql);
$mensaje = mysqli_fetch_assoc($resultado);

// Si no se encuentra el mensaje, redirigir a la lista de contactos
if (!$mensaje) {
    header('Location: /modaurbana/admin/contactos/ver_contactos.php');
    exit();
}

include '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2>Detalles del Mensaje</h2>
    <div class="card">
        <div class="card-header">
            <strong>Asunto:</strong> <?php echo htmlspecialchars($mensaje['asunto']); ?>
        </div>
        <div class="card-body">
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($mensaje['nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($mensaje['email']); ?></p>
            <p><strong>Fecha de Envío:</strong> <?php echo htmlspecialchars($mensaje['fecha_envio']); ?></p>
            <p><strong>Mensaje:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($mensaje['mensaje'])); ?></p>
        </div>
    </div>
    <a href="/modaurbana/admin/contactos/ver_contactos.php" class="btn btn-secondary mt-3">Volver a la Lista de Mensajes</a>
</div>

<?php include '../../includes/templates/footer.php'; ?>