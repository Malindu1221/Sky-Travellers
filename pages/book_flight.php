<?php
session_start();
$pageTitle = "Book Flight - Sky Travellers";
$pageName = 'book_flight';
include '../includes/header.php';
include '../includes/db_connection.php';
?>

<section class="flight-booking">
    <h2>Book a Flight</h2>
    <form action="/skytravellers/api/process_booking.php" method="POST">
        <div class="form-group">
            <label for="flight_id">Flight</label>
            <select id="flight_id" name="flight_id">
                <?php
                // Fetch available flights from the database
                $flights = mysqli_query($mysqli, "SELECT id, flight_number FROM flights");
                while ($row = mysqli_fetch_assoc($flights)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['flight_number'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="passengers_count">Number of Passengers</label>
            <input type="number" id="passengers_count" name="passengers_count" required>
        </div>
        <button type="submit">Book Flight</button>
    </form>
</section>

<?php include '../includes/footer.php'; ?>
