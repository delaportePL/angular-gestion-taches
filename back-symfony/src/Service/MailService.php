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
            $this->mail->Subject = "Nouvelle tâche";
            $this->mail->Body = $this->getBodyMail("Nouvelle tâche attribuée");
            $this->mail->send();
            return "Mail envoyé";
        } 
        catch (Exception $e) {
            return 'Une erreur est survenue lors de l\'envoi de l\'e-mail : ' . $this->mail->ErrorInfo;
        }
    }

    private function getBodyMail($subject):string
    {
        return '<html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Modèle de Courriel</title>
            <style>
                body {
                    font-family: \'Arial\', sans-serif;
                    background-color: #f7f7f7;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 70%;
                    margin: 0 auto;
                    padding: 20px;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                    text-align: center;
                }
                h3 {
                    font-weight: 700;
                    font-size: 24px;
                    color: #333;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 16px;
                    font-weight: 400;
                    color: #666;
                    line-height: 1.5;
                    margin-bottom: 20px;
                }
                .button {
                    display: inline-block;
                    background-color: #007bff;
                    color: #fff;
                    padding: 10px 20px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-size: 16px;
                    transition: background-color 0.3s ease;
                }
                .button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h3>' . $subject . '</h3>
                <p>Bonjour,</p>
                <p>Une tâche vous a été assignée. Vous pouvez cliquer ci-dessous pour ouvrir les détails :</p>
                <a href="http://localhost:4200/task/idTask" class="button">Ouvrir la tâche</a>
                <p>Cordialement</p>
            </div>
        </body>
        </html>
        ';
    }
}
