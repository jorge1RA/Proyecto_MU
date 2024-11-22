<?php

/**
 * config.php
 * 
 * Archivo de configuración para el envío de correos electrónicos.
 *
 * Define las constantes necesarias para la conexión con el servidor SMTP
 * y para la configuración del remitente.
 *
 * @category Configuración del servidor SMTP.
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Dirección del servidor SMTP.
 */
define('SMTP_HOST', 'smtp.gmail.com');

/**
 * Activación de la autenticación SMTP.
 */
define('SMTP_AUTH', true);

/**
 * Nombre de usuario para la autenticación SMTP (correo electrónico).
 */
define('SMTP_USERNAME', 'jorgeromeroaiza@gmail.com');

/**
 * Contraseña para la autenticación SMTP.
 */
define('SMTP_PASSWORD', 'kyhx vioh ddsb ojyy'); // Contraseña generada por medio de 'Contraseñas de aplicación', por Google.

/**
 * Método de seguridad para la conexión SMTP (tls o ssl).
 */
define('SMTP_SECURE', 'tls');

/**
 * Puerto para la conexión SMTP (587 = TLS, 465 = SSL).
 */
define('SMTP_PORT', 587);

/**
 * Dirección de correo electrónico y nombre del remitente.
 */
define('FROM_EMAIL', 'jorgeromeroaiza@gmail.com');
define('FROM_NAME', 'ModaUrbana');
