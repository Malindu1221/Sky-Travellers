<?php
// File: /skytravellers/api/schedule_flight.php
ini_set('display_errors', 0); // Change this to 0 for production
ini_set('display_startup_errors', 0); // Change this to 0 for production
error_reporting(E_ALL);

include_once '../includes/db_connection.php';
include_once '../includes/functions.php';

header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Sanitize and validate inputs
$flight_id = sanitize_input($_POST['flight_id']);
$source = sanitize_input($_POST['source']);
$destination = sanitize_input($_POST['destination']);

// Validate and convert datetime strings
$departure_datetime = DateTime::createFromFormat('Y-m-d H:i:s', sanitize_input($_POST['departure_datetime']));
$arrival_datetime = DateTime::createFromFormat('Y-m-d H:i:s', sanitize_input($_POST['arrival_datetime']));

// Check for valid date objects
if (!$departure_datetime || !$arrival_datetime) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format.']);
    exit();
}

// Convert back to string for SQL
$departure_datetime = $departure_datetime->format('Y-m-d H:i:s');
$arrival_datetime = $arrival_datetime->format('Y-m-d H:i:s');

$economy_seats = intval($_POST['economy_seats']);
$business_seats = intval($_POST['business_seats']);
$first_class_seats = intval($_POST['first_class_seats']);
$economy_cost = floatval($_POST['economy_cost']);
$business_cost = floatval($_POST['business_cost']);
$first_class_cost = floatval($_POST['first_class_cost']);

// Remove or comment out debug output
// var_dump($arrival_datetime);
// var_dump($departure_datetime);

// Ensure the date format is correct
$date_pattern = "/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/";
if (!preg_match($date_pattern, $departure_datetime) || !preg_match($date_pattern, $arrival_datetime)) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit();
}

// Prepare the SQL query
$sql = "INSERT INTO scheduled_flights (flight_id, source, destination, departure_datetime, arrival_datetime,
        economy_seats, business_seats, first_class_seats, economy_cost, business_cost, first_class_cost)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'SQL error: ' . $mysqli->error]);
    exit();
}

// Bind parameters
$stmt->bind_param("issssiiiddd", $flight_id, $source, $destination, $departure_datetime, $arrival_datetime,
                   $economy_seats, $business_seats, $first_class_seats, $economy_cost, $business_cost, $first_class_cost);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Flight scheduled successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error scheduling flight: ' . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$mysqli->close();