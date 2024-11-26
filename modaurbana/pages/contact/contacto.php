<?php

// Incluye el esqueleto de la cabecera de la página
include_once '../../includes/templates/header.php';
?>


<div class="container mt-4">
    <h2>Contacto</h2>

    <!-- Pendinte formulario de contacto... -->
    <p>El formulario de contacto...</p>

    <!-- Formulario -->
    <form>
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" class="form-control" placeholder="Tu nombre">
        </div>
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" class="form-control" placeholder="Tu correo electrónico">
        </div>
        <div class="form-group">
            <label>Asunto</label>
            <select class="form-control">
                <option>Seleccione un asunto</option>
                <option>Consulta general</option>
                <option>Devolución</option>
                <option>Queja</option>
                <option>Sugerencia</option>
            </select>
        </div>
        <div class="form-group">
            <label>Mensaje</label>
            <textarea class="form-control" rows="5" placeholder="Tu mensaje"></textarea>
        </div>
        <button type="button" class="btn btn-primary">Enviar Mensaje</button>
    </form>
</div>

<!-- Incluye el esqueleto del pie de la página -->
<?php include_once '../../includes/templates/footer.php'; ?>