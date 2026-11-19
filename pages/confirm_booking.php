<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$pageTitle = "Confirm Booking - Sky Travellers";
$pageName = 'confirm_booking';
include '../includes/header.php';
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if this is the final submission with payment details
    if (isset($_POST['process_payment'])) {
        // Process booking with payment details
        $flight_id = $_POST['flight_id'] ?? '';
        $class = $_POST['class'] ?? '';
        $passengers_count = $_POST['passengers_count'] ?? 1;
        $payment_method = $_POST['payment_method'] ?? '';
        $card_number = $_POST['card_number'] ?? '';
        $expiry_date = $_POST['expiry_date'] ?? '';
        $cvv = $_POST['cvv'] ?? '';
        
        // Generate a transaction ID
        $transaction_id = uniqid('TXN');

        // Fetch flight details
        $query = "SELECT * FROM scheduled_flights WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $flight_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $flight = $result->fetch_assoc();

        if ($flight) {
            // Check seat availability
            $seats_available = $flight["{$class}_seats"];
            $cost_per_seat = $flight["{$class}_cost"];

            if ($seats_available >= $passengers_count) {
                $total_cost = $cost_per_seat * $passengers_count;
                $user_id = $_SESSION['user_id'] ?? 0;
                $booking_datetime = date('Y-m-d H:i:s');
            
                // Start transaction
                $mysqli->begin_transaction();
                try {
                    // Insert booking into database
                    $insert_query = "INSERT INTO bookings (flight_id, user_id, booking_datetime, passengers_count, total_price, class)
                                    VALUES (?, ?, ?, ?, ?, ?)";
                    $insert_stmt = $mysqli->prepare($insert_query);
                    $insert_stmt->bind_param("iisids", $flight_id, $user_id, $booking_datetime, $passengers_count, $total_cost, $class);
                    if (!$insert_stmt->execute()) {
                        throw new Exception("Error inserting booking: " . $insert_stmt->error);
                    }
                    $booking_id = $mysqli->insert_id;
            
                    // Process payment
                    $payment_query = "INSERT INTO payments (booking_id, amount, payment_method, transaction_id, payment_status, payment_datetime)
                                     VALUES (?, ?, ?, ?, 'completed', ?)";
                    $payment_stmt = $mysqli->prepare($payment_query);
                    $payment_stmt->bind_param("idsss", $booking_id, $total_cost, $payment_method, $transaction_id, $booking_datetime);
                    if (!$payment_stmt->execute()) {
                        throw new Exception("Error processing payment: " . $payment_stmt->error);
                    }
            
                    // Update available seats
                    $update_query = "UPDATE scheduled_flights
                                    SET {$class}_seats = {$class}_seats - ?
                                    WHERE id = ?";
                    $update_stmt = $mysqli->prepare($update_query);
                    $update_stmt->bind_param("ii", $passengers_count, $flight_id);
                    if (!$update_stmt->execute()) {
                        throw new Exception("Error updating seats: " . $update_stmt->error);
                    }
            
                    // Commit transaction
                    $mysqli->commit();
                    ?>
                    <div class="confirmation-container">
                        <h2 class="confirmation-header">Booking Confirmed</h2>
                        <div class="confirmation-details">
                            <p>Your booking has been confirmed for <?php echo ucfirst($class); ?> class.</p>
                            <p>Total amount paid: <span class="amount">Rs <?php echo number_format($total_cost); ?></span></p>
                            <p>Transaction ID: <span class="transaction-id"><?php echo htmlspecialchars($transaction_id); ?></span></p>
                        </div>
                        <?php 
                        $_SESSION['booking_id'] = $booking_id;
                        $_SESSION['passengers_count'] = $passengers_count;
                        ?>
                        <button onclick="window.location.href='/skytravellers/pages/passenger_details.php'" class="btn btn-primary">Enter passenger details</button>
                    </div>
                    <?php
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $mysqli->rollback();
                    ?>
                    <div class="error-container">
                        <h2 class="error-header">Booking Failed</h2>
                        <p class="error-message">There was an error processing your booking and payment. Please try again.</p>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <p class="error-details">Error details: <?php echo htmlspecialchars($e->getMessage()); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php
                    error_log("Booking error: " . $e->getMessage());
                }
            } else {
                ?>
                <div class="error-container">
                    <h2 class="error-header">Booking Failed</h2>
                    <p class="error-message">Not enough seats available for the selected class.</p>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="error-container">
                <h2 class="error-header">Booking Failed</h2>
                <p class="error-message">Invalid flight selection.</p>
            </div>
            <?php
        }
    } else {
        // This is the initial form submission, show payment form
        $flight_id = $_POST['flight_id'] ?? '';
        $class = $_POST['class'] ?? '';
        $passengers_count = $_POST['passengers_count'] ?? 1;
        
        // Fetch flight details for display
        $query = "SELECT * FROM scheduled_flights WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $flight_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $flight = $result->fetch_assoc();
        
        if ($flight) {
            $cost_per_seat = $flight["{$class}_cost"];
            $total_cost = $cost_per_seat * $passengers_count;
            ?>
            <div class="payment-container">
                <div class="payment-header">
                    <h2>Payment Details</h2>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="process_payment" value="1">
                    <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($flight_id); ?>">
                    <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
                    <input type="hidden" name="passengers_count" value="<?php echo htmlspecialchars($passengers_count); ?>">
                    
                    <div class="total-amount">
                        Total Amount: <span class="amount">Rs <?php echo number_format($total_cost); ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                        </select>
                    </div>
                    
                    <div class="card-details">
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" name="card_number" id="card_number" required pattern="\d{16}" placeholder="1234 5678 9012 3456">
                        </div>
                        
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="text" name="expiry_date" id="expiry_date" required pattern="\d{2}/\d{2}" placeholder="MM/YY">
                        </div>
                        
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" name="cvv" id="cvv" required pattern="\d{3}" placeholder="123">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Process Payment and Confirm Booking</button>
                </form>
            </div>
            <?php
        } else {
            ?>
            <div class="error-container">
                <h2 class="error-header">Invalid Flight Selection</h2>
                <p class="error-message">Please start your booking from the flight search page.</p>
            </div>
            <?php
        }
    }
} else {
    ?>
    <div class="error-container">
        <h2 class="error-header">Invalid Request</h2>
        <p class="error-message">Please start your booking from the flight search page.</p>
    </div>
    <?php
}

include '../includes/footer.php';
?>