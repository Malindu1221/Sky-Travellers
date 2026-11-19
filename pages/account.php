<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = "User Account";
$pageName = 'account';
include '../includes/header.php';
include '../includes/functions.php';
// Fetch user data from the database
$userId = $_SESSION['user_id']; // Assume user is logged in
$userData = getUserData($userId); // Implement this function to fetch user data
?>

<div class="container user-account-page">
    <div class="user-profile">
        <h2>User:</h2>
        <div class="profile-image">
            <img src="<?php echo $userData['profile_image'] ?? '/assets/images/default-avatar.png'; ?>" alt="User Avatar">
        </div>
        <h3>Country:</h3>
        <p><?php echo htmlspecialchars($userData['country']); ?></p>
        <h3>Passport:</h3>
        <p><?php echo htmlspecialchars($userData['passport_number']); ?></p>
    </div>
    
    <div class="account-info-section">
        <div class="account-info">
            <h2>Account Information:</h2>
            <form action="/skytravellers/api/update_account.php" method="POST">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($userData['firstname']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($userData['lastname']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="phone">Phone number:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
                </div>
                <button type="submit" name="update_info" class="btn-primary">Update Info</button>
            </form>
            <form action="logout.php" method="POST">
                <button type="submit" class="btn-secondary">Log out</button>
            </form>
        </div>
        
        <div class="change-password">
            <h2>Change Password:</h2>
            <form action="/skytravellers/api/change_password.php" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn-primary">Change</button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- In your account.php file -->

<script>
// Update account info
document.addEventListener("DOMContentLoaded", function() {
    const updateForm = document.querySelector('form[action="/skytravellers/api/update_account.php"]');
    const changePasswordForm = document.querySelector('form[action="/skytravellers/api/change_password.php"]');

     // Handle account info update
    updateForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(updateForm);
        formData.append('update_info', true);

        fetch('/skytravellers/api/update_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                // Optionally refresh the page or update the form fields
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error updating your information.');
        });
    });

    // Handle password change
    changePasswordForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting

        const formData = new FormData(changePasswordForm);
        formData.append('change_password', true); // Append custom data

        fetch('/skytravellers/api/change_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            alert(data.message); // Show success/error message from server
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`There was an error changing your password: ${error.message}`);
        });
    });


});
</script>
