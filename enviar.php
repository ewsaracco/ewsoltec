<?php
/**
 * Script de envío de formulario para EWSolTec
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibimos los datos y los limpiamos un poco
    $nombre = strip_tags(trim($_POST["nombre"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $telefono = strip_tags(trim($_POST["telefono"]));
    $mensaje = strip_tags(trim($_POST["mensaje"]));

    // --- CONFIGURACIÓN ---
    // 1. Poné acá el mail donde querés RECIBIR las consultas:
    $destinatario = "esaracco@ewsoltec.com.ar"; 
    
    // 2. Asunto del mail que te va a llegar
    $asunto = "Nueva consulta web de: $nombre";

    // 3. Diseño del contenido del mail
    $contenido = "Detalles del contacto de EWSolTec:\n\n";
    $contenido .= "Nombre: $nombre\n";
    $contenido .= "Email: $email\n";
    $contenido .= "Teléfono: " . ($telefono ?: "No especificado") . "\n\n";
    $contenido .= "Mensaje:\n$mensaje\n";

    // 4. Cabeceras (Importante: 'From' debe ser un mail de tu propio dominio)
    $headers = "From: esaracco@ewsoltec.com.ar" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // 5. Envío y respuesta al usuario
    if (mail($destinatario, $asunto, $contenido, $headers)) {
        // Si sale bien, tira un alerta y vuelve al inicio
        echo "<script>
                alert('Mensaje enviado con éxito.');
                window.location.href='index.html';
              </script>";
    } else {
        echo "Lo siento, hubo un error al enviar el mensaje. Intente nuevamente más tarde.";
    }
} else {
    // Si alguien intenta entrar al .php directamente, lo mandamos al index
    header("Location: index.html");
}
?>