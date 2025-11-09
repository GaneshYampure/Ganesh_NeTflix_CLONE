<?php
include 'dtata.php';
ob_start();
session_start();
if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result = mysqli_query($con, $sql); // Changed $conn to $con to match database.php
    $dbuseremail=null;
    $dbuserpassword=null;
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $dbuseremail = $row["email"];
            $dbuserpassword = $row["password"];
        }
    }
    if($email==$dbuseremail && $password==$dbuserpassword){
        $_SESSION['email'] = $email;
        header("Location: profile.php");
        exit(); 
    }
    else{
        $error = "Invalid email or password"; // Better error handling
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header class="header">
        <img src="https://assets.nflxext.com/en_us/layout/ecweb/common/logo-shadow2x.png" alt="Netflix Logo" class="netflix-logo">
    </header>
    
    <div class="login-container">
        <div class="login-form">
            <h1>Sign In</h1>
            <?php if(isset($error)): ?>
                <div style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <input type="email" class="form-input" name="email" placeholder="Email or phone number" id="email" required>
                    <label for="email" class="form-label">Email or phone number</label>
                </div>
                <div class="form-group">
                    <input type="password" class="form-input" name="password" placeholder="Password" id="password" required>
                    <label for="password" class="form-label">Password</label>
                </div>
                <button type="submit" class="signin-button">Sign In</button>
                <div class="remember-help">
                    <div class="remember">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="help-link">Need help?</a>
                </div>
                <div class="signup-now">
                    New to Netflix? <a href="signup.php">Sign up now</a>
                </div>
                <div class="recaptcha-terms">
                    This page is protected by Google reCAPTCHA to ensure you're not a bot. <a href="#">Learn more.</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>