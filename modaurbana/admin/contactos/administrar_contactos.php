<?php

/**
 * administrar_contactos.php
 * 
 * Página para administrar los mensajes de contacto.
 *
 * Permite al administrador ver todos los mensajes enviados a través del formulario de contacto.
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
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
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
$sql_contactos = "SELECT * FROM contactos ORDER BY fecha_envio DESC";
$resultado_contactos = mysqli_query($conexion, $sql_contactos);


/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>


<!--  
Contenedor Principal.
-->
<div class="container mt-4">
    <h2>Administrar Mensajes de Contacto</h2>
    <!-- 
    Condición para verificar el número de filas resultante de la consulta, si es mayor de cero, hay 1 o más mensaje. 
    -->
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
                <!-- 
                bucle para generar una fila por cada mensaje de contacto obtenido de la consulta. 
                -->
                <?php while ($contacto = mysqli_fetch_assoc($resultado_contactos)): ?>
                    <tr>
                        <!-- 
                        Muestra el nombre del remitente, como email, asunto, mensaje, fecha_envio 
                        -->
                        <td><?php echo htmlspecialchars($contacto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['email']); ?></td>
                        <td><?php echo htmlspecialchars($contacto['asunto']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($contacto['mensaje'])); ?></td>
                        <td><?php echo htmlspecialchars($contacto['fecha_envio']); ?></td>
                    </tr>
                    <!-- 
                    Fin de Bucle de contacto. 
                    -->
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- 
        SÍ es cero, saca mensaje informativo, es decir, Si no hay pedidos, muestra un mensaje informativo al administrador. 
        -->
        <p>No hay mensajes de contacto en este momento.</p>
    <?php endif; ?>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>