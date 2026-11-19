<?php
// api/cancel_booking.php
header('Content-Type: application/json');
session_start();

include '../includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['booking_id'])) {
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided']);
    exit;
}

$booking_id = $data['booking_id'];
$user_id = $_SESSION['user_id'];

// Start transaction
$mysqli->begin_transaction();

try {
    // Verify booking belongs to user and get details
    $check_query = "SELECT b.flight_id, b.passengers_count, b.class, sf.departure_datetime 
                    FROM bookings b
                    JOIN scheduled_flights sf ON b.flight_id = sf.id
                    WHERE b.booking_id = ? AND b.user_id = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param("ii", $booking_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($booking = $result->fetch_assoc()) {
        // Check if flight is in the future
        if (strtotime($booking['departure_datetime']) <= time()) {
            throw new Exception('Cannot cancel past flights');
        }

        // Update available seats
        $seats_column = $booking['class'] . '_seats';
        $update_query = "UPDATE scheduled_flights 
                         SET $seats_column = $seats_column + ?
                         WHERE id = ?";
        $update_stmt = $mysqli->prepare($update_query);
        $update_stmt->bind_param("ii", $booking['passengers_count'], $booking['flight_id']);
        $update_stmt->execute();
        
        // Delete booking
        $delete_query = "DELETE FROM bookings WHERE booking_id = ?";
        $delete_stmt = $mysqli->prepare($delete_query);
        $delete_stmt->bind_param("i", $booking_id);
        $delete_stmt->execute();
        
        $mysqli->commit();
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Booking not found or does not belong to user');
    }
} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}