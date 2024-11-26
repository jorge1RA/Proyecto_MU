<?php
//eliminar.php


include '../../includes/conexion.php';
session_start();

// Verifica si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/admin/index.php');
    exit();
}

// Obtiene el ID del producto a eliminar
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Obtiene los datos del producto
    $sql = "SELECT * FROM productos WHERE id='$producto_id'";
    $resultado = mysqli_query($conexion, $sql);
    $producto = mysqli_fetch_assoc($resultado);

    if ($producto) {
        // Elimina la imagen del producto si existe
        if ($producto['imagen'] && file_exists('/modaurbana/assets/img/' . $producto['imagen'])) {
            unlink('/modaurbana/assets/img/' . $producto['imagen']);
        }

        // Elimina el producto de la base de datos
        $sql = "DELETE FROM productos WHERE id='$producto_id'";
        if (mysqli_query($conexion, $sql)) {
            // Redirigir al listado de productos
            header('Location: /modaurbana/admin/index.php');
            exit();
        } else {
            echo "Error al eliminar el producto: " . mysqli_error($conexion);
        }
    } else {
        echo "Producto no encontrado.";
    }
} else {
    header('Location: /modaurbana/admin/index.php');
    exit();
}
