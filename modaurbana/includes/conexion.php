<?php
//conexion.php


$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "modaurbana_db";

$conexion = mysqli_connect($host, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
