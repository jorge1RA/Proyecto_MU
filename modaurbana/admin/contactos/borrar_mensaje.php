<?php

/**
 * borrar_mensaje.php
 * 
 * Página para eliminar un mensaje de contacto.
 *
 * Permite al administrador eliminar un mensaje de contacto recibido.
 *
 * @category Administración
 * @package  ModaUrbana
 * @author   Jorge Romero
 */


/**
 * Inicia una nueva sesión o reanuda la existente.
 */
session_start();


/**
 * Verifica si el usuario está autenticado y es administrador.
 * Si no cumple las condiciones, redirige al login.
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
 * Verifica si el parámetro 'id' está presente en la URL.
 */
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];


    /**
     * Sentencia SQL para eliminar el mensaje con el ID proporcionado.
     */
    $sql = "DELETE FROM contactos WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    if ($stmt) {
        /**
         * Vincula el parámetro.
         */
        mysqli_stmt_bind_param($stmt, "i", $id);
        /**
         * Ejecuta la consulta.
         */
        if (mysqli_stmt_execute($stmt)) {
            /**
             * Redirige a la página de ver_contactos con un mensaje de éxito.
             */
            header('Location: ver_contactos.php?mensaje=borrado_exito');
            exit();
        } else {
            /**
             * Redirige a la página de ver_contactos con un mensaje de error.
             */
            header('Location: ver_contactos.php?mensaje=borrado_error');
            exit();
        }

        /**
         * Cierre del statement.
         */
        mysqli_stmt_close($stmt);
    }
}

/**
 * Cierra la conexión a la base de datos.
 */
mysqli_close($conexion);


/**
 * Si no se recibe un 'id' válido, redirige a ver_contactos.php.
 */
header('Location: ver_contactos.php');
exit();
