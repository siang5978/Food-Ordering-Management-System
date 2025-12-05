<?php
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Model/Users.php';

//Observer Design Pattern
require_once __DIR__ . '/../ObserverPattern/RegisterNotifier.php';
require_once __DIR__ . '/../ObserverPattern/UpdateProfileNotifier.php';
require_once __DIR__ . '/../ObserverPattern/ChangePasswordNotifier.php';
require_once __DIR__ . '/../ObserverPattern/SecurityNotifier.php';

//email library
require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

class AccountController extends \Core\BaseController{
    //login page
    public function login(){
        $this->display(__DIR__ . '/../View/Login.php');
    }
    
    //register page
    public function register(){
        $this->display(__DIR__ . '/../View/Register.php');
    }
    
    //auth function
    public function authenticate(){
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
            $errors[] = "Username must be 3-20 characters, letters/numbers/underscore only.";
        }
        
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }
        
        if (!empty($errors)) {
            $errorMessage = implode("<br>", $errors);
            $this->display(__DIR__ . '/../View/Login.php', ['errorMessage' => $errorMessage]);
            return;
        }

        $user = Users::findByUsername($username);
        if (!$user) {
            $this->display(__DIR__ . '/../View/Login.php', ['errorMessage' => "Invalid username or password."]);
            return;
        }
        
        if ($user->getLockUntil() && strtotime($user->getLockUntil()) > time()) {
            $remaining = ceil((strtotime($user->getLockUntil()) - time()) / 60);
            $errorMessage = "Account locked. Try again in $remaining minutes.";
            $this->display(__DIR__ . '/../View/Login.php', ['errorMessage' => $errorMessage]);
            return;
        }
        if ($password === $user->getPassword()) {
            $user->setFailedAttempts(0);
            $user->setLockUntil(null);
            $user->update();

            $_SESSION['id'] = $user->getUserID();
            $_SESSION['role'] = $user->getRole();
            header("Location: /Assignment/index.php");
            return;
        } else {
            $failed = $user->getFailedAttempts() + 1;
            $user->setFailedAttempts($failed);

            if ($failed >= 5) {
                $lockUntil = date("Y-m-d H:i:s", strtotime("+1 minutes"));
                $user->setLockUntil($lockUntil);
                $securityNotifier = new SecurityNotifier();
                $user->add($securityNotifier);
                $user->notify();
            }

            $user->update();

            if ($failed <= 5) {
            $errorMessage = "Invalid username or password. Attempts: $failed/5";
            } else {
            $errorMessage = "Invalid username or password. Attempts: $failed";
            }
            $this->display(__DIR__ . '/../View/Login.php', ['errorMessage' => $errorMessage]);
        }
    }
    
    //create account function
    public function create(){
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $conpassword = $_POST['confirm_password'];
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);

        if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
            $errors[] = "Username must be 3-20 characters, letters/numbers/underscore only.";
        }

        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
            $errors[] = "Password must be at least 8 characters, include uppercase, lowercase, and a number.";
        }

        if ($password !== $conpassword) {
            $errors[] = "Passwords do not match!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
            $errors[] = "Phone number must be 10-15 digits.";
        }
        
        if (!empty($errors)) {
            $errorMessage = implode("\n", $errors);
            $this->display(__DIR__ . '/../View/Register.php', ['errorMessage' => $errorMessage]);
            return;
        }

        $user = new Users(null, $username,$password, $email, $phone);
        
        $errorMessage = $user->verifyUnique();

        if($errorMessage){
            $this->display(__DIR__ . '/../View/Register.php', ['errorMessage' => $errorMessage]);
            return;
        }
        
        $user->insert($user);

        //Observer Design Pattern
        $registerNotifier = new RegisterNotifier();
        $user->add($registerNotifier);
        $user->notify();
        
        header("Location: /Assignment/index.php/Login");
    }
    
    //profile page
    public function profile(){
        $user = Users::find($_SESSION['id']);
        
        $this->display(__DIR__ . '/../View/Profile.php', ['user' => $user]);
    }
    
    //logout function
    public function logout(){
    session_destroy();
    
    header("Location: /Assignment/index.php");
    }
    
    //edit page
    public function edit(){
        $user = Users::find($_SESSION['id']);
        
        $this->display(__DIR__ . '/../View/EditProfile.php', ['user' => $user]);
    }
    
    //update function
    public function update(){
        $user = Users::find($_SESSION['id']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        $errorMessage = null;
        if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
            $errors[] = "Username must be 3-20 characters, letters/numbers/underscore only.";
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        
        if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
            $errors[] = "Phone number must be 10-15 digits.";
        }
        
        if (!empty($errors)) {
            $errorMessage = implode("\n", $errors);
            $this->display(__DIR__ . '/../View/EditProfile.php', ['errorMessage' => $errorMessage]);
            return;
        }
        
        $result = $user->verifyEdit($username, $email, $phone);
        
        if ($result !== null) {
            $messages = [];
            if ($result & 1) {
                $messages[] = "Username already exists.";
            }
            if ($result & 2) {
                $messages[] = "Email already exists.";
            }
            if ($result & 4) {
                $messages[] = "Phone Number already exists.";
            }

            $errorMessage = implode("\n", $messages);
        }
        if(!$errorMessage){
            $user->setUserName($username);
            $user->setEmail($email);
            $user->setPhone($phone);
            $user->update();
            
            //Observer Design Pattern
            $updateProfileNotifier = new UpdateProfileNotifier();
            $user->add($updateProfileNotifier);
            $user->notify();
            
            header("Location: /Assignment/index.php/Profile");
        } else {
            $user = Users::find($_SESSION['id']);
            
            $this->display(__DIR__ . '/../View/EditProfile.php', ['user' => $user, 'errorMessage' => $errorMessage]);
        }
        
    }
    
    //Change Password Page
    public function changePass(){
        $this->display(__DIR__ . '/../View/ChangePassword.php');
    }

    //Verify Email
    public function sendPasswordReset(){
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Please enter a valid email address.";
            $this->display(__DIR__ . '/../View/ChangePassword.php',['errorMessage' => $errorMessage]);
            return;
        }
        
        $user = Users::findByEmail($email);
        if($user){
            $otp = random_int(100000, 999999);
            
            $_SESSION['email'] = $email;
            $_SESSION['otp'] = $otp;
            
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tarumtcafe@gmail.com';
            $mail->Password = 'wvin ciff kass htsp';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tarumtcafe@gmail.com', 'YUM FOMS');
            $mail->addAddress($email, );

            $mail->isHTML(true);
            $mail->Subject = 'Password Changing confirmation';
            $mail->Body = "Here is the code for u to confirm the password changing. Code: " . $otp;

            $mail->send();
            header("Location: /Assignment/index.php/VerifyOTP");
            return;
        }else{
            $errorMessage = "No account found with this email.";
            $this->display(__DIR__ . '/../View/ChangePassword.php',['errorMessage' => $errorMessage]);
            return;
        }
    }
    
    //Change Password Page
    public function verifyOTPpage(){
         $this->display(__DIR__ . '/../View/VerifyOTP.php');
         return;
    }
    
    //verify OTP
    public function verifyOTP() {
        $enteredOtp = trim($_POST['otp']);
            
        if (empty($enteredOtp)) {
            $errors[] = "OTP cannot be empty.";
        }
        if (!ctype_digit($enteredOtp)) {
            $errors[] = "OTP must contain only digits.";
        }
        if (strlen($enteredOtp) !== 6) {
            $errors[] = "OTP must be exactly 6 digits.";
        }

        if (!empty($errors)) {
            $errorMessage = implode(" ", $errors);
            $this->display(__DIR__ . '/../View/VerifyOTP.php',['errorMessage' => $errorMessage]);
            return;
        }
        
        $savedOtp = $_SESSION['otp'];

            if ($enteredOtp == $savedOtp) {
                $_SESSION['otp_verified'] = true;

                unset($_SESSION['otp']);

                header("Location: /Assignment/index.php/updatePass");
                return;
            } else {
                $errorMessage = "Invalid code. Please try again.";
                $this->display(__DIR__ . '/../View/VerifyOTP.php',['errorMessage' => $errorMessage]);
                return;
            }
    }
    
    //Update Password Page
    public function updatePassPage(){
        $this->display(__DIR__ . '/../View/UpdatePassword.php');
        return;
    }
    
    //Update Password Function
    public function updatePass(){
        if(isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] == true){
            $password = trim($_POST['password'] ?? '');
            $conpassword = trim($_POST['conpassword'] ?? '');
            $errors = [];

            if (empty($password) || empty($conpassword)) {
                $errors[] = "Password fields cannot be empty.";
            }
            if (strlen($password) < 8) {
                $errors[] = "Password must be at least 8 characters long.";
            }
            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
                $errors[] = "Password must be at least 8 characters, include uppercase, lowercase, and a number.";
            }
            
            if (!empty($errors)) {
                $errorMessage = implode("\n", $errors);
                $this->display(__DIR__ . '/../View/UpdatePassword.php', ['errorMessage' => $errorMessage]);
                return;
            }

            if($password !== $conpassword){
                $errorMessage = "Passwords do not match!";
                $this->display(__DIR__ . '/../View/UpdatePassword.php',['errorMessage' => $errorMessage]);
                return;
            } else {
                unset($_SESSION['otp_verified']);
                $user = Users::findByEmail($_SESSION['email']);
                $user->setPassword($password);
                $user->update();
                
                $ChangePasswordNotifier = new ChangePasswordNotifier();
                $user->add($ChangePasswordNotifier);
                $user->notify();
                header("Location: /Assignment/index.php/Login");
                return;
            }
        }else{
            $errorMessage = "You have not verified OTP!";
            $this->display(__DIR__ . '/../View/VerifyOTP.php',['errorMessage' => $errorMessage]);
            return;
        }
    }
    
}