<?php
// registro.php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../../includes/conexion.php';
include '../../includes/email/enviar_correo.php';

// Inicializar variables
$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtiene y sanitiza los datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    // Valida los campos
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "Por favor, completa todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } elseif ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verifica si el correo ya está registrado
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            // Hashea la contraseña
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);

            // Inserta el nuevo usuario en la base de datos con el campo confirmado = 1
            $stmt_insert = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, email, password, rol, confirmado, fecha_registro) VALUES (?, ?, ?, ?, 'usuario', 1, NOW())");
            $stmt_insert->bind_param("ssss", $nombre, $apellidos, $email, $password_hashed);

            if ($stmt_insert->execute()) {
                $exito = "Registro exitoso! Bienvenido a ModaUrbana.";
                enviarCorreoBienvenida($email, $nombre); // Envia un correo de bienvenida
            } else {
                $error = "Hubo un error al registrar el usuario. Intenta nuevamente.";
            }

            $stmt_insert->close();
        }

        $stmt->close();
    }
}

?>

<?php include '../../includes/templates/header.php'; ?>

<div class="container mt-4">
    <h2>Registro de Usuario</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (!empty($exito)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($exito); ?></div>
    <?php endif; ?>
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

<?php include '../../includes/templates/footer.php'; ?>