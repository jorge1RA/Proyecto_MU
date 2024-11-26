<?php
//comprar.php


include '../../includes/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Obtiene información del producto
    $sql = "SELECT * FROM productos WHERE id='$producto_id'";
    $resultado = mysqli_query($conexion, $sql);
    $producto = mysqli_fetch_assoc($resultado);

    if ($producto) {
        // Aquí puedes agregar el producto al carrito o procesar la compra
        echo "<h2>Has comprado: " . $producto['nombre'] . "</h2>";
        echo "<p>Precio: €" . number_format($producto['precio'], 2) . "</p>";
        echo "<a href='index.php'>Volver a la tienda</a>";
    } else {
        echo "Producto no encontrado.";
    }
} else {
    echo "No se ha seleccionado ningún producto.";
}
