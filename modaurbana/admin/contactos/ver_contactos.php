<?php

/**
 * ver_contactos.php
 * 
 * Página para visualizar los mensajes de contacto.
 *
 * Muestra una lista de los mensajes de contacto recibidos,
 * permitiendo al administrador ver detalles específicos de cada mensaje.
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
 * Consulta SQL para obtener todos los mensajes de contacto,
 * ordenados por fecha de envío de forma descendente.
 */
$sql = "SELECT * FROM contactos ORDER BY fecha_envio DESC";
$resultado = mysqli_query($conexion, $sql);

/**
 * Incluye elesqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!--  
Contenedor Principal, con clases de bootstrap. 
-->
<div class="container mt-4">
    <h2>Mensajes de Contacto</h2>
    <!-- 
    Condición para verificar el número de filas resultante de la consulta, si es mayor de cero, hay 1 o más mensaje. 
    -->
    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <table class="table">
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
                <!-- 
                Bucle para generar una fila por cada mensaje obtenido de la consulta. 
                -->
                <?php while ($mensaje = mysqli_fetch_assoc($resultado)): ?>
                    <!-- 
                    Mostrar mensajes con color diferente si no han sido leídos. 
                    -->
                    <tr class="<?php echo $mensaje['leido'] ? 'table-light' : 'table-warning'; ?>">
                        <!-- 
                        Muestra el nombre del remitente, como email, asunto, fecha_envio. 
                        -->
                        <td><?php echo htmlspecialchars($mensaje['id']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['email']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['asunto']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['fecha_envio']); ?></td>
                        <td>
                            <a href="/modaurbana/admin/contactos/ver_mensajes.php?id=<?php echo htmlspecialchars($mensaje['id']); ?>" class="btn btn-primary btn-sm">Ver Mensaje</a>
                            <a href="/modaurbana/admin/contactos/borrar_mensaje.php?id=<?php echo htmlspecialchars($mensaje['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas borrar este mensaje?');">Borrar</a>
                        </td>
                    </tr>
                    <!-- 
                    Fin de Bucle. 
                    -->
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- 
        Sí es cero, saca mensaje informativo. 
        -->
        <p>No hay mensajes.</p>
    <?php endif; ?>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>