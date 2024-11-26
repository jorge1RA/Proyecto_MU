<?php

/**
 * eliminar_variante.php
 *
 * Página para eliminar una variante específica de un producto.
 *
 * Permite al administrador eliminar una variante de color/talla de un producto.
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
 * Verifica si el usuario es administrador.
 * Si no está autenticado como administrador, redirige a la página principal de administración.
 */
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header('Location: /modaurbana/admin/index.php');
    exit();
}


/**
 * Incluye el archivo de conexión a la base de datos.
 */
include_once '../../includes/conexion.php';


/**
 * Verifica si se ha proporcionado un ID de variante.
 */
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $variante_id = intval($_GET['id']);

    /**
     * Prepara la consulta para eliminar la variante del producto.
     */
    $sql_eliminar_variante = "DELETE FROM producto_variantes WHERE id = ?";
    $variante = $conexion->prepare($sql_eliminar_variante);
    $variante->bind_param("i", $variante_id);

    /**
     * Ejecuta la consulta y redirige según el resultado.
     */
    if ($variante->execute()) {
        header('Location: /modaurbana/admin/productos/index.php?mensaje=Variante eliminada correctamente.');
        exit();
    } else {
        $error = "Error al eliminar la variante: " . $conexion->error;
        header('Location: /modaurbana/admin/productos/index.php?error=' . urlencode($error));
        exit();
    }
} else {
    /**
     * Redirige si no se pasa un id válido de la variante.
     */
    header('Location: /modaurbana/admin/productos/index.php?error=' . urlencode("ID de variante no válido."));
    exit();
}
