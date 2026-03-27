<?php
// 1. Errores en pantalla (Solo para probar, después lo borramos)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// 2. Rutas - ASEGURATE que los nombres de archivos coincidan exacto
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = isset($_POST["nombre"]) ? strip_tags(trim($_POST["nombre"])) : "Sin nombre";
    $email    = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : "";
    $telefono = isset($_POST["telefono"]) ? strip_tags(trim($_POST["telefono"])) : "";
    $mensaje  = isset($_POST["mensaje"]) ? strip_tags(trim($_POST["mensaje"])) : "";

    $mail = new PHPMailer(true);

    try {
        // --- CONFIGURACIÓN ZOHO ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.zoho.com'; // Usá el global
        $mail->SMTPAuth   = true;
        $mail->Username   = 'esaracco@ewsoltec.com.ar';
        $mail->Password   = 'xxxxxx'; // Pegala acá sin espacios
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 2525;

        // --- OPCIONES PARA SALTAR BLOQUEOS ---
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // --- DESTINATARIOS ---
        $mail->setFrom('esaracco@ewsoltec.com.ar', 'Web EWSolTec');
        $mail->addAddress('esaracco@ewsoltec.com.ar'); 
        if (!empty($email)) {
            $mail->addReplyTo($email, $nombre);
        }

        // --- CONTENIDO ---
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "Nueva consulta web: $nombre";
        
        $cuerpo = "<h3>Consulta de contacto</h3>";
        $cuerpo .= "<p><strong>Nombre:</strong> $nombre</p>";
        $cuerpo .= "<p><strong>Email:</strong> $email</p>";
        $cuerpo .= "<p><strong>Teléfono:</strong> $telefono</p>";
        $cuerpo .= "<p><strong>Mensaje:</strong><br>" . nl2br($mensaje) . "</p>";

        $mail->Body = $cuerpo;

        $mail->send();
        
        echo "<script>alert('Mensaje enviado con éxito.'); window.location.href='index.html';</script>";

    } catch (Exception $e) {
        echo "Error de PHPMailer: {$mail->ErrorInfo}";
    }
} else {
    header("Location: index.html");
}
?>