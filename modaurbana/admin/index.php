<?php

session_start();


// Verificar si el usuario es administrador - Pendiente de probar funcionamiento y habilitar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}


include_once '../../includes/conexion.php';

header('Location: /modaurbana/admin/productos/index.php');
exit();
