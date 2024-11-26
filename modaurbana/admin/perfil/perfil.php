<?php

/**
 * perfil.php
 *
 * Página que muestra la información del perfil del usuario actual.
 *
 * Permite al usuario ver su información personal y acceder a la página de edición de perfil.
 *
 * @category Usuario
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */

/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Verifica si el usuario ha iniciado sesión.
 * Si no está autenticado, redirige al login.
 */
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Obtiene el id del usuario actual desde la sesión.
 */
$usuario_id = $_SESSION['usuario_id'];


/**
 * Prepara y ejecuta la consulta para obtener la información del usuario desde la base de datos.
 */
$sql_usuario = "SELECT * FROM usuarios WHERE id = '$usuario_id'";
$resultado_usuario = mysqli_query($conexion, $sql_usuario);


/**
 * Verifica si se obtuvo un resultado válido de la consulta,
 * si no se encuentra el usuario, muestra un mensaje de error y termina la ejecución.
 */
if (!$resultado_usuario || mysqli_num_rows($resultado_usuario) == 0) {
    echo "Usuario no encontrado.";
    exit();
}


/**
 * Obtiene la información del usuario y la almacena en la variable $usuario.
 */
$usuario = mysqli_fetch_assoc($resultado_usuario);


/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>

<!-- 
Contenedor principal con margen superior. 
-->
<div class="container mt-4">
    <h2 class="mb-4">Mi Perfil</h2>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Información Personal</h5>
        </div>
        <div class="card-body">
            <!-- 
            Imagen de perfil del usuario. 
            -->
            <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="Imagen de Perfil" class="img-thumbnail mb-3" width="150">

            <!-- 
            Información personal del usuario. 
            -->
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p><strong>Apellidos:</strong> <?php echo htmlspecialchars($usuario['apellidos']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Rol:</strong> <?php echo ucfirst(htmlspecialchars($usuario['rol'])); ?></p>

            <!-- 
            Enlace para editar el perfil. 
            -->
            <a href="/modaurbana/admin/perfil/editar_perfil.php" class="btn btn-primary mt-3">Editar Perfil</a>
        </div>
    </div>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>