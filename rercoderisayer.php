<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Correo = $_POST['Correo'] ?? '';
    $dataUri = $_POST['dataUri'] ?? '';

    // Valida el correo electrónico
    if (!filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Formato de correo electrónico inválido.";
        exit; // Detiene la ejecución del script si el correo no es válido
    }

    // Decodifica la imagen de base64
    $dataUri = str_replace('data:image/png;base64,', '', $dataUri);
    $dataUri = str_replace(' ', '+', $dataUri);
    $imagenBinaria = base64_decode($dataUri);

    // Guarda la imagen en el servidor temporalmente
    $rutaImagenTemporal = sys_get_temp_dir() . '/carnet_' . uniqid() . '.png';
    if (file_put_contents($rutaImagenTemporal, $imagenBinaria) === false) {
        echo "Error al guardar la imagen temporal.";
        exit; // Detiene la ejecución si no se puede guardar la imagen
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jhonnygonsalez7@gmail.com';
        $mail->Password = 'zsmuewebcxralalw'; // Usa una contraseña de aplicación si tienes habilitada la verificación en dos pasos
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // O usa PHPMailer::ENCRYPTION_STARTTLS si prefieres el puerto 587
        $mail->Port = 465; // Cambia a 587 si estás usando STARTTLS

        // Remitentes y destinatarios
        $mail->setFrom('tu_email@example.com', 'Nombre del Remitente'); // Cambia esto por tu dirección de correo y nombre
        $mail->addAddress($Correo); // Usa el correo del aprendiz recibido

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Tu Carnet';
        $mail->Body    = 'Hola, aquí está tu carnet.';
        $mail->AltBody = 'Hola, aquí está tu carnet.';

        // Adjunta el carnet
        $mail->addAttachment($rutaImagenTemporal, 'carnet.png');

        $mail->send();
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
    } finally {
        // Elimina la imagen temporal
        unlink($rutaImagenTemporal);
    }
} else {
    echo 'Método no permitido';
}



$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'jhonnygonsalez7@gmail.com';
$mail->Password = 'zsmuewebcxralalw'; // Usa una contraseña de aplicación si tienes habilitada la verificación en dos pasos
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // O usa PHPMailer::ENCRYPTION_STARTTLS si prefieres el puerto 587
$mail->Port = 465; // Cambia a 587 si estás usando STARTTLS


