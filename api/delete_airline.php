<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

// Check if ID is provided
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Airline ID is required'
    ]);
    exit;
}

$airline_id = $mysqli->real_escape_string($_GET['id']);

// Start transaction
$mysqli->begin_transaction();

try {
    // First, delete related flights
    $delete_flights = $mysqli->query("DELETE FROM flights WHERE airline_id = $airline_id");
    
    // Then, delete the airline
    $delete_airline = $mysqli->query("DELETE FROM airlines WHERE id = $airline_id");
    
    if ($delete_airline) {
        $mysqli->commit();
        echo json_encode([
            'status' => 'success',
            'message' => 'Airline deleted successfully'
        ]);
    } else {
        throw new Exception("Failed to delete airline");
    }
} catch (Exception $e) {
    $mysqli->rollback();
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to delete airline: ' . $e->getMessage()
    ]);
}

$mysqli->close();