<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db_connection.php';

header('Content-Type: application/json');

if (isset($_POST['update_info'])) {
    $userId = $_SESSION['user_id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $mysqli->prepare("UPDATE users SET phone = ?, email = ? WHERE id = ?");
    $stmt->bind_param('ssi', $phone, $email, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Account info updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update account info.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}