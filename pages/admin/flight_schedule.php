<?php
session_start();
// log all errors

error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = "Schedule Flight";
$pageName = 'schedule_flight';
include '../../includes/admin_header.php';
?>
<div class="admin-content">
    <h1>Schedule Flight Details</h1>
    <div class="action-bar">
        <button id="scheduleNewFlight" class="btn btn-primary">Schedule New Flight</button>
        <div class="search-bar">
            <input type="text" id="searchFlight" placeholder="Search">
            <button class="btn btn-search"><i class="fas fa-search"></i></button>
        </div>
    </div>
    <table class="flight-table">
        <thead>
            <tr>
                <th>#</th>
                <th>AIR LINE NAME</th>
                <th>FLIGHT NAME</th>
                <th>SRS</th>
                <th>DEST</th>
                <th>ADT</th>
                <th>DDT</th>
                <th>CEC</th>
                <th>CBC</th>
                <th>CFC</th>
                <th>MAKE CHANGES</th>
            </tr>
        </thead>
        <tbody id="flightTableBody">
            <!-- Table rows will be dynamically populated here -->
        </tbody>
    </table>
    <div id="editFlightModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="editFlightForm">
                <h2>Edit Scheduled Flight</h2>
                <input type="hidden" id="edit_flight_id" name="id">
                
                <div class="form-group">
                    <label for="edit_airline_name">Airline Name:</label>
                    <input type="text" id="edit_airline_name" name="airline_name" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_flight_number">Flight Number:</label>
                    <input type="text" id="edit_flight_number" name="flight_number" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_source">Source:</label>
                    <input type="text" id="edit_source" name="source" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_destination">Destination:</label>
                    <input type="text" id="edit_destination" name="destination" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_departure_datetime">Departure Date and Time:</label>
                    <input type="datetime-local" id="edit_departure_datetime" name="departure_datetime" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_arrival_datetime">Arrival Date and Time:</label>
                    <input type="datetime-local" id="edit_arrival_datetime" name="arrival_datetime" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_economy_cost">Economy Cost:</label>
                    <input type="number" id="edit_economy_cost" name="economy_cost" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_business_cost">Business Cost:</label>
                    <input type="number" id="edit_business_cost" name="business_cost" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_first_class_cost">First Class Cost:</label>
                    <input type="number" id="edit_first_class_cost" name="first_class_cost" step="0.01" required>
                </div>
                
                <button type="submit">Update Flight</button>
            </form>
        </div>
    </div>
</div>
<?php include '../../includes/admin_footer.php'; ?>
<script src="/skytravellers/assets/js/schedule_flight.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editFlightModal');
    const closeBtn = modal.querySelector('.close');

    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
</script>