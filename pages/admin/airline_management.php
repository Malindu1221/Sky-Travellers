<?php
session_start();
$pageTitle = "Airline Management";
$pageName = 'airline_management';
include '../../includes/admin_header.php';
?>

<main class="admin-content">
    <h1>Airline Details</h1>
    
    <div class="action-bar">
        <button id="addNewAirline" class="btn btn-primary" onclick="window.location.href='/skytravellers/pages/admin/add_new_airline.php'">Add New Airline</button>
        <div class="search-bar">
            <input type="text" id="searchAirline" placeholder="Search">
            <button class="btn btn-search"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <table class="airline-table">
        <thead>
            <tr>
                <th>#</th>
                <th>AIRLINE NAME</th>
                <th>NO OF FLIGHTS</th>
                <th>MAKE CHANGES</th>
                <th>ADDED DATE</th>
            </tr>
        </thead>
        <tbody id="airlineTableBody">
            <!-- Table rows will be dynamically populated here -->
        </tbody>
    </table>
</main>

<?php include '../../includes/admin_footer.php'; ?>

<script src="/skytravellers/assets/js/airline_management.js"></script>