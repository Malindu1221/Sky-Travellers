<?php
session_start();
$pageTitle = "Add New Airline";
$pageName = 'add_new_airline';
include '../../includes/admin_header.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /skytravellers/pages/login.php');
    exit;
}
?>
<main class="admin-content">
    <h1>Add New Airline</h1>
    <form id="addAirlineForm" class="add-airline-form">
        <div class="form-group">
            <label for="airlineName">Enter Airline Name</label>
            <input type="text" id="airlineName" name="airlineName" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/skytravellers/pages/admin/airline_management.php'">Back to Airline Management</button>
        </div>
    </form>
</main>
<?php include '../../includes/admin_footer.php'; ?>
<script src="/skytravellers/assets/js/add_new_airline.js"></script>