<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    // Debugging: Log the request
    error_log("Add Airline API called");
    error_log("POST data: " . print_r($_POST, true));
    
    // Include database connection
    require_once '../includes/db_connection.php';
    
    // Check if database connection is successful
    if ($mysqli->connect_error) {
        throw new Exception("Database connection failed: " . $mysqli->connect_error);
    }
    
    // Verify session and role
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in");
    }
    
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        throw new Exception("User is not an admin");
    }
    
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Validate and sanitize input
    $airlineName = isset($_POST['airlineName']) ? trim($_POST['airlineName']) : '';
    
    if (empty($airlineName)) {
        throw new Exception('Airline name is required');
    }
    
    // Check if airline already exists
    $checkStmt = $mysqli->prepare("SELECT id FROM airlines WHERE name = ?");
    if (!$checkStmt) {
        throw new Exception("Prepare check statement failed: " . $mysqli->error);
    }
    
    $checkStmt->bind_param("s", $airlineName);
    if (!$checkStmt->execute()) {
        throw new Exception("Execute check statement failed: " . $checkStmt->error);
    }
    
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        throw new Exception('An airline with this name already exists');
    }
    
    // Insert new airline
    $stmt = $mysqli->prepare("INSERT INTO airlines (name, flights, added_date) VALUES (?, 0, NOW())");
    if (!$stmt) {
        throw new Exception("Prepare insert statement failed: " . $mysqli->error);
    }
    
    $stmt->bind_param("s", $airlineName);
    if (!$stmt->execute()) {
        throw new Exception("Execute insert statement failed: " . $stmt->error);
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Airline added successfully',
        'airlineId' => $mysqli->insert_id
    ]);
    
} catch (Exception $e) {
    error_log("Error in add_airline.php: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage(),
        'debug' => [
            'session' => $_SESSION,
            'post_data' => $_POST,
            'php_version' => PHP_VERSION,
            'mysql_version' => isset($mysqli) ? $mysqli->server_info : 'Unknown'
        ]
    ]);
}

// Close statements and connection
if (isset($checkStmt)) $checkStmt->close();
if (isset($stmt)) $stmt->close();
if (isset($mysqli)) $mysqli->close();