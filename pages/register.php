<?php
session_start();
$pageTitle = "Register";
$pageName = 'register';
include '../includes/header.php';
?>

<main class="register-content">
    <h1>Create an Account</h1>
    <form id="registrationForm" class="registration-form" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="firstname">First Name*</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name*</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="email">Email Address*</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="passport_number">Passport Number</label>
                <input type="text" id="passport_number" name="passport_number">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone">
            </div>
        </div>
        
        <div class="form-group">
            <label for="country">Country</label>
            <select id="country" name="country">
                <option value="">Select a country</option>
                <!-- Countries will be populated via JavaScript -->
            </select>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password*</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="profile_image">Profile Image</label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
<script src="/skytravellers/assets/js/register.js"></script>