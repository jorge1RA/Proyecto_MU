<?php

/**
 * actualizar_carrito.php
 * 
 * Archivo para actualizar la cantidad de productos en el carrito.
 * 
 * Verifica y actualiza la cantidad de una variante del producto en el carrito de sesión.
 * 
 * @category Carrito
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Verifica si la solicitud es de tipo POST,
 * sí no lo es, redirige de vuelta al carrito.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /**
     * Obtiene y sanitiza los datos del formulario.
     * 
     * ID del producto variante del carrito.
     */
    $clave = isset($_POST['clave']) ? $_POST['clave'] : '';
    /**
     * Cantidad deseada.
     */
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;


    /**
     * Verificar si la variante existe en el carrito de sesión.
     */
    if ($clave && isset($_SESSION['carrito'][$clave])) {

        /**
         * Validar la cantidad solicitada.
         */
        if ($cantidad < 1) {
            /**
             * Sí la cantidad es menor a 1, mostrar error.
             */
            $_SESSION['error'] = "La cantidad debe ser al menos 1.";
        } elseif ($cantidad > intval($_SESSION['carrito'][$clave]['stock'])) {
            /**
             * Sí la cantidad excede el stock disponible, mostrar error.
             */
            $_SESSION['error'] = "No hay suficiente stock disponible.";
        } else {
            /**
             * Sí la validación es exitosa, actualizar la cantidad en el carrito.
             */
            $_SESSION['carrito'][$clave]['cantidad'] = $cantidad;
            $_SESSION['success'] = "Cantidad actualizada en el carrito.";
        }
    } else {
        /**
         * Sí el producto no existe en el carrito, mostrar error.
         */
        $_SESSION['error'] = "Producto no encontrado en el carrito.";
    }


    /**
     * Redirige de vuelta al carrito después de procesar.
     */
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
} else {
    /**
     * Sí no es una solicitud POST, redirigir al carrito.
     */
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
}
