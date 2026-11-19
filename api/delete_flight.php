<?php
require_once '../includes/db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$flight_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$flight_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Flight ID is required']);
    exit;
}

try {
    $mysqli->begin_transaction();

    // Get the airline_id before deleting the flight
    $stmt = $mysqli->prepare("SELECT airline_id FROM flights WHERE id = ?");
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $stmt->bind_result($airline_id);
    $stmt->fetch();
    $stmt->close();

    if (!$airline_id) {
        throw new Exception('Flight not found');
    }

    // Delete the flight
    $stmt = $mysqli->prepare("DELETE FROM flights WHERE id = ?");
    $stmt->bind_param("i", $flight_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Error deleting flight: ' . $mysqli->error);
    }

    // Decrement the flights count for the airline
    $stmt = $mysqli->prepare("UPDATE airlines SET flights = flights - 1 WHERE id = ?");
    $stmt->bind_param("i", $airline_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Error updating airline flight count: ' . $mysqli->error);
    }

    $mysqli->commit();

    echo json_encode(['success' => true, 'message' => 'Flight deleted successfully and airline flight count updated']);

} catch (Exception $e) {
    $mysqli->rollback();
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$mysqli->close();
