<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../public/vendor/autoload.php';
require_once '../config/EmailConfig.php';

class ContactController
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    // Afficher la page de contact
    public function index()
    {
        require_once '../views/contact/contact.php';
    }

    // Envoyer l'email de contact via AJAX
    public function send()
    {
        header('Content-Type: application/json');

        // Récupérer les données POST
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $sujet = $_POST['sujet'] ?? '';
        $message = $_POST['message'] ?? '';

        // Validation des champs
        $errors = [];

        if (empty($nom)) {
            $errors[] = 'Le nom est requis';
        }

        if (empty($email)) {
            $errors[] = 'L\'email est requis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email n\'est pas valide';
        }

        if (empty($sujet)) {
            $errors[] = 'Le sujet est requis';
        }

        if (empty($message)) {
            $errors[] = 'Le message est requis';
        }

        // Si des erreurs existent, retourner une réponse d'erreur
        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => implode(', ', $errors)
            ]);
            return;
        }

        // Envoyer l'email
        $result = $this->sendContactEmail($nom, $email, $sujet, $message);

        if ($result === true) {
            echo json_encode([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès! Nous vous répondrons dans les plus brefs délais.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result
            ]);
        }
    }

    // Fonction pour envoyer un email de contact
    private function sendContactEmail($nom, $emailExpediteur, $sujet, $message)
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = SMTP_PORT;

            // Email de l'expéditeur (système)
            $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);

            // Email du destinataire (admin du restaurant)
            $mail->addAddress(EMAIL_TO, EMAIL_TO_NAME);

            // Répondre à l'email de l'utilisateur
            $mail->addReplyTo($emailExpediteur, $nom);

            $mail->isHTML(true);
            $mail->Subject = "Contact: $sujet";

            // Corps du mail
            $mail->Body = "
                <h2>Nouveau message de contact</h2>
                <p><strong>Nom:</strong> $nom</p>
                <p><strong>Email:</strong> $emailExpediteur</p>
                <p><strong>Sujet:</strong> $sujet</p>
                <hr>
                <h3>Message:</h3>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
                <hr>
                <p><em>Envoyé depuis le formulaire de contact de Resto Delicieux</em></p>
            ";

            $mail->AltBody = "Nouveau message de contact\n\n"
                . "Nom: $nom\n"
                . "Email: $emailExpediteur\n"
                . "Sujet: $sujet\n\n"
                . "Message:\n$message\n\n"
                . "Envoyé depuis le formulaire de contact de Resto Delicieux.";

            $mail->send();

            return true;
        } catch (Exception $e) {
            return "Erreur lors de l'envoi : " . $mail->ErrorInfo;
        }
    }
}
