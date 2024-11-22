<?php

// Incluye el esqueleto de la cabecera de la página.
include_once '../../includes/templates/header.php';
?>

<div class="container mt-4">
    <h2>Gestión de Productos</h2>
    <p>Pendiente....</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Precio (€)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Camiseta Blanca</td>
                <td>Descripción camiseta.</td>
                <td>19.99</td>
                <td>
                    <a href="#" class="btn btn-sm btn-primary">Editar</a>
                    <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                </td>
            </tr>
            <tr>
                <td>Chaqueta</td>
                <td>Descripción chaqueta.</td>
                <td>29.99</td>
                <td>
                    <a href="#" class="btn btn-sm btn-primary">Editar</a>
                    <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Incluye el esqueleto del pie de la página. -->
<?php include_once '../../includes/templates/footer.php'; ?>