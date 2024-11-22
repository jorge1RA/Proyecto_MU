<?php

/**
 * editar_perfil.php
 *
 * Página que permite al usuario editar la información de su perfil.
 *
 * Permite al usuario actualizar sus datos personales, imagen de perfil y cambiar su contraseña.
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
 * Verifica si el usuario ha iniciado sesión,
 * si no está autenticado, redirige al login.
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
 * Prepara y ejecuta la consulta para obtener la información del usuario.
 */
$sql_usuario = "SELECT * FROM usuarios WHERE id = '$usuario_id'";
$resultado_usuario = mysqli_query($conexion, $sql_usuario);


/**
 * Verifica si se encontró el usuario,
 * si no, muestra un mensaje de error y termina la ejecución.
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
 * Inicializa las variables de mensaje para errores y éxitos.
 */
$error = '';
$success = '';


/**
 * Procesa el formulario si se ha enviado mediante el método POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Actualiza los datos personales del usuario.
     */
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conexion, $_POST['apellidos']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    /**
     * Prepara y ejecuta la consulta para actualizar los datos del usuario.
     */
    $sql_actualizar = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', email = '$email' WHERE id = '$usuario_id'";
    if (mysqli_query($conexion, $sql_actualizar)) {
        $success = "Perfil actualizado correctamente.";
    } else {
        $error = "Error al actualizar el perfil: " . mysqli_error($conexion);
    }


    /**
     * Maneja la subida de la imagen de perfil.
     */
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['imagen']['name'];
        $file_tmp = $_FILES['imagen']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));


        /**
         * Verifica si el tipo de archivo está permitido.
         */
        if (in_array($file_ext, $allowed)) {
            $new_name = uniqid('img_', true) . '.' . $file_ext;
            $destination = '/modaurbana/assets/img/' . $new_name;


            /**
             * Mueve el archivo subido al directorio de destino.
             */
            if (move_uploaded_file($file_tmp, $destination)) {
                /**
                 * Prepara y ejecuta la consulta para actualizar la imagen del usuario en la base de datos.
                 */
                $stmt = $conexion->prepare("UPDATE usuarios SET imagen = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $new_name, $usuario_id);
                    if ($stmt->execute()) {
                        $success .= " Imagen de perfil actualizada correctamente.";


                        /**
                         * Elimina la imagen antigua si no es la imagen por defecto.
                         */
                        if ($usuario['imagen'] != 'default.png') {
                            unlink('/modaurbana/assets/img/' . $usuario['imagen']);
                        }
                        $usuario['imagen'] = $new_name;
                    } else {
                        $error .= " Error al actualizar la imagen en la base de datos.";
                    }
                    $stmt->close();
                } else {
                    $error .= " Error al preparar la consulta.";
                }
            } else {
                $error .= " Error al mover el archivo.";
            }
        } else {
            $error .= " Tipo de archivo no permitido. Solo se permiten JPG, JPEG, PNG y GIF.";
        }
    }


    /**
     * Maneja el cambio de contraseña del usuario.
     */
    if (!empty($_POST['password']) || !empty($_POST['confirm_password'])) {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];


        /**
         * Verifica que las contraseñas coinciden y que cumplen con los requisitos mínimos de seguridad.
         */
        if ($password !== $confirm_password) {
            $error .= " Las contraseñas no coinciden.";
        } elseif (strlen($password) < 6) {
            $error .= " La contraseña debe tener al menos 6 caracteres.";
        } else {
            /**
             * Hashea la nueva contraseña y actualiza la base de datos.
             */
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);


            /**
             *  Actualiza la contraseña en la base de datos
             */
            $stmt = $conexion->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("si", $password_hashed, $usuario_id);
                if ($stmt->execute()) {
                    $success .= " Contraseña actualizada correctamente.";
                } else {
                    $error .= " Error al actualizar la contraseña: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error .= " Error al preparar la consulta para la contraseña.";
            }
        }
    }
}

/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>


<!-- 
Contenedor principal con margen superior. 
-->
<div class="container mt-4">
    <h2 class="mb-4">Editar Perfil</h2>
    <div class="card">

        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="/modaurbana/admin/perfil/editar_perfil.php" enctype="multipart/form-data">
                <!-- 
                Formulario para editar datos personales. 
                -->
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                </div>

                <!-- 
                Subida de imagen de perfil. 
                -->
                <div class="form-group">
                    <label>Imagen de Perfil</label><br>
                    <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="Imagen de Perfil" class="img-thumbnail mb-2" width="150"><br>
                    <input type="file" name="imagen" accept="image/*" class="form-control-file">
                </div>
                <hr>

                <!-- 
                Cambio de contraseña. 
                -->
                <h5>Cambiar Contraseña</h5>
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Ingresa nueva contraseña">
                </div>
                <div class="form-group">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirma nueva contraseña">
                </div>

                <!-- 
                Botones para guardar cambios o cancelar. 
                -->
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <br>
                <a href="/modaurbana/admin/perfil/perfil.php" class="btn btn-secondary mt-3">Cancelar</a>
                <a href="/modaurbana/admin/perfil/perfil.php" class="btn btn-secondary mt-3">Volver atrás</a>
            </form>

        </div>
    </div>

</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include '../../includes/templates/footer.php'; ?>