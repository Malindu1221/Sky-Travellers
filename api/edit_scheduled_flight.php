<?php
// File: /skytravellers/api/edit_scheduled_flight.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../includes/db_connection.php';
include_once '../includes/functions.php';

header('Content-Type: application/json');

// Handle GET request: Fetch flight details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Flight ID is required']);
        exit();
    }

    $flight_id = sanitize_input($_GET['id']);
    
    $sql = "SELECT 
        sf.id,
        a.name,
        f.flight_number,
        sf.source,
        sf.destination,
        DATE_FORMAT(sf.arrival_datetime, '%Y-%m-%dT%H:%i') as arrival_datetime,
        DATE_FORMAT(sf.departure_datetime, '%Y-%m-%dT%H:%i') as departure_datetime,
        sf.economy_cost,
        sf.business_cost,
        sf.first_class_cost
    FROM scheduled_flights sf
    JOIN flights f ON sf.flight_id = f.id
    JOIN airlines a ON f.airline_id = a.id
    WHERE sf.id = ?";
    
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $mysqli->error]);
        exit();
    }
    
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Flight not found']);
        exit();
    }
    
    $flight_data = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $flight_data]);
    
    $stmt->close();
    exit();
}