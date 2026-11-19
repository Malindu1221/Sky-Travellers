<?php
session_start();
$pageTitle = "Add New Flight";
$pageName = 'add_new_flight';
include '../../includes/admin_header.php';
?>
<main class="admin-content">
    <h1>Add a Flight</h1>
    <form id="addFlightForm" class="flight-form" method="POST" action="/skytravellers/api/add_flight.php">
        <div class="form-container">
            <div class="form-group">
                <label for="airline">Select Airline</label>
                <select id="airline" name="airline" required>
                    <option value="">Select an airline</option>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="form-group">
                <label for="flightNumber">Enter Flight Number</label>
                <input type="text" id="flightNumber" name="flightNumber" required>
            </div>
            <div class="form-group">
                <label>Flight Has</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="flightClass[]" value="economy"> Economy Class</label>
                    <label><input type="checkbox" name="flightClass[]" value="business"> Business Class</label>
                    <label><input type="checkbox" name="flightClass[]" value="first"> First Class</label>
                </div>
            </div>
            <div class="form-group">
                <label for="economySeats">Enter Number Of Economy Class Seats</label>
                <input type="number" id="economySeats" name="economySeats" min="0">
            </div>
            <div class="form-group">
                <label for="businessSeats">Enter Number Of Business Class Seats</label>
                <input type="number" id="businessSeats" name="businessSeats" min="0">
            </div>
            <div class="form-group">
                <label for="firstSeats">Enter Number Of First Class Seats</label>
                <input type="number" id="firstSeats" name="firstSeats" min="0">
            </div>
            <div class="form-group">
                <label for="totalSeats">Total Number Of Seats</label>
                <input type="number" id="totalSeats" name="totalSeats" readonly>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</main>
<?php include '../../includes/admin_footer.php'; ?>
<script src="/skytravellers/assets/js/add_new_flight.js"></script>