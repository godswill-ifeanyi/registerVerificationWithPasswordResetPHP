<?php
if (!isset($_SESSION['sessionemail'])) {
    header("Location: login.php");
}
?>