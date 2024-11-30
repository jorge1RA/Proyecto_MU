<?php
//login.php

include '../../includes/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Evita inyección SQL
    $email = mysqli_real_escape_string($conexion, $email);

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $resultado = mysqli_query($conexion, $sql);
    $usuario = mysqli_fetch_assoc($resultado);

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];

        if ($usuario['rol'] == 'admin') {
            header('Location: /modaurbana/admin/index.php');
        } else {
            header('Location: /modaurbana/admin/perfil/perfil.php');
        }
        exit();
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>
<!-- Formulario de inicio de sesión -->
<?php include '../../includes/templates/header.php'; ?>
<div class="container">
    <br>
    <h2>Iniciar Sesión</h2>
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
<?php include '../../includes/templates/footer.php'; ?>