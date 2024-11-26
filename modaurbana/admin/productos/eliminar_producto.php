<?php
// eliminar_producto.php

include '../../includes/conexion.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location:/modaurbana/admin/productos/index.php');
    exit();
}

// Verificar si se ha pasado un ID de producto
if (isset($_GET['id'])) {
    $producto_id = intval($_GET['id']);

    // Eliminar primero las variantes del producto
    $sql_eliminar_variantes = "DELETE FROM producto_variantes WHERE producto_id = ?";
    $stmt_eliminar_variantes = $conexion->prepare($sql_eliminar_variantes);
    $stmt_eliminar_variantes->bind_param("i", $producto_id);
    if ($stmt_eliminar_variantes->execute()) {
        // Si se eliminaron las variantes, eliminar el producto
        $sql_eliminar_producto = "DELETE FROM productos WHERE id = ?";
        $stmt_eliminar_producto = $conexion->prepare($sql_eliminar_producto);
        $stmt_eliminar_producto->bind_param("i", $producto_id);
        if ($stmt_eliminar_producto->execute()) {
            header('Location: /modaurbana/admin/productos/index.php');
            exit();
        } else {
            $error = "Error al eliminar el producto: " . $conexion->error;
        }
    } else {
        $error = "Error al eliminar las variantes: " . $conexion->error;
    }
} else {
    header('Location: /modaurbana/admin/productos/index.php');
    exit();
}

// Mostrar error si ocurre algún problema
if (isset($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}
