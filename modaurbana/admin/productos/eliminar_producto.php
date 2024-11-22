<?php

/**
 * eliminar_producto.php
 *
 * Página para eliminar un producto específico.
 *
 * Permite al administrador eliminar un producto y sus variantes asociadas.
 *
 * @category Administración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Verifica si el usuario es administrador, si no, redirige al listado de productos.
 */
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location:/modaurbana/admin/productos/index.php');
    exit();
}


/**
 * Incluye la conexión a la base de datos y comienza la sesión.
 */
include_once '../../includes/conexion.php';


/**
 * Verifica si se ha pasado un id de producto válido.
 */
if (isset($_GET['id'])) {
    $producto_id = intval($_GET['id']);

    /**
     * Elimina las variantes del producto.
     */
    $sql_eliminar_variantes = "DELETE FROM producto_variantes WHERE producto_id = ?";
    $stmt_eliminar_variantes = $conexion->prepare($sql_eliminar_variantes);
    $stmt_eliminar_variantes->bind_param("i", $producto_id);

    if ($stmt_eliminar_variantes->execute()) {
        /**
         * Si las variantes se eliminan correctamente, elimina el producto.
         */
        $sql_eliminar_producto = "DELETE FROM productos WHERE id = ?";
        $stmt_eliminar_producto = $conexion->prepare($sql_eliminar_producto);
        $stmt_eliminar_producto->bind_param("i", $producto_id);

        if ($stmt_eliminar_producto->execute()) {
            /**
             * Redirige al listado de productos si se eliminó correctamente.
             */
            header('Location: /modaurbana/admin/productos/index.php');
            exit();
        } else {
            /**
             * Mensaje de error si ocurre un problema al eliminar el producto.
             */
            $error = "Error al eliminar el producto: " . $conexion->error;
        }
    } else {
        /**
         * Mensaje de error si ocurre un problema al eliminar las variantes.
         */
        $error = "Error al eliminar las variantes: " . $conexion->error;
    }
} else {
    /**
     * Redirige al listado de productos si no se pasa un id.
     */
    header('Location: /modaurbana/admin/productos/index.php');
    exit();
}


/**
 * Muestra un mensaje de error si ocurre algún problema.
 */
if (isset($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}
