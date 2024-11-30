<?php
//index.php - admin


include 'includes/conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/admin/index.php');
    exit();
}

// Redirige a la gestión de productos
header('Location: /modaurbana/admin/productos/index.php');
exit();
