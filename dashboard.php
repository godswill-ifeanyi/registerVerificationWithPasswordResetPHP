<?php
session_start();
$page_title = "Dashboard";

require 'includes/header.php';
require_once 'includes/authenticated.php';

?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>User Dashboard</h4>
                    </div>
                    <div class="card-body">
                    <h4>Accessed when you're logged in...</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
    
<?php require 'includes/footer.php';?>