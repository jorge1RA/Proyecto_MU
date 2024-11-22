<?php

/**
 * registro.php
 * 
 * Página de registro de nuevos usuarios en ModaUrbana.
 * Permite a los usuarios crear una cuenta proporcionando sus datos personales.
 * 
 * @category Registro
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
 * Incluye funciones de correo electrónico.
 */
include '../../includes/email/enviar_correo.php';


/**
 * Inicializa las variables para almacenar mensajes de error o éxito.
 */
$error = '';
$exito = '';


/**
 * Verifica si el formulario se ha enviado mediante una solicitud POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /**
     * Obtiene y sanitiza los datos del formulario
     */
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);


    /**
     * Valida los campos del formulario para asegurarse de que están completos y correctos.
     */
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "Por favor, completa todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } elseif ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        /**
         * Verifica si el correo ya está registrado en la base de datos.
         */
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            /**
             * Hashea la contraseña para almacenarla de forma segura.
             */
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);

            /**
             * Inserta el nuevo usuario en la base de datos con rol de usuario y confirmado = 1.
             */
            $stmt_insert = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, email, password, rol, confirmado, fecha_registro) VALUES (?, ?, ?, ?, 'usuario', 1, NOW())");
            $stmt_insert->bind_param("ssss", $nombre, $apellidos, $email, $password_hashed);

            if ($stmt_insert->execute()) {
                /**
                 * Mensaje de éxito y envío de correo de bienvenida.
                 */
                $exito = "Registro exitoso! Bienvenido a ModaUrbana.";
                enviarCorreoBienvenida($email, $nombre);
            } else {
                /**
                 * Mensaje de error en caso de fallo al registrar el usuario.
                 */
                $error = "Hubo un error al registrar el usuario. Intenta nuevamente.";
            }

            /**
             * Cierre de sentencia preparada para insertar el usuario.
             */
            $stmt_insert->close();
        }

        /**
         * Cierre de sentencia preparada para verificar el correo electrónico.
         */
        $stmt->close();
    }
}

/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>


<!--  
Contenedor principal de la página de registro.
-->
<div class="container mt-4">
    <h2>Registro de Usuario</h2>

    <!-- 
    Mensaje de error si la validación falla. 
    -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- 
    Mensaje de éxito si el registro es exitoso. 
    -->
    <?php if (!empty($exito)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($exito); ?></div>
    <?php endif; ?>

    <!-- 
    Formulario de registro. 
    -->
    <form method="post" action="registro.php">

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" required>
        </div>


        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" name="apellidos" class="form-control" value="<?php echo isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : ''; ?>" required>
        </div>


        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        </div>


        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>


        <div class="form-group">
            <label>Confirmar Contraseña</label>
            <input type="password" name="password_confirm" class="form-control" required>
        </div>


        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>

</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once '../../includes/templates/footer.php'; ?>