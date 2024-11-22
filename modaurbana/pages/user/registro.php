<?php

// Inicia la sesión
session_start();

// Incluye el esqueleto de la cabecera de la página
include_once '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2>Registro de Usuario</h2>

    <!-- ↓ Continuar ↓ -->
    <p>El formulario de registro....</p>

    <!-- Formulario -->
    <form>
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" class="form-control" placeholder="Tu nombre">
        </div>

        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" class="form-control" placeholder="Tus apellidos">
        </div>

        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" class="form-control" placeholder="Tu correo electrónico">
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" class="form-control" placeholder="Tu contraseña">
        </div>

        <div class="form-group">
            <label>Confirmar Contraseña</label>
            <input type="password" class="form-control" placeholder="Confirma tu contraseña">
        </div>

        <button type="button" class="btn btn-primary">Registrarse</button>
    </form>

</div>

<!-- Incluye el esqueleto del pie de la página -->
<?php include_once '../../includes/templates/footer.php'; ?>