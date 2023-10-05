<?php
session_start();
unset($_SESSION['sessionemail']);
$_SESSION['status'] = "You have logged out Successfully";
header("Location: login.php"); 
?>