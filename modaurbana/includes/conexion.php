<?php

/**
 * conexion.php
 * 
 * Archivo de conexión a la base de datos.
 * 
 * Establece una conexión con la base de datos MySQL para la tienda "ModaUrbana".
 * Si la conexión falla, se muestra un mensaje de error.
 *
 * @category Configuración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 */


/**
 * Parámetros de conexión a la base de datos.
 */
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "modaurbana_db";


/**
 * Crea la conexión.
 */
$conexion = mysqli_connect($host, $usuario, $password, $base_datos);


/**
 * Verifica si la conexión fue exitosa.
 */
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
