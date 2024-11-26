<?php

/**
 * enviar_correo.php
 * 
 * Archivo para enviar correos electrónicos utilizando la librería PHPMailer.
 * 
 * Contiene funciones para enviar correos de bienvenida y de confirmación de pedidos
 * a los usuarios registrados en ModaUrbana.
 * 
 * @category Envío de correos
 * @package  ModaUrbana
 * @author   Jorge Romero Ariza
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/**
 * Carga las clases necesarias de PHPMailer y el archivo de configuración.
 */
require __DIR__ . '/../PHPMailer/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer.php';
require __DIR__ . '/../PHPMailer/SMTP.php';
require 'config.php';


/**
 * Envía un correo electrónico de bienvenida al nuevo usuario.
 *
 * @param string $email  Dirección de correo electrónico del destinatario.
 * @param string $nombre Nombre del destinatario.
 * @return bool          Devuelve true si el correo se envió correctamente, false si ocurrió un error.
 * 
 * @throws Exception Si ocurre un error durante el envío del correo.
 */
function enviarCorreoBienvenida($email, $nombre)
{
    $mail = new PHPMailer(true);
    try {
        /**
         * Configuración del servidor SMTP.
         * @throws Exception En caso de que haya un problema al establecer la configuración SMTP.
         */
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        /**
         * Configuración del remitente y destinatario.
         */
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($email, $nombre);

        /**
         * Contenido del correo.
         */
        $mail->isHTML(true);
        $mail->Subject = 'Bienvenido a ModaUrbana';
        $mail->Body = "
            <html>
            <head><meta charset='UTF-8'></head>
            <body>
                <p>Hola $nombre,</p>
                <p>Gracias por registrarte en ModaUrbana. Esperamos que disfrutes de tu experiencia.</p>
                <p>¡Saludos!</p>
                <p>Equipo de ModaUrbana</p>
            </body>
            </html>
        ";
        $mail->AltBody = "Hola $nombre,\n\nGracias por registrarte en ModaUrbana. ¡Esperamos que disfrutes de tu experiencia!\n\nEquipo de ModaUrbana";


        /**
         * Envía el correo.
         * 
         * @throws Exception Si ocurre un error al enviar el correo.
         */
        $mail->send();
        return true;
    } catch (Exception $e) {
        /**
         * Registro del error si el correo no se pudo enviar.
         */
        error_log("Error al enviar correo de bienvenida: {$mail->ErrorInfo}");
        return false;
    }
}


/**
 * Envía un correo electrónico de confirmación de pedido al usuario.
 *
 * @param string $email      Dirección de correo electrónico del destinatario.
 * @param string $nombre     Nombre del destinatario.
 * @param int    $pedido_id  ID del pedido del usuario.
 * @return bool              Devuelve true si el correo se envió correctamente, false si ocurrió un error.
 * 
 * @throws Exception Si ocurre un error durante el envío del correo.
 */
function enviarCorreoConfirmacionPedido($email, $nombre, $pedido_id)
{
    $mail = new PHPMailer(true);
    try {
        /**
         * Configuración del servidor SMTP.
         * @throws Exception En caso de que haya un problema al establecer la configuración SMTP.
         */
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        /**
         * Configuración del remitente y destinatario.
         */
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($email, $nombre);

        /**
         * Contenido del correo.
         */
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de tu pedido en ModaUrbana';
        $mail->Body = "
            <html>
            <head><meta charset='UTF-8'></head>
            <body>
                <p>Hola $nombre,</p>
                <p>Tu pedido número <strong>$pedido_id</strong> ha sido procesado con éxito.</p>
                <p>¡Gracias por comprar en ModaUrbana!</p>
            </body>
            </html>
        ";
        $mail->AltBody = "Hola $nombre,\n\nTu pedido número $pedido_id ha sido procesado con éxito.\n\n¡Gracias por comprar en ModaUrbana!";

        /**
         * Envía el correo.
         * 
         * @throws Exception Si ocurre un error al enviar el correo.
         */
        $mail->send();
        return true;
    } catch (Exception $e) {
        /**
         * Registro del error si el correo no se pudo enviar.
         */
        error_log("Error al enviar correo de confirmación de pedido: {$mail->ErrorInfo}");
        return false;
    }
}
