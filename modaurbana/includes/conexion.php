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
 * 
 * @var string $host      Dirección del servidor de la base de datos.
 * @var string $usuario   Usuario con permisos de acceso a la base de datos.
 * @var string $password  Contraseña del usuario para la conexión.
 * @var string $base_datos Nombre de la base de datos a la que se conecta.
 */
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "modaurbana_db";


/**
 * Crea la conexión con la base de datos.
 * 
 * @var mysqli $conexion  Objeto de conexión a la base de datos.
 */
$conexion = mysqli_connect($host, $usuario, $password, $base_datos);


/**
 * Verifica si la conexión fue exitosa.
 * Si la conexión falla, se detiene la ejecución y se muestra un mensaje de error.
 */
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
