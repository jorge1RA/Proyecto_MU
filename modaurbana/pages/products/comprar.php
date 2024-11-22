<?php

/**
 * comprar.php
 * 
 * Página para realizar la compra de un producto.
 * Muestra la información del producto que el usuario ha seleccionado para comprar.
 * 
 * @category Compra
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Verifica si el usuario está autenticado y es administrador.
 * Si no cumple las condiciones, redirige al login.
 */
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Verifica si se ha proporcionado un id de producto.
 */
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    /**
     * Obtiene la información del producto desde la base de datos.
     */
    $sql = "SELECT * FROM productos WHERE id='$producto_id'";
    $resultado = mysqli_query($conexion, $sql);
    $producto = mysqli_fetch_assoc($resultado);


    /**
     * Verifica si el producto existe,
     * sí el producto se encuentra, muestra la información del mismo.
     */
    if ($producto) {

        /**
         * Muestra el nombre y precio del producto seleccionado para comprar.
         */
        echo "<h2>Has comprado: " . $producto['nombre'] . "</h2>";
        echo "<p>Precio: €" . number_format($producto['precio'], 2) . "</p>";
        echo "<a href='index.php'>Volver a la tienda</a>";
    } else {
        /**
         * Mensaje de error si el producto no fue encontrado.
         */
        echo "Producto no encontrado.";
    }
} else {
    /**
     * Mensaje de error si no se ha proporcionado un id de producto.
     */
    echo "No se ha seleccionado ningún producto.";
}
