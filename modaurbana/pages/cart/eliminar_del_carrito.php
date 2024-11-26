<?php
// eliminar_del_carrito.php

session_start();

// Verifica que la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtiene la clave única del producto a eliminar del carrito
    $clave = isset($_POST['clave']) ? $_POST['clave'] : '';

    // Verifica si la clave existe en el carrito
    if ($clave && isset($_SESSION['carrito'][$clave])) {
        unset($_SESSION['carrito'][$clave]);
        $_SESSION['success'] = "Producto eliminado del carrito.";
    } else {
        $_SESSION['error'] = "Producto no encontrado en el carrito.";
    }

    // Redirige de vuelta al carrito
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
} else {
    // Si no es una solicitud POST, redirigir al carrito
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
}
