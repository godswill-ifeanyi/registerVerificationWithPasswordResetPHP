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

function send_password_reset($get_name,$get_email,$token) {
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
    $mail->addAddress($get_email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Password Reset Notification';

    $email_template = "<h3> Hello, ".$get_name."</h3>
    <p> You are receiving this email because we received a password reset request for your account.</p>
    <br><br>
    <a href='http://localhost/registerverification/password-change.php?token=$token&email=$get_email'> Click Here </a>";

    $mail->Body    = $email_template;

    $mail->send();
    //echo 'Message has been sent';
}

if (isset($_POST['password_reset_link'])) {
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT name,email FROM users WHERE email='$email'";
    $check_email_run = mysqli_query($conn,$check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        $row = mysqli_fetch_array($check_email_run);
        $get_name = $row['name'];
        $get_email = $row['email'];

        $update_token = "UPDATE users SET verify_token='$token' WHERE email='$get_email'";
        $update_token_run = mysqli_query($conn,$update_token);

        if ($update_token_run) {
            send_password_reset($get_name,$get_email,$token);
            $_SESSION['status'] = "We e-mailed you a password reset link";
            header("Location: password-reset.php");
            exist(0);
        }
        else {
            $_SESSION['status'] = "Something Went Wrong";
            header("Location: password-reset.php");
            exist(0);    
        }

    }
    else {
        $_SESSION['status'] = "Email Address Not Found";
        header("Location: password-reset.php");
        exist(0);
    }
}

if (isset($_POST['password_update'])) {
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn,$_POST['confirm_password']);

    $token = mysqli_real_escape_string($conn,$_POST['token']);

    if (!empty($token)) {

        if (!empty($password) || !empty($confirm_password)) {
            $check_token = "SELECT verify_token FROM users WHERE verify_token='$token'";
            $check_token_run = mysqli_query($conn,$check_token);

            if (mysqli_num_rows($check_token_run) > 0) {

                if ($password == $confirm_password) {
                    $hash_pass = password_hash($password, PASSWORD_DEFAULT);
                    $update_password = "UPDATE users SET password='$hash_pass' WHERE verify_token='$token'";
                    $update_password_run = mysqli_query($conn,$update_password);

                    if ($update_password_run) {
                        $new_token = md5(rand());
                        $update_token = "UPDATE users SET verify_token='$new_token' WHERE verify_token='$token'";
                        $update_token_run = mysqli_query($conn,$update_token);
                        
                        $_SESSION['status'] = "New Password Successfully Updated";
                        header("Location: login.php");
                        exist(0);
                    } else {
                        $_SESSION['status'] = "Password Update Failed";
                        header("Location: password-change.php?token=$token&email=$email");
                        exist(0);
                    }
                } else {
                    $_SESSION['status'] = "Passwords don't match";
                    header("Location: password-change.php?token=$token&email=$email");
                    exist(0);
                }

            } else {
                $_SESSION['status'] = "Invalid Token";
                header("Location: password-change.php?token=$token&email=$email");
                exist(0);
            }

        } else {
            $_SESSION['status'] = "All Fields Are Required";
            header("Location: password-change.php?token=$token&email=$email");
            exist(0);
        }
    }
    else {
        $_SESSION['status'] = "No Token Available";
        header("Location: password-change.php");
        exist(0);
    }
}

?>