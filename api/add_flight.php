<?php
require_once '../includes/db_connection.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Start transaction
    $mysqli->begin_transaction();

    // Validate and sanitize input
    $airline_id = filter_input(INPUT_POST, 'airline', FILTER_SANITIZE_NUMBER_INT);
    $flight_number = filter_input(INPUT_POST, 'flightNumber', FILTER_SANITIZE_STRING);
    $economy_seats = filter_input(INPUT_POST, 'economySeats', FILTER_SANITIZE_NUMBER_INT);
    $business_seats = filter_input(INPUT_POST, 'businessSeats', FILTER_SANITIZE_NUMBER_INT);
    $first_seats = filter_input(INPUT_POST, 'firstSeats', FILTER_SANITIZE_NUMBER_INT);

    // Validate required fields
    if (!$airline_id || !$flight_number) {
        throw new Exception('Airline and flight number are required');
    }

    // Validate seat numbers
    if ($economy_seats < 0 || $business_seats < 0 || $first_seats < 0) {
        throw new Exception('Seat numbers cannot be negative');
    }

    // Check if flight number already exists for the airline
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM flights WHERE airline_id = ? AND flight_number = ?");
    $stmt->bind_param("is", $airline_id, $flight_number);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        throw new Exception('Flight number already exists for this airline');
    }

    // Insert new flight
    $stmt = $mysqli->prepare("INSERT INTO flights (airline_id, flight_number, economy_seats, business_seats, first_seats) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiii", $airline_id, $flight_number, $economy_seats, $business_seats, $first_seats);
    
    if (!$stmt->execute()) {
        throw new Exception('Error adding flight to database: ' . $mysqli->error);
    }

    // Increment the flights count for the airline
    $stmt = $mysqli->prepare("UPDATE airlines SET flights = flights + 1 WHERE id = ?");
    $stmt->bind_param("i", $airline_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Error updating airline flight count: ' . $mysqli->error);
    }

    // Commit the transaction
    $mysqli->commit();

    echo json_encode(['success' => true, 'message' => 'Flight added successfully and airline flight count updated']);

} catch (Exception $e) {
    // Rollback the transaction on error
    $mysqli->rollback();

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$mysqli->close();