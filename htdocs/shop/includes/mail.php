<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function send_email($to_email, $to_name, $subject, $body_html, $body_plain = '', $attachments = []) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '60a3a5954978be'; // NEW Mailtrap username
        $mail->Password   = 'e5b26642fa90f2'; // NEW Mailtrap password  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('no-reply@brutor.com', 'Brutor Shop');
        $mail->addAddress($to_email, $to_name);

        // Attachments (array of file paths or associative arrays ['path'=>..., 'name'=>...])
        if (!empty($attachments) && is_array($attachments)) {
            foreach ($attachments as $att) {
                if (is_string($att) && file_exists($att)) {
                    $mail->addAttachment($att);
                } elseif (is_array($att) && !empty($att['path']) && file_exists($att['path'])) {
                    $name = isset($att['name']) ? $att['name'] : null;
                    if ($name) $mail->addAttachment($att['path'], $name);
                    else $mail->addAttachment($att['path']);
                }
            }
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body_html;
        $mail->AltBody = $body_plain ?: strip_tags($body_html);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
