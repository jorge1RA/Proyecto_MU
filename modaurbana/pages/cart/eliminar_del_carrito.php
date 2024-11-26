<?php

/**
 * eliminar_del_carrito.php
 * 
 * Página para eliminar un producto del carrito.
 * 
 * Elimina un producto específico del carrito de la sesión del usuario.
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
 * Verifica que la solicitud es POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /**
     * Obtiene la clave única del producto a eliminar del carrito.
     */
    $clave = isset($_POST['clave']) ? $_POST['clave'] : '';

    /**
     *  Verifica si la clave existe en el carrito.
     */
    if ($clave && isset($_SESSION['carrito'][$clave])) {
        /**
         * Elimina el producto del carrito.
         */
        unset($_SESSION['carrito'][$clave]);
        $_SESSION['success'] = "Producto eliminado del carrito.";
    } else {
        $_SESSION['error'] = "Producto no encontrado en el carrito.";
    }


    /**
     * Redirige de vuelta al carrito
     */
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
} else {
    /**
     * Redirige al carrito si la solicitud no es POST.
     */
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
}
