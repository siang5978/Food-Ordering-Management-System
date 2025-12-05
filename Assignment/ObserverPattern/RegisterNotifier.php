<?php
require_once 'Observer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

class RegisterNotifier implements Observer {
    #[\Override]
    public function update(Subject $subject): void {
        if ($subject instanceof Users) {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tarumtcafe@gmail.com';
            $mail->Password = 'wvin ciff kass htsp';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tarumtcafe@gmail.com', 'YUM FOMS');
            $mail->addAddress($subject->getEmail(), $subject->getUserName());

            $mail->isHTML(true);
            $mail->Subject = 'Registration Successful';
            $mail->Body = "Hello " . $subject->getUserName() . ", your registration is successful!";

            $mail->send();
        }
    }
}
