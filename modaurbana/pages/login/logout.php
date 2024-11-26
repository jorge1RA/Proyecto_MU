<?php

/**
 * logout.php
 * 
 * Página para cerrar la sesión del usuario.
 * Destruye la sesión actual y redirige al usuario a la página de inicio.
 * 
 * @category Autenticación
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Inicia la sesión para poder manipularla.
 */
session_start();

/**
 * Eliminar todas las variables de sesión.
 */
session_unset();

/**
 * Destruye la sesión.
 */
session_destroy();

/**
 * Redirige al usuario a la página de inicio.
 */
header("Location: /modaurbana/index.php");
exit();
