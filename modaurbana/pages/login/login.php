<?php

// Inicia la sesión
session_start();

// Incluye el esqueleto de la cabecera de la página
include_once '../../includes/templates/header.php';
?>

<div class="container">
    <br>
    <h2>Iniciar Sesión</h2>

    <!-- Pendiente... -->
    <!-- Formulario de inicio -->
    <form>
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" class="form-control" placeholder="Tu correo electrónico">
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" class="form-control" placeholder="Tu contraseña">
        </div>
        <button type="button" class="btn btn-primary">Ingresar</button>
    </form>
</div>

<!-- Incluye el esqueleto del pie de la página -->
<?php include_once '../../includes/templates/footer.php'; ?>