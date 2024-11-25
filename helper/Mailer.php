<?php
require './vendor/PHPMailer/src/Exception.php';

require './vendor/PHPMailer/src/PHPMailer.php';

require './vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public $mail;

    public function __construct()
    {
        // Inicializar PHPMailer
        $this->mail = new PHPMailer(true);
    }

    public function mandarEmail($destinatario, $token){
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
            return 1;
        } catch (Exception $e) {
            return 0;
            exit();
        }
    }
}
