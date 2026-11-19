<?php
// File: /skytravellers/api/edit_flight.php
require_once '../includes/db_connection.php';
header('Content-Type: application/json');// Set the content type to JSON

// Check if the request method is POST for updating a flight
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $airline_id = $_POST['airline_id'] ?? null;
    $flight_number = $_POST['flight_number'] ?? null;
    $economy_seats = $_POST['economy_seats'] ?? null;
    $first_seats = $_POST['first_seats'] ?? null;
    $business_seats = $_POST['business_seats'] ?? null;

    // Check if any required field is missing
    if (!$id || !$airline_id || !$flight_number || !$economy_seats || !$first_seats || !$business_seats) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Prepare SQL query to update the flight record
    $stmt = $mysqli->prepare("UPDATE flights SET airline_id = ?, flight_number = ?, economy_seats = ?, first_seats = ?, business_seats = ? WHERE id = ?");
    $stmt->bind_param("issiii", $airline_id, $flight_number, $economy_seats, $first_seats, $business_seats, $id);
 
    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Flight updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update flight: ' . $mysqli->error]);
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing flight ID']);
        exit;
    }

    $stmt = $mysqli->prepare("SELECT f.id, f.airline_id, f.flight_number, f.economy_seats, f.first_seats, f.business_seats, a.name AS airline_name FROM flights f JOIN airlines a ON f.airline_id = a.id WHERE f.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($flight = $result->fetch_assoc()) {
        echo json_encode(['status' => 'success', 'data' => $flight]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Flight not found']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

$mysqli->close();