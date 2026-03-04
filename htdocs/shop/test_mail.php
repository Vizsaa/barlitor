<?php
require_once 'includes/mail.php';

$sent = send_email(
    'your-email@example.com',  // replace with your Mailtrap inbox email
    'Test User',
    'Brutor Test Email',
    '<h1>Congrats!</h1><p>This is a test email from Brutor using Mailtrap.</p>'
);

echo $sent ? "Email sent successfully!" : "Email failed!";
