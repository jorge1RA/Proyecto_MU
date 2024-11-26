<?php
// includes/email/enviar_correo.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer.php';
require __DIR__ . '/../PHPMailer/SMTP.php';
require 'config.php';

function enviarCorreoBienvenida($email, $nombre)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($email, $nombre);

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

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo de bienvenida: {$mail->ErrorInfo}");
        return false;
    }
}

function enviarCorreoConfirmacionPedido($email, $nombre, $pedido_id)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($email, $nombre);

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

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo de confirmación de pedido: {$mail->ErrorInfo}");
        return false;
    }
}
