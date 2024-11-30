<?php

include '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $asunto = mysqli_real_escape_string($conexion, $_POST['asunto']);
    $motivo_devolucion = isset($_POST['motivo_devolucion']) ? mysqli_real_escape_string($conexion, $_POST['motivo_devolucion']) : '';
    $mensaje = mysqli_real_escape_string($conexion, $_POST['mensaje']);

    // Si hay un motivo de devolución, agregarlo al mensaje
    if ($asunto == 'Devolución' && !empty($motivo_devolucion)) {
        $mensaje = "Motivo de devolución: " . $motivo_devolucion . "\n\n" . $mensaje;
    }

    $sql = "INSERT INTO contactos (nombre, email, asunto, mensaje, fecha_envio) VALUES ('$nombre', '$email', '$asunto', '$mensaje', NOW())";

    if (mysqli_query($conexion, $sql)) {
        $mensaje_exito = "Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.";
    } else {
        $error = "Error al enviar el mensaje: " . mysqli_error($conexion);
    }
}
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2>Contacto</h2>
    <?php if (isset($mensaje_exito)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje_exito); ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Formulario de contacto -->
    <form method="POST" action="/modaurbana/pages/contact/contacto.php">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Asunto</label>
            <select name="asunto" id="asunto" class="form-control" required>
                <option value="">Seleccione un asunto</option>
                <option value="Consulta general">Consulta general</option>
                <option value="Devolución">Devolución</option>
                <option value="Queja">Queja</option>
                <option value="Sugerencia">Sugerencia</option>
            </select>
        </div>
        <div class="form-group" id="motivoDevolucionGroup" style="display: none;">
            <label>Motivo de Devolución</label>
            <select name="motivo_devolucion" id="motivo_devolucion" class="form-control">
                <option value="">Seleccione un motivo</option>
                <option value="Artículo defectuoso">Artículo defectuoso</option>
                <option value="No es lo que esperaba">No es lo que esperaba</option>
                <option value="No me gusta">No me gusta</option>
                <option value="Otros">Otros</option>
            </select>
            <small class="form-text text-muted">Las devoluciones tienen un plazo máximo de 20 días desde la fecha de compra.</small>
        </div>
        <div class="form-group">
            <label>Mensaje</label>
            <textarea name="mensaje" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
    </form>
</div>

<!-- JavaScript para mostrar/ocultar el campo de motivo de devolución -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var asuntoSelect = document.getElementById('asunto');
        var motivoDevolucionGroup = document.getElementById('motivoDevolucionGroup');

        asuntoSelect.addEventListener('change', function() {
            if (asuntoSelect.value === 'Devolución') {
                motivoDevolucionGroup.style.display = 'block';
            } else {
                motivoDevolucionGroup.style.display = 'none';
            }
        });
    });
</script>

<?php include '../../includes/templates/footer.php'; ?>