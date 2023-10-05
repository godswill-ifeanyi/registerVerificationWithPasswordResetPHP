<?php
session_start();
require_once 'dbcon.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify_token = "SELECT verify_token,verify_status FROM users WHERE verify_token ='$token'";
    $verify_token_run = mysqli_query($conn,$verify_token);

    if (mysqli_num_rows($verify_token_run) > 0) {
        $row = mysqli_fetch_array($verify_token_run);

        if ($row['verify_status'] == 0) {
            $clicked_token = $row['verify_token'];
            $update_query = "UPDATE users SET verify_status='1' WHERE verify_token='$clicked_token'";
            $update_query_run = mysqli_query($conn,$update_query);

            if ($update_query_run) {
                $_SESSION['status'] = "Your Email has been Verified. Please Login to Continue...";
                header("Location: login.php");
                exist(0);
            } else {
                $_SESSION['status'] = "Email Already Verified. Please Login to Continue...";
                header("Location: login.php");
                exist(0); 
            }

        } else {
            $_SESSION['status'] = "Email Already Verified. Please Login to Continue...";
            header("Location: login.php");
            exist(0);
        }
    } else {
        $_SESSION['status'] = "This Token does not Exit";
        header("Location: login.php");
    }

} else {
    $_SESSION['status'] = "Not Allowed";
    header("Location: login.php");
}

?>