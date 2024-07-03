<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Suponiendo que tienes una función que genera la imagen del carnet y devuelve la imagen en base64
// Esta función es solo un ejemplo y debe ser reemplazada por tu implementación real
function generarImagenCarnet($documento, $aprendiz, $correo) {
    // Aquí iría la lógica para generar la imagen del carnet, incluyendo el código QR
    // Por ahora, solo devolveremos una cadena base64 de ejemplo
    return 'data:image/png;base64,'; // Asegúrate de reemplazar esto por la imagen real en base64
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'] ?? '';
    $aprendiz = $_POST['aprendiz'] ?? '';
    $correo = $_POST['Correo'] ?? '';
    $dataUri = generarImagenCarnet($documento, $aprendiz, $correo);

    // Crear una instancia de PHPMailer
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
        
        
        // Destinatarios
        $mail->setFrom('de@example.com', 'Emisor');
        $mail->addAddress($correo, $aprendiz); // Añade un destinatario

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Tu Carnet';
        $mail->Body    = '<h1>Hola ' . $aprendiz . '</h1><p>Aquí está tu carnet.</p><img src="' . $dataUri . '" alt="Carnet">';
        $mail->AltBody = 'Hola ' . $aprendiz . '. Aquí está tu carnet.';

        $mail->send();
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Error de PHPMailer: {$mail->ErrorInfo}";
    }
} else {
    echo 'Método de solicitud no permitido';
}



