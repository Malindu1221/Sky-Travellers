<?php
session_start();
$pageTitle = "My Flights - Sky Travellers";
$pageName = 'my_flights';
include '../includes/header.php';
include '../includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's bookings
$user_id = $_SESSION['user_id'];
$query = "SELECT b.*, sf.departure_datetime, sf.arrival_datetime, 
          sf.source, sf.destination, a.name as airline_name 
          FROM bookings b 
          JOIN scheduled_flights sf ON b.flight_id = sf.id 
          JOIN airlines a ON sf.flight_id = a.id 
          WHERE b.user_id = ? 
          ORDER BY sf.departure_datetime DESC";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h1>My Flights</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <?php while ($booking = $result->fetch_assoc()): ?>
            <div class="flight-ticket" data-booking-id="<?php echo $booking['booking_id']; ?>">
                <div class="ticket-content">
                    <div class="cities">
                        <div class="departure">
                            <h3><?php echo htmlspecialchars($booking['source']); ?></h3>
                            <p>Departed</p>
                            <h2><?php echo date('H:i', strtotime($booking['departure_datetime'])); ?></h2>
                            <p><?php echo date('D d M y', strtotime($booking['departure_datetime'])); ?></p>
                        </div>
                        <div class="flight-icon">
                            <span>✈</span>
                        </div>
                        <div class="arrival">
                            <h3><?php echo htmlspecialchars($booking['destination']); ?></h3>
                            <p>Arrived</p>
                            <h2><?php echo date('H:i', strtotime($booking['arrival_datetime'])); ?></h2>
                            <p><?php echo date('D d M y', strtotime($booking['arrival_datetime'])); ?></p>
                        </div>
                    </div>
                    <div class="airline-info">
                        <p><?php echo htmlspecialchars($booking['airline_name']); ?></p>
                        <p>Class: <?php echo ucfirst(htmlspecialchars($booking['class'])); ?></p>
                        <p>Passengers: <?php echo htmlspecialchars($booking['passengers_count']); ?></p>
                    </div>
                </div>
                <div class="ticket-actions">
                    <button class="btn-print">Print</button>
                    <?php
                    // Only show cancel button for future flights
                    if (strtotime($booking['departure_datetime']) > time()):
                    ?>
                        <button class="btn-cancel">Cancel</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-flights">You haven't booked any flights yet.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>