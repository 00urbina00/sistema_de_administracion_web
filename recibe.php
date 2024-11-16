<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

var_dump($_POST);

require 'vendor/autoload.php'; // Asegúrate de que el autoload de Composer esté presente

$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
$correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$comentario = filter_var($_POST['comentario'], FILTER_SANITIZE_STRING);

if (!empty($nombre) && !empty($correo) && !empty($comentario)) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'eedurbina2002@gmail.com'; // Reemplaza con tu correo de Gmail
        $mail->Password = 'cjkktsuxfhcbgnlk'; // Reemplaza con tu contraseña de Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configuración del correopo
        $mail->setFrom($correo, $nombre);
        $mail->addAddress('eedurbina2002@gmail.com'); // Reemplaza con el correo destino
        $mail->isHTML(true);
        $mail->Subject = 'Nuevo mensaje de contacto';
        $mail->Body    = "Nombre: $nombre<br>Correo: $correo<br>Comentario: $comentario";

        $mail->send();
        header("Location: contacto.php?status=success");
        exit(); // Asegúrate de terminar el script después de redirigir
    } catch (Exception $e) {
        header("Location: contacto.php?status=error");
        exit(); // Asegúrate de terminar el script después de redirigir
    }
} else {
    header("Location: contacto.php?status=invalid");
    exit(); // Asegúrate de terminar el script después de redirigir
}
?>