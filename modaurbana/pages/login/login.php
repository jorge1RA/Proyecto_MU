<?php

/**
 * login.php
 * 
 * Página de inicio de sesión para los usuarios de ModaUrbana.
 * Permite autenticar a los usuarios y redirigirlos según su rol (usuario o administrador).
 * 
 * @category Autenticación
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Procesa el formulario de inicio de sesión cuando se envía una solicitud POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Obtiene y sanitiza el correo electrónico para evitar inyección SQL.
     */
    $email = $_POST['email'];
    $password = $_POST['password'];

    /**
     * Evita inyección SQL
     */
    $email = mysqli_real_escape_string($conexion, $email);

    /**
     * Consulta para obtener el usuario por su correo electrónico.
     */
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $resultado = mysqli_query($conexion, $sql);
    $usuario = mysqli_fetch_assoc($resultado);

    /**
     * Verificar si el usuario existe y si la contraseña es correcta.
     */
    if ($usuario && password_verify($password, $usuario['password'])) {
        /**
         * Guarda la información del usuario en la sesión.
         */
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];

        /**
         *  Redirige al panel correspondiente según el rol del usuario.
         */
        if ($usuario['rol'] == 'admin') {
            header('Location: /modaurbana/admin/index.php');
        } else {
            header('Location: /modaurbana/admin/perfil/perfil.php');
        }
        exit();
    } else {
        /**
         *  Mensaje de error si las credenciales no son correctas.
         */
        $error = "Correo o contraseña incorrectos.";
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
<div class="container">
    <br>
    <h2>Iniciar Sesión</h2>

    <!-- 
    Formulario de inicio de sesión. 
    -->
    <form method="POST" action="/modaurbana/pages/login/login.php">

        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>


        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Ingresar</button>
    </form>

</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>