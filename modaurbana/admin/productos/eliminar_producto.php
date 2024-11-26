<?php
session_start();

// Verificar si el usuario es administrador - Pendiente de probar funcionamiento y habilitar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/admin/productos/index.php');
    exit();
}

// Eliminar Productos
if (isset($_GET['id'])) {
    echo "<div class='alert alert-info'>Eliminar productos.</div>";
} else {
    header('Location: /modaurbana/admin/productos/index.php');
    exit();
}

// Redirige al listado de productos después de mostrar el mensaje, sino a inicio a los 3s
header('refresh:3;url=/modaurbana/admin/productos/index.php');

