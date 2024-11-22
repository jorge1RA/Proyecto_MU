<?php

/**
 * tienda.php
 * 
 * Página principal de la tienda donde se muestran todos los productos disponibles.
 * Permite filtrar los productos por categoría.
 * 
 * @category Productos
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
 * Obtiene las categorías únicas disponibles en la base de datos.
 */
$sql_categorias = "SELECT DISTINCT categoria FROM productos";
$resultado_categorias = mysqli_query($conexion, $sql_categorias);


/**
 * Obtiene los productos, filtrados opcionalmente por categoría.
 */
$categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

if ($categoria_seleccionada) {

    /**
     * Sanitiza la entrada de categoría para prevenir inyecciones SQL.
     */
    $categoria_seleccionada = mysqli_real_escape_string($conexion, $categoria_seleccionada);
    $sql_productos = "SELECT * FROM productos WHERE categoria='$categoria_seleccionada'";
} else {
    /**
     * Si no se selecciona una categoría específica, muestra todos los productos.
     */
    $sql_productos = "SELECT * FROM productos";
}


/**
 * Ejecuta la consulta SQL para obtener los productos.
 */
$resultado_productos = mysqli_query($conexion, $sql_productos);


/**
 * Incluye el esqueleto de la cabecera de la página.
 */
include_once '../../includes/templates/header.php';
?>


<!--  
Contenedor Principal.
-->
<div class="container mt-4">
    <h2 class="mb-4">Nuestra Tienda</h2>

    <!-- 
    Filtro de Categorías. 
    -->
    <div class="mb-4">

        <!-- 
        Botón para mostrar todos los productos. 
        -->
        <a href="/modaurbana/pages/products/tienda.php" class="btn btn-secondary">Todas</a>

        <!-- 
        Botones de filtro por cada categoría disponible. 
        -->
        <?php while ($categoria = mysqli_fetch_assoc($resultado_categorias)): ?>
            <a href="/modaurbana/pages/products/tienda.php?categoria=<?php echo urlencode($categoria['categoria']); ?>" class="btn btn-secondary">
                <?php echo ucfirst(htmlspecialchars($categoria['categoria'])); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- 
    Lista de Productos. 
    -->
    <div class="row">
        <?php while ($producto = mysqli_fetch_assoc($resultado_productos)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($producto['imagen']): ?>

                        <!-- 
                        Enlace con imagen del producto para redirigir a la página de detalles. 
                        -->
                        <a href="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>">
                            <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" style="height: 300px; object-fit: cover;">
                        </a>

                    <?php else: ?>
                        <!-- 
                        Imagen por defecto si no hay imagen disponible. 
                        -->
                        <a href="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>">
                            <img src="/modaurbana/assets/img/" class="card-img-top" alt="Producto">
                        </a>
                    <?php endif; ?>

                    <div class="card-body">
                        <!-- 
                        Nombre del producto. 
                        -->
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                        <!-- 
                        Descripción del producto. 
                        -->
                        <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    </div>

                    <div class="card-footer">
                        <!-- 
                        Precio del producto. 
                        -->
                        <p class="card-text"><?php echo number_format($producto['precio'], 2); ?>€</p>
                        <!-- 
                        Cambia el enlace para redirigir a producto.php en lugar de agregar directamente al carrito. 
                        -->
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
<?php include_once '../../includes/templates/footer.php'; ?>