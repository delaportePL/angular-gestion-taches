<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\HttpFoundation\Response;

class MailService
{
    private $mail;

    public function __construct(private string $mailerMailSender, private $mailerPassword, private $mailerHost, private $mailerPort){
        $this->mail = new PHPMailer(true); 
        $this->mail->isSMTP();
        $this->mail->Host = $mailerHost;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $mailerMailSender;
        $this->mail->Password = $mailerPassword;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = $mailerPort;
        $this->mail->setFrom($mailerMailSender, 'Task-Management');
        $this->mail->CharSet = 'UTF-8';
        $this->mail->isHTML(true);
    }

    public function sendMailNewResponsability($email):string
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email);
            $this->mail->Subject = "Une nouvelle tâche vous a été attribuée";
            $this->mail->Body = $this->getBodyMail("Nouvelle tâche attribuée", "Une nouvelle tâche vous a été attribuée");
            $this->mail->send();
            return "Mail envoyé";
        } 
        catch (Exception $e) {
            return 'Une erreur est survenue lors de l\'envoi de l\'e-mail : ' . $this->mail->ErrorInfo;
        }
    }

    private function getBodyMail($subject, $text):string
    {
        return '<html><head></head><body>
        <section style="width: 100%; height: 100%; background-color: #f7f7f7;">
        <div style="width: 70%; height: 100%; margin: 0 auto; padding: 10px 30px 18px 30px; background: white">
        <br><h3 style="font-weight: 500; font-size: 18px; color: black; margin-bottom:15x;">' . $subject . ' </h3>
        <p style="font-size: 13.5px; font-weight: 300;  color: black; font-family: \'Google Sans\',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">   ' . $text . '</p>
        <br></div></section></body></html>';
    }
}
