<?php
//index.php - principal (INICIO)


include 'includes/conexion.php';
session_start();

$sql = "SELECT * FROM productos";
$resultado = mysqli_query($conexion, $sql);
?>

<?php include 'includes/templates/header.php'; ?>
<div class="container mt-4">
    <h2 class="mb-4">Productos Destacados</h2>
    <div class="row">
        <?php while ($producto = mysqli_fetch_assoc($resultado)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($producto['imagen']): ?>
                        <!-- Envuelve la imagen en un enlace a producto.php pasando el id -->
                        <a href="pages/products/productos.php?id=<?php echo intval($producto['id']); ?>">
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
                        <a href="/modaurbana/pages/products/productos.php?id=<?php echo intval($producto['id']); ?>" class="btn btn-primary">Comprar</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include 'includes/templates/footer.php'; ?>