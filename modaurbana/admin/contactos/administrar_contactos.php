<?php
// administrar_contactos.php

session_start();
include '../../includes/conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

// Obtener todos los mensajes de contacto de la base de datos
$sql_contactos = "SELECT * FROM contactos ORDER BY fecha_envio DESC";
$resultado_contactos = mysqli_query($conexion, $sql_contactos);

include '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2>Administrar Mensajes de Contacto</h2>

    <?php if (mysqli_num_rows($resultado_contactos) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Fecha de Envío</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($contacto = mysqli_fetch_assoc($resultado_contactos)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contacto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['email']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['asunto']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($contacto['mensaje'])); ?></td>
                        <td><?php echo htmlspecialchars($contacto['fecha_envio']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay mensajes de contacto en este momento.</p>
    <?php endif; ?>
</div>
<?php include '../../includes/footer.php'; ?>