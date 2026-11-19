<?php 
session_start();
$pageTitle = "Login to Your Account";
$pageName = 'login';
include '../includes/header.php';
?>
<div class="container">
<div class="login-container">
    <h1>Login to Your Account</h1>
    <form class="login-form" action="/skytravellers/api/login_api.php" method="POST">
        <center><span><i>Don't have an account?</i></span><br></center>
        <div class="social-login">
            <button type="button" class="google-login">
                
                <a href="register.php"><span style="font-size:1.5rem; font-family: cursive">Register Now</span></a>
            </button>
        </div>
        <div class="separator">
            <span>OR</span>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="remember-me">
                Remember me
            </label>
        </div>
        <button type="submit" class="login-button">Login</button>
    </form>
</div>
</div>

<?php include '../includes/footer.php'; ?>