<?php
require './vendor/PHPMailer/src/Exception.php';

require './vendor/PHPMailer/src/PHPMailer.php';

require './vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public $mail;

    public function __construct($destinatario, $token)
    {
        // Inicializar PHPMailer
        $this->mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'ursomartin89@gmail.com'; // Correo remitente
            $this->mail->Password = 'wxbw rqzx qzbl qqlz'; // Contraseña SMTP
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;

            // Configuración del correo
            $this->mail->setFrom('pyr.pw2@gmail.com', 'Preguntas y respuestas');
            $this->mail->addAddress($destinatario); // Destinatario
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Confirmación de correo';
            $this->mail->Body = "<h3>Ingrese al siguiente link para confirmar el registro: <a href='http://localhost/registro/ingresoPorEmail/" . $token . "'>Link</a></h3>";

            // Enviar el correo
            $this->mail->send();
            echo 'El correo ha sido enviado con éxito';
        } catch (Exception $e) {
            echo "El correo no pudo ser enviado. Error: {$this->mail->ErrorInfo}";
            exit();
        }
    }
}
