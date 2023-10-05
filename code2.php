<?php
session_start();
require_once 'dbcon.php';

if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['status'] = "All Fields Are Required";
        header("Location: login.php");
        exist(0);    
    } else {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn,$sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);

            $pass_check = password_verify($password, $row['password']);

            if ($pass_check) {

                $_SESSION['sessionid'] = $row['id'];
                $_SESSION['sessionemail'] = $row['email'];
                $_SESSION['sessionname'] = $row['firstname'];

                header("Location: dashboard.php");
            exist(0);    
            } else {
                $_SESSION['status'] = "Incorrect Password";
                header("Location: login.php");
                exist(0);    
            }
            
        } else {
            $_SESSION['status'] = "Email Not Found";
            header("Location: login.php");
            exist(0);    
        }
    }
    
}
?>