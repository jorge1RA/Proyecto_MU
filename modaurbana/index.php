<?php
session_start();
include_once 'includes/conexion.php';

// header
include_once 'includes/templates/header.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ModaUrbana - Inicio</title>
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Productos Destacados</h2>
        <div class="row">
            <?php
            // Consulta los productos desde la base de datos (Prueba)
            $sql = "SELECT * FROM productos LIMIT 6"; // Número de productos
            $resultado = mysqli_query($conexion, $sql);

            // Mostrar los productos si existen (Prueba)
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($producto = mysqli_fetch_assoc($resultado)) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '    <div class="card h-100">';
                    echo '        <img src="/modaurbana/assets/img/' . htmlspecialchars($producto['imagen']) . '" class="card-img-top" alt="Producto" style="height: 300px; object-fit: cover;">';
                    echo '        <div class="card-body">';
                    echo '            <h5 class="card-title">' . htmlspecialchars($producto['nombre']) . '</h5>';
                    echo '            <p class="card-text">' . htmlspecialchars($producto['descripcion']) . '</p>';
                    echo '        </div>';
                    echo '        <div class="card-footer">';
                    echo '            <p class="card-text">' . number_format($producto['precio'], 2) . '€</p>';
                    echo '            <a href="#" class="btn btn-primary">Comprar</a>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay productos disponibles.</p>';
            }
            ?>
        </div>
    </div>

    <!-- footer -->
    <?php include_once 'includes/templates/footer.php'; ?>
</body>

</html>