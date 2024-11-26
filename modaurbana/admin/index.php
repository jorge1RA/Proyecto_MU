<?php

/**
 * index.php - admin
 *
 * Página inicial para la administración.
 *
 * Verifica si el usuario es administrador y redirige a la gestión de productos.
 *
 * @category Administración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia sesión y conecta con la base de datos.
 */
session_start();


/**
 * Verifica si el usuario es administrador, si no, redirige al login.
 */
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/pages/login/login.php');
    exit();
}


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Redirige al administrador a la página de gestión de productos.
 */
header('Location: /modaurbana/admin/productos/index.php');
exit();
