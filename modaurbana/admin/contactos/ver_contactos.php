<?php
// ver_contactos.php

include '../../includes/conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

$sql = "SELECT * FROM contactos ORDER BY fecha_envio DESC";
$resultado = mysqli_query($conexion, $sql);

include '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2>Mensajes de Contacto</h2>
    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Asunto</th>
                    <th>Fecha de Envío</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($mensaje = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mensaje['id']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['email']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['asunto']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['fecha_envio']); ?></td>
                        <td>
                            <a href="/modaurbana/admin/contactos/ver_mensajes.php?id=<?php echo htmlspecialchars($mensaje['id']); ?>" class="btn btn-primary btn-sm">Ver</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay mensajes.</p>
    <?php endif; ?>
</div>

<?php include '../../includes/templates/footer.php'; ?>