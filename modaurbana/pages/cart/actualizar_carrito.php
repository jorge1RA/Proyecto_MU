<?php
// actualizar_carrito.php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y sanitizar los datos del formulario
    $clave = isset($_POST['clave']) ? $_POST['clave'] : '';
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    // Verificar si la variante existe en el carrito
    if ($clave && isset($_SESSION['carrito'][$clave])) {
        // Validar la cantidad
        if ($cantidad < 1) {
            $_SESSION['error'] = "La cantidad debe ser al menos 1.";
        } elseif ($cantidad > intval($_SESSION['carrito'][$clave]['stock'])) {
            $_SESSION['error'] = "No hay suficiente stock disponible.";
        } else {
            // Actualizar la cantidad
            $_SESSION['carrito'][$clave]['cantidad'] = $cantidad;
            $_SESSION['success'] = "Cantidad actualizada en el carrito.";
        }
    } else {
        $_SESSION['error'] = "Producto no encontrado en el carrito.";
    }

    // Redirigir de vuelta al carrito
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
} else {
    // Si no es una solicitud POST, redirigir al carrito
    header('Location: /modaurbana/pages/cart/carrito.php');
    exit();
}
