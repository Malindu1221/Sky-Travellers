<?php
// File: /skytravellers/pages/admin/edit_airline.php
session_start();
$pageTitle = "Edit Airline";
$pageName = 'edit_airline';
include '../../includes/admin_header.php';
?>
<main class="admin-content">
    <h1>Edit Airline</h1>
    <form id="editAirlineForm">
        <input type="hidden" id="airlineId" name="id">
        <div class="form-group">
            <label for="airlineName">Airline Name:</label>
            <input type="text" id="airlineName" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Airline</button>
    </form>
</main>
<?php include '../../includes/admin_footer.php'; ?>
<script src="/skytravellers/assets/js/edit_airline.js"></script>
