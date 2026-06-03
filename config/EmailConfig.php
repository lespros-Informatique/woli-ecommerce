<?php

// Configuration PHPMailer pour l'envoi d'emails
define('SMTP_HOST', 'mail.jetrouvtout.com');
define('SMTP_USERNAME', 'test@jetrouvtout.com');
define('SMTP_PASSWORD', 'test@jetrouvtout');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl'); // 'ssl' pour port 465, 'tls' pour port 587

// Email de l'expéditeur (utilisé pour setFrom)
define('EMAIL_FROM', 'test@jetrouvtout.com');
define('EMAIL_FROM_NAME', 'Resto Delicieux - Formulaire de Contact');

// Email du destinataire (admin du restaurant)
define('EMAIL_TO', 'lespros1313@gmail.com');
define('EMAIL_TO_NAME', 'Administration Resto Delicieux');
