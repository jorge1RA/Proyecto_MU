<?php

/**
 * contacto.php
 *
 * Página de contacto para que los usuarios envíen sus consultas o solicitudes.
 *
 * Procesa el formulario de contacto y guarda el mensaje en la base de datos.
 *
 * @category Contacto
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 *  
 */


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Procesar el formulario cuando se envía una solicitud POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Sanitiza los datos recibidos del formulario.
     */
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $asunto = mysqli_real_escape_string($conexion, $_POST['asunto']);
    $motivo_devolucion = isset($_POST['motivo_devolucion']) ? mysqli_real_escape_string($conexion, $_POST['motivo_devolucion']) : '';
    $mensaje = mysqli_real_escape_string($conexion, $_POST['mensaje']);


    /**
     * Si el asunto es 'Devolución' y hay motivo, agregarlo al mensaje
     */
    if ($asunto == 'Devolución' && !empty($motivo_devolucion)) {
        $mensaje = "Motivo de devolución: " . $motivo_devolucion . "\n\n" . $mensaje;
    }

    /**
     * Insertar mensaje en la base de datos.
     */
    $sql = "INSERT INTO contactos (nombre, email, asunto, mensaje, fecha_envio) VALUES ('$nombre', '$email', '$asunto', '$mensaje', NOW())";

    if (mysqli_query($conexion, $sql)) {
        $mensaje_exito = "Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.";
    } else {
        $error = "Error al enviar el mensaje: " . mysqli_error($conexion);
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
    <h2>Contacto</h2>
    <!-- 
    Mensaje de éxito o error según el resultado del envío del formulario. 
    -->
    <?php if (isset($mensaje_exito)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje_exito); ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>


    <!-- 
    Formulario de contacto. 
    -->
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

        <!-- 
        Sección del motivo de devolución, visible solo si el asunto es 'Devolución'. 
        -->
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

<!-- 
JavaScript para mostrar/ocultar el campo de motivo de devolución. 
-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var asuntoSelect = document.getElementById('asunto');
        var motivoDevolucionGroup = document.getElementById('motivoDevolucionGroup');

        /**
         * Mostrar motivo de devolución solo si el asunto es 'Devolución'
         */
        asuntoSelect.addEventListener('change', function() {
            if (asuntoSelect.value === 'Devolución') {
                motivoDevolucionGroup.style.display = 'block';
            } else {
                motivoDevolucionGroup.style.display = 'none';
            }
        });
    });
</script>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>