<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flight_id = $_POST['flight_id'];
    $passengers_count = $_POST['passengers_count'];
    $user_id = $_SESSION['user_id'];  // Assuming the user is logged in

    // Calculate total price (dummy value for now, can be based on seats or other criteria)
    $price_per_passenger = 100.00; // Example price per passenger
    $total_price = $passengers_count * $price_per_passenger;

    // Insert booking into the database
    $query = "INSERT INTO bookings (flight_id, user_id, passengers_count, total_price) VALUES ('$flight_id', '$user_id', '$passengers_count', '$total_price')";
    
    if (mysqli_query($mysqli, $query)) {
        echo "Booking successful!";
        // Redirect to confirmation page or show success message
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
    }
}
