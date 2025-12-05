<?php
require_once 'Observer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

class SecurityNotifier implements Observer {
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
            $mail->Subject = 'Account Locked - Security Alert';
            $mail->Body = "Hello " . $subject->getUserName() .
                    ",<br><br>Your account has been locked due to multiple failed login attempts.<br>" .
                    "Please wait 1 minutes before trying again.<br>If this wasn't you, please reset your password immediately.";

            $mail->send();
        }
    }
}
