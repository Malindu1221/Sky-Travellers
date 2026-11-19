<?php
// File: /skytravellers/api/remove_scheduled_flight.php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include_once '../includes/db_connection.php';
include_once '../includes/functions.php';

header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Check if the flight ID is provided
if (!isset($_POST['flight_id']) || empty($_POST['flight_id'])) {
    echo json_encode(['success' => false, 'message' => 'Flight ID is required']);
    exit();
}

// Sanitize the input
$flight_id = sanitize_input($_POST['flight_id']);

// Prepare the SQL query
$sql = "DELETE FROM scheduled_flights WHERE id = ?";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'SQL error: ' . $mysqli->error]);
    exit();
}

// Bind parameter
$stmt->bind_param("i", $flight_id);

// Execute the query
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Flight removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No flight found with the provided ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error removing flight: ' . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$mysqli->close();
