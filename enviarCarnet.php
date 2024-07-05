<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generarImagenCarnet($documento, $aprendiz, $correo)
{
    // Aquí iría la lógica para generar la imagen del carnet, incluyendo el código QR
    // Por ahora, solo devolveremos una cadena base64 de ejemplo
    return 'data:image/png;base64,'; // Asegúrate de reemplazar esto por la imagen real en base64
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'] ?? '';
    $aprendiz = $_POST['aprendiz'] ?? '';
    $correo = $_POST['Correo'] ?? '';
    $dataUri = $_POST['dataUri'] ?? '';

    $imagenCarnet = generarImagenCarnet($documento, $aprendiz, $correo);

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor de correo
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jhonnygonsalez7@gmail.com';
        $mail->Password = 'zsmuewebcxralalw'; // Usa una contraseña de aplicación si tienes habilitada la verificación en dos pasos
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // O usa PHPMailer::ENCRYPTION_STARTTLS si prefieres el puerto 587
        $mail->Port = 465; // Cambia a 587 si estás usando STARTTLS

        // Configuración del remitente y del destinatario
        $mail->setFrom('your_email@example.com', 'Tu Nombre');
        $mail->addAddress($correo, $aprendiz);

        // Configuración del asunto y del cuerpo del mensaje
        $mail->isHTML(true);
        $mail->Subject = 'Tu Carnet';
        $mail->Body = "
            <h1>Hola, $aprendiz!</h1>
            <p>Aquí tienes tu carnet y el código QR:</p>
            <img src='$imagenCarnet' alt='Carnet'>
            <img src='$dataUri' alt='Código QR'>
        ";

        // Adjunta la imagen del carnet y el código QR como datos adjuntos
        $mail->addStringAttachment(base64_decode(substr($imagenCarnet, 22)), 'carnet.png', 'image/png');
        $mail->addStringAttachment(base64_decode(substr($dataUri, 22)), 'qrcode.png', 'image/png');

        // Envía el correo electrónico
        $mail->send();
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
?>


// Configuración del servidor de correo
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'jhonnygonsalez7@gmail.com';
$mail->Password = 'zsmuewebcxralalw'; // Usa una contraseña de aplicación si tienes habilitada la verificación en dos pasos
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // O usa PHPMailer::ENCRYPTION_STARTTLS si prefieres el puerto 587
$mail->Port = 465; // Cambia a 587 si estás usando STARTTLS
