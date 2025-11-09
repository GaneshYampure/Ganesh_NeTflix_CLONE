<?php
include 'dtata.php';
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $currentDateTime = date('Y-m-d H:i:s');
    $expiryDateTime = date('Y-m-d H:i:s', strtotime('+1 year'));

    $insertQuery = "INSERT INTO user (name,email,mobile,password,accjoindate,acctype,accexpdate) VALUES ('$username', '$email', '$phone', '$password', '$currentDateTime','paid', '$expiryDateTime')";
    if(mysqli_query($con, $insertQuery)) {
        header('Location: signin.php');
        exit();
    } else {
        $error = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Sign Up</title>
    <link rel="stylesheet" href="./login.css">
</head>
<body>
    <header class="header">
        <img src="https://assets.nflxext.com/en_us/layout/ecweb/common/logo-shadow2x.png" alt="Netflix Logo" class="netflix-logo">
    </header>
    
    <div class="login-container">
        <div class="login-form">
            <h1>Sign Up</h1>
            <form action="signup.php" method="POST">
                <div class="form-group">
                    <input type="text" class="form-input" name="username" placeholder="Username" id="username" required>
                    <label for="username" class="form-label">Username</label>
                </div>

                <div class="form-group">
                    <input type="email" class="form-input" name="email" placeholder="Email" id="email" required>
                    <label for="email" class="form-label">Email</label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-input" name="phone" placeholder="Phone Number" id="phone" required>
                    <label for="phone" class="form-label">Phone Number</label>
                </div>
                <div class="form-group">
                    <input type="password" class="form-input" name="password" placeholder="Password" id="password" required>
                    <label for="password" class="form-label">Password</label>
                </div>
                <button name="login" type="submit" class="signin-button">Sign Up</button>
                <div class="signup-now">
                    Already have an account? <a href="singin.php">Sign In</a>
                </div>
                <div class="recaptcha-terms">
                    This page is protected by Google reCAPTCHA to ensure you're not a bot. <a href="#">Learn more.</a>
                </div>
            </form>
        </div>
    </div>
</body>

