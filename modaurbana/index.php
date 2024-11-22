<?php

/**
 * index.php - Principal (INICIO)
 * 
 * Página principal del sitio web ModaUrbana que muestra los productos destacados.
 * 
 * @category Inicio
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
include_once 'includes/conexion.php';


/**
 * Consulta para obtener todos los productos disponibles en la base de datos.
 */
$sql = "SELECT * FROM productos";
$resultado = mysqli_query($conexion, $sql);


/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once 'includes/templates/header.php';
?>

<!--  
Contenedor principal con margen superior.
-->
<div class="container mt-4">
    <h2 class="mb-4">Productos Destacados</h2>

    <!-- 
    Fila de productos. 
    -->
    <div class="row">
        <?php while ($producto = mysqli_fetch_assoc($resultado)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">

                    <?php if ($producto['imagen']): ?>
                        <!-- 
                        Envuelve la imagen en un enlace a producto.php pasando el id. 
                        -->
                        <a href="pages/products/productos.php?id=<?php echo intval($producto['id']); ?>">
                            <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" style="height: 300px; object-fit: cover;">
                        </a>

                    <?php else: ?>

                        <!-- 
                        Imagen de respaldo si no se encuentra la imagen del producto. 
                        -->
                        <a href="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>">
                            <img src="/modaurbana/assets/img/" class="card-img-top" alt="Producto">
                        </a>
                    <?php endif; ?>

                    <!-- 
                    Cuerpo de la tarjeta. 
                    -->
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    </div>

                    <!-- 
                    Pie de la tarjeta. 
                    -->
                    <div class="card-footer">
                        <p class="card-text"><?php echo number_format($producto['precio'], 2); ?>€</p>
                        <a href="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>" class="btn btn-primary">Comprar</a>
                    </div>

                </div>
            </div>
        <?php } ?>

    </div>
</div>

<!-- 
Incluye el esqueleto del pie de la página. 
-->
<?php include_once 'includes/templates/footer.php'; ?>