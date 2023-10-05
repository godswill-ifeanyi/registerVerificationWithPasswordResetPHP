<?php
session_start();
require_once 'dbcon.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($name,$email,$verify_token) {
    $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'godswillokpanku@gmail.com';                     //SMTP username
        $mail->Password   = 'zjgw vuyq lchm vinq';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('godswillokpanku@gmail.com', 'Good Design');
        $mail->addAddress($email);     //Add a recipient
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Verification From Good Design';

        $email_template = "<h2> You have registered with Good Design </h2>
        <p> To continue, verify your email address with the given link below</p>
        <br><br>
        <a href='http://localhost/registerverification/verify-email.php?token=$verify_token'> Click Here </a>";

        $mail->Body    = $email_template;
    
        $mail->send();
        //echo 'Message has been sent';
    
}

if (isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn,$_POST['confirm_password']);
    $verify_token = md5(rand());

    // Check Empty Fields
     if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $_SESSION['status'] = "Empty Fields";
        header("Location: register.php");
    }
    else {
        // Email Exist or Not
        $check_email_query = "SELECT email from users WHERE email='$email'";
        $check_email_query_run = mysqli_query($conn,$check_email_query);

        if (mysqli_num_rows($check_email_query_run) > 0) {
            $_SESSION['status'] = "Email address already exists";
            header("Location: register.php");
        }
        else {

            if ($password != $confirm_password) {
                $_SESSION['status'] = "Password Incorrect"; 
                header("Location: register.php");
            }
            else {
                // Insert User into User's Table
                $hash_pass = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users (name,email,phone,password,verify_token) 
                VALUES ('$name','$email','$phone','$hash_pass','$verify_token')";
                $query_run = mysqli_query($conn,$query);

                if ($query_run) {
                    sendemail_verify("$name","$email","$verify_token");

                    $_SESSION['status'] = "Registration Successful, please verify your Email Address"; 
                    header("Location: register.php");
                }
                else {
                    $_SESSION['status'] = "Registration Failed"; 
                    header("Location: register.php");
                }
            }
        }
    } 
    
}

?>