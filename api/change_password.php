<?php
session_start();
include '../includes/db_connection.php';

if (isset($_POST['change_password'])) {
    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if new password matches confirm password
    if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    // Fetch the current hashed password from the database
    $stmt = $mysqli->prepare("SELECT password FROM users WHERE id = ?");//get readt to ask the database for the user's password using their unique user id
    $stmt->bind_param('i', $userId);//securely attach the user's id to the query so it can find the correct user in db
    $stmt->execute();//it sends the query to the db to get the password
    $stmt->bind_result($hashedPassword);//store password and put it in a variable
    $stmt->fetch();//fetch the result
    $stmt->close();//finished

    // Verify the current password against the hashed password in the database
    if (!password_verify($currentPassword, $hashedPassword)) {
        echo json_encode(['message' => 'Incorrect current password.']);
        exit();
    }

    // Hash the new password and update it in the database
    $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $newHashedPassword, $userId);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Password changed successfully.']);
        header('Location: ../pages/account.php'); // Redirect after success
        exit(); // Ensure script terminates after the redirect
    } else {
        echo json_encode(['message' => 'Failed to change password.']);
        exit();
    }

    $stmt->close();
}
