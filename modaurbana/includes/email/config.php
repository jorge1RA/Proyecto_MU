<?php

/**
 * config.php
 * 
 * Archivo de configuración para el envío de correos electrónicos.
 *
 * Define las constantes necesarias para la conexión con el servidor SMTP
 * y para la configuración del remitente.
 *
 * @category Configuración
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 * 
 */


/**
 * Dirección del servidor SMTP.
 *
 * @var string SMTP_HOST Dirección del servidor SMTP a utilizar para el envío de correos.
 */
define('SMTP_HOST', 'smtp.gmail.com');


/**
 * Activación de la autenticación SMTP.
 *
 * @var bool SMTP_AUTH Indica si la autenticación SMTP está habilitada.
 */
define('SMTP_AUTH', true);


/**
 * Nombre de usuario para la autenticación SMTP (correo electrónico).
 *
 * @var string SMTP_USERNAME Nombre de usuario del servidor SMTP.
 */
define('SMTP_USERNAME', 'jorgeromeroaiza@gmail.com');


/**
 * Contraseña para la autenticación SMTP.
 *
 * @var string SMTP_PASSWORD Contraseña para la autenticación SMTP.
 * @note Utiliza una contraseña generada a través de 'Contraseñas de aplicación' de Google.
 */
define('SMTP_PASSWORD', 'kyhx vioh ddsb ojyy'); // Contraseña generada por medio de 'Contraseñas de aplicación', por Google.


/**
 * Método de seguridad para la conexión SMTP (tls o ssl).
 *
 * @var string SMTP_SECURE Define el método de seguridad utilizado en la conexión (TLS o SSL).
 */
define('SMTP_SECURE', 'tls');


/**
 * Puerto para la conexión SMTP (587 = TLS, 465 = SSL).
 *
 * @var int SMTP_PORT Puerto del servidor SMTP.
 */
define('SMTP_PORT', 587);


/**
 * Dirección de correo electrónico del remitente.
 *
 * @var string FROM_EMAIL Correo electrónico utilizado como remitente.
 */
define('FROM_EMAIL', 'jorgeromeroaiza@gmail.com');


/**
 * Nombre del remitente de los correos electrónicos.
 *
 * @var string FROM_NAME Nombre del remitente utilizado para los correos electrónicos.
 */
define('FROM_NAME', 'ModaUrbana');
