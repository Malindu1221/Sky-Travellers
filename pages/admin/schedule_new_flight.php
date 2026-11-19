<?php
// File: /skytravellers/pages/admin/schedule_flight.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pageTitle = "Schedule Flight";
$pageName = 'schedule_new_flight';
include '../../includes/admin_header.php';
include_once '../../includes/db_connection.php';
include_once '../../includes/functions.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /skytravellers/pages/login.php");
    exit();
}

// Fetch all flights
$flights = get_all_flights($mysqli);
?>

<main class="admin-content">
    <h1>Schedule Flight</h1>
    <form id="scheduleFlightForm">
        <div class="form-group">
            <label for="flight">Select Flight:</label>
            <select name="flight_id" id="flight" required>
                <option value="">Select a flight</option>
                <?php foreach ($flights as $flight): ?>
                    <option value="<?php echo $flight['id']; ?>">
                        <?php echo $flight['flight_number'] . ' - ' . $flight['airline_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="source">Source:</label>
            <input type="text" id="source" name="source" required>
        </div>

        <div class="form-group">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" required>
        </div>

        <div class="form-group">
            <label for="departureDateTime">Departure Date and Time:</label>
            <input type="datetime-local" id="departure_datetime" name="departure_datetime" required>
        </div>

        <div class="form-group">
            <label for="arrivalDateTime">Arrival Date and Time:</label>
            <input type="datetime-local" id="arrival_datetime" name="arrival_datetime" required>
        </div>

        <div class="form-group">
            <label for="economySeats">Economy Class Seats:</label>
            <input type="number" id="economySeats" name="economy_seats" min="0" required>
        </div>

        <div class="form-group">
            <label for="businessSeats">Business Class Seats:</label>
            <input type="number" id="businessSeats" name="business_seats" min="0" required>
        </div>

        <div class="form-group">
            <label for="firstClassSeats">First Class Seats:</label>
            <input type="number" id="firstClassSeats" name="first_class_seats" min="0" required>
        </div>

        <div class="form-group">
            <label for="economyCost">Economy Class Cost:</label>
            <input type="number" id="economyCost" name="economy_cost" min="0" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="businessCost">Business Class Cost:</label>
            <input type="number" id="businessCost" name="business_cost" min="0" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="firstClassCost">First Class Cost:</label>
            <input type="number" id="firstClassCost" name="first_class_cost" min="0" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-primary">Schedule Flight</button>
    </form>
</main>

<?php include '../../includes/admin_footer.php'; ?>
<script src="/skytravellers/assets/js/schedule_new_flight.js"></script>