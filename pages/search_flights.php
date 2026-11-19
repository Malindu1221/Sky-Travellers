<?php
session_start();
$pageTitle = "Search Flights - Sky Travellers";
$pageName = 'search_flights';
include '../includes/header.php';
include '../includes/db_connection.php';

// Retrieve search parameters
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$departure_date = $_GET['departure_date'] ?? '';
$passengers_count = $_GET['passengers_count'] ?? 1;

// Fetch flights from the database based on search criteria
$query = "SELECT * FROM scheduled_flights 
          WHERE source = ? AND destination = ? AND DATE(departure_datetime) = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss", $from, $to, $departure_date);
$stmt->execute();
$result = $stmt->get_result();
?>

<section class="search-results">
    <h2>Making a Booking</h2>
    <p><?php echo htmlspecialchars($from); ?> to <?php echo htmlspecialchars($to); ?></p>
    <p><?php echo date('d M Y', strtotime($departure_date)); ?></p>

    <?php while ($flight = $result->fetch_assoc()): ?>
        <div class="flight-card">
            <div class="flight-info">
                <div class="departure">
                    <h3><?php echo htmlspecialchars($from); ?></h3>
                    <p>Departed</p>
                    <p class="time"><?php echo date('H:i', strtotime($flight['departure_datetime'])); ?></p>
                    <p class="date"><?php echo date('D,d M y', strtotime($flight['departure_datetime'])); ?></p>
                </div>
                <div class="arrival">
                    <h3><?php echo htmlspecialchars($to); ?></h3>
                    <p>Arrived</p>
                    <p class="time"><?php echo date('H:i', strtotime($flight['arrival_datetime'])); ?></p>
                    <p class="date"><?php echo date('D,d M y', strtotime($flight['arrival_datetime'])); ?></p>
                </div>
            </div>
            <div class="booking-options">
                <div class="class-option">
                    <h4>Economy Class</h4>
                    <p>Rs <?php echo number_format($flight['economy_cost']); ?></p>
                    <p>Seat Available: <?php echo $flight['economy_seats']; ?></p>
                    <form action="/skytravellers/pages/confirm_booking.php" method="POST">
                        <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                        <input type="hidden" name="class" value="economy">
                        <input type="hidden" name="passengers_count" value="<?php echo $passengers_count; ?>">
                        <button type="submit" <?php echo ($flight['economy_seats'] < $passengers_count) ? 'disabled' : ''; ?>>
                            Submit
                        </button>
                    </form>
                </div>
                <div class="class-option">
                    <h4>Business Class</h4>
                    <p>Rs <?php echo number_format($flight['business_cost']); ?></p>
                    <p>Seat Available: <?php echo $flight['business_seats']; ?></p>
                    <form action="/skytravellers/pages/confirm_booking.php" method="POST">
                        <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                        <input type="hidden" name="class" value="business">
                        <input type="hidden" name="passengers_count" value="<?php echo $passengers_count; ?>">
                        <button type="submit" <?php echo ($flight['business_seats'] < $passengers_count) ? 'disabled' : ''; ?>>
                            Submit
                        </button>
                    </form>
                </div>
                <div class="class-option">
                    <h4>First Class</h4>
                    <p>Rs <?php echo number_format($flight['first_class_cost']); ?></p>
                    <p>Seat Available: <?php echo $flight['first_class_seats']; ?></p>
                    <form action="/skytravellers/pages/confirm_booking.php" method="POST">
                        <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                        <input type="hidden" name="class" value="first_class">
                        <input type="hidden" name="passengers_count" value="<?php echo $passengers_count; ?>">
                        <button type="submit" <?php echo ($flight['first_class_seats'] < $passengers_count) ? 'disabled' : ''; ?>>
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</section>

<?php include '../includes/footer.php'; ?>