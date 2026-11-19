<?php
session_start();
$pageTitle = "Flight Management";
$pageName = 'flight_management';
include '../../includes/admin_header.php';
?>

<main class="admin-content">
    <h1>Flight Details</h1>
    
    <div class="action-bar">
        <button id="addNewFlight" class="btn btn-primary" onclick="window.location.href='/skytravellers/pages/admin/add_new_flight.php'">Add New Flight</button>
        <div class="search-bar">
            <input type="text" id="searchFlight" placeholder="Search">
            <button class="btn btn-search"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <table class="flight-table">
        <thead>
            <tr>
                <th>#</th>
                <th>AIRLINE NAME</th>
                <th>FLIGHT No.</th>
                <th>NOE</th>
                <th>NOF</th>
                <th>NOB</th>
                <th>NOS</th>
                <th>MAKE CHANGES</th>
            </tr>
        </thead>
        <tbody id="flightTableBody">
            <!-- Table rows will be dynamically populated here -->
        </tbody>
    </table>
</main>

<?php include '../../includes/admin_footer.php'; ?>

<script src="/skytravellers/assets/js/flight_management.js"></script>