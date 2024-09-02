<?php

namespace Controllers
{   
    use \Config;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class ContactController
    {
        public function index()
        {            
            return ['view', 'contact', null];
        }

        public function send()
        {            
            if (isset($_POST['name']))
                $name = $_POST['name'];
            else
                $name = "unknown";

            if (isset($_POST['email']))
                $email = $_POST['email'];
            else
                $email = "unknown";

            if (isset($_POST['tel']))
                $tel = $_POST['tel'];
            else
                $tel = "unknown";

            if (isset($_POST['message']))
                $message = nl2br($_POST['message']);
            else
                $message = "unknown";

            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->isSMTP();
                $mail->Host       = Config::getInstance()->MAIL_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = Config::getInstance()->MAIL_LOGIN;
                $mail->Password   = Config::getInstance()->MAIL_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // To load the French version
                $mail->CharSet    = PHPMailer::CHARSET_UTF8;

                //Recipients
                $mail->setFrom($email);
                $mail->addAddress(Config::getInstance()->MAIL_LOGIN);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = "Message depuis le site";
                $mail->Body    = '<b>Nom: </b>'.$name.'<br/><b>Téléphone: </b>'.$tel.'<br/><p>'.$message.'</p>';
                
                $mail->send();
                $serialized_data = '{"type":"success", "message":"Votre message a bien été envoyé"}';
            } catch (Exception $e) {
                $serialized_data = '{"type":"danger", "message":"Erreur lors de l\'envoi de votre message: {'.$mail->ErrorInfo.'"}';
            }
            
            return ['json', null, json_encode($serialized_data)];
        }
    }
}

?>