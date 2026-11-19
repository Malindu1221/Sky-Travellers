<?php
// File: /skytravellers/pages/admin/edit_flight.php
session_start();
$pageTitle = "Edit Flight";
$pageName = 'edit_flight';
include '../../includes/admin_header.php';
?>
<main class="admin-content">
    <h1>Edit Flight</h1>
    <form id="editFlightForm">
        <input type="hidden" id="flightId" name="id">
        <div class="form-group">
            <label for="airlineId">Airline:</label>
            <select id="airlineId" name="airline_id" required>
                <!-- Options will be populated dynamically -->
            </select>
        </div>
        <div class="form-group">
            <label for="flightNumber">Flight Number:</label>
            <input type="text" id="flightNumber" name="flight_number" required>
        </div>
        <div class="form-group">
            <label for="economySeats">Economy Seats:</label>
            <input type="number" id="economySeats" name="economy_seats" required>
        </div>
        <div class="form-group">
            <label for="firstSeats">First Class Seats:</label>
            <input type="number" id="firstSeats" name="first_seats" required>
        </div>
        <div class="form-group">
            <label for="businessSeats">Business Class Seats:</label>
            <input type="number" id="businessSeats" name="business_seats" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Flight</button>
    </form>
</main>
<?php include '../../includes/admin_footer.php'; ?>
<script src="/skytravellers/assets/js/edit_flight.js"></script>
