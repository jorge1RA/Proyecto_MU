<?php
//tienda.php


include '../../includes/conexion.php';
session_start();

// Obtiene las categorías
$sql_categorias = "SELECT DISTINCT categoria FROM productos";
$resultado_categorias = mysqli_query($conexion, $sql_categorias);

// Obteniene los productos (filtrados por categoría)
$categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

if ($categoria_seleccionada) {
    // Sanitiza la entrada para prevenir inyecciones SQL
    $categoria_seleccionada = mysqli_real_escape_string($conexion, $categoria_seleccionada);
    $sql_productos = "SELECT * FROM productos WHERE categoria='$categoria_seleccionada'";
} else {
    $sql_productos = "SELECT * FROM productos";
}

$resultado_productos = mysqli_query($conexion, $sql_productos);
?>

<?php include '../../includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2 class="mb-4">Nuestra Tienda</h2>
    <!-- Categorías -->
    <div class="mb-4">
        <a href="/modaurbana/pages/products/tienda.php" class="btn btn-secondary">Todas</a>
        <?php while ($categoria = mysqli_fetch_assoc($resultado_categorias)): ?>
            <a href="/modaurbana/pages/products/tienda.php?categoria=<?php echo urlencode($categoria['categoria']); ?>" class="btn btn-secondary">
                <?php echo ucfirst(htmlspecialchars($categoria['categoria'])); ?>
            </a>
        <?php endwhile; ?>
    </div>
    <!-- Productos -->
    <div class="row">
        <?php while ($producto = mysqli_fetch_assoc($resultado_productos)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($producto['imagen']): ?>
                        <!-- Envuelve la imagen en un enlace a producto.php pasando el id -->
                        <a href="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>">
                            <img src="/modaurbana/assets/img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" style="height: 300px; object-fit: cover;">
                        </a>
                    <?php else: ?>
                        <a href="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>">
                            <img src="/modaurbana/assets/img/" class="card-img-top" alt="Producto">
                        </a>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    </div>
                    <div class="card-footer">
                        <p class="card-text"><?php echo number_format($producto['precio'], 2); ?>€</p>
                        <!-- Cambia el enlace para redirigir a producto.php en lugar de agregar directamente al carrito -->
                        <a href="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>" class="btn btn-primary">Comprar</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include '../../includes/templates/footer.php'; ?>