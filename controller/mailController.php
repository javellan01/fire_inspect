<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require("./dist/phpmailer/Exception.php");
require("./dist/phpmailer/PHPMailer.php");
require("./dist/phpmailer/SMTP.php");

function sendNewLogin($user,$password){
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$mlogin = include("./config/mail.php");
try {
    //Server settings
    $mail->isSMTP(true);                                        //Send using SMTP
    $mail->Host       = $mlogin['host'];                   //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $mlogin['username'];    //SMTP username
    $mail->Password   = $mlogin['password'];                            //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->CharSet    = 'UTF-8';                //Charset para letras no brasil
    //Recipients
    $mail->setFrom('app.sistema@firesystems-am.com.br', 'Sistema FireSystems-AM');
    $mail->addAddress($user);                            //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
   //$mail->addReplyTo('info@example.com', 'Information');
   // $mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Login de Acesso - Sistema de Gerenciamento FireSystems-AM';
    $mail->Body    =  include("./messages/sendNewLogin.php");
    $mail->AltBody = 'Senha de Acesso ao Sistema FireSystems: '.$password;
    
    $mail->send();

    echo 'Mensagem Enviada com Sucesso!';
    } catch (Exception $e) {
        echo "Mensagem não pôde ser enviada. User:".$mlogin['username']." Error: {$mail->ErrorInfo}";
    }
}

function sendMedicao($users,$medicao){
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mlogin = include("./config/mail.php");
    try {
        //Server settings
        $mail->isSMTP(true);                                        //Send using SMTP
        $mail->Host       = $mlogin['host'];                   //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $mlogin['username'];    //SMTP username
        $mail->Password   = $mlogin['password'];                            //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->CharSet    = 'UTF-8';                //Charset para letras no brasil
        //Recipients
        $mail->setFrom('app.sistema@firesystems-am.com.br', 'Sistema FireSystems-AM');
        foreach($users as $user){
            $mail->addAddress($user);
        }
        //Add a recipient
       // $mail->addAddress('ellen@example.com');               //Name is optional
       //$mail->addReplyTo('info@example.com', 'Information');
       // $mail->addCC('cc@example.com');
       // $mail->addBCC('bcc@example.com');
    
        //Attachments
       // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
       // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Lançamento de Medição - Sistema de Gerenciamento FireSystems-AM';
        $mail->Body    = include("./messages/sendMedicao.php");
        $mail->AltBody = 'Medição Disponível no Sistema de Gerenciamento.';
        
        $mail->send();
    
        echo 'Mensagem Enviada com Sucesso!';
        } catch (Exception $e) {
            echo "Mensagem não pôde ser enviada. Error: {$mail->ErrorInfo}";
        }
    }
