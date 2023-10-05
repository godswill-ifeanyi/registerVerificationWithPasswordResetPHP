<?php 
session_start();
$page_title = "Password Change Form";

include('includes/header.php');

?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Login With Email Verification</h4>
                <h5>With PHP</h5>
            </div>
        </div>
        <div class="row">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
            <div class="alert">
                    <?php
                        if (isset($_SESSION['status'])) {
                            ?>
                            <div class="alert alert-success">
                                <h5><?=$_SESSION['status'];?></h5>
                            </div>
                    <?php
                        unset($_SESSION['status']);
                        }
                    ?>
                </div>
                <div class="card shadow">
                    <div class="card-header">
                        <h4>Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="code3.php" method="post">
                            <div class="form-group mb-3">
                                
                                <input type="hidden" name="email" value="<?php if (isset($_GET['email'])) {echo $_GET['email'];}?>" class="form-control">
                                <input type="hidden" name="token" value="<?php if (isset($_GET['token'])) {echo $_GET['token'];}?>" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">New Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="password_update" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>