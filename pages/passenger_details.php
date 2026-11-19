<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$pageTitle = "Passenger Details - Sky Travellers";
$pageName = 'passenger_details';
include '../includes/header.php';
include '../includes/db_connection.php';

// Check if there's a valid booking in the session
if (!isset($_SESSION['booking_id'])) {
    echo "<div class='error-container'><h2 class='error-header'>Invalid Access</h2><p class='error-message'>Please complete your booking first.</p></div>";
    include '../includes/footer.php';
    exit;
}

$booking_id = $_SESSION['booking_id'];
$passengers_count = $_SESSION['passengers_count'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $mysqli->begin_transaction();
    
    try {
        for ($i = 1; $i <= $passengers_count; $i++) {
            $title = $_POST["title_$i"];
            $first_name = $_POST["first_name_$i"];
            $last_name = $_POST["last_name_$i"];
            $mobile_number = $_POST["mobile_number_$i"];
            $date_of_birth = $_POST["date_of_birth_$i"];
            $gender = $_POST["gender_$i"];
            $passport_number = $_POST["passport_number_$i"];

            $query = "INSERT INTO passenger_details (booking_id, title, first_name, last_name, mobile_number, date_of_birth, gender, passport_number)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("isssssss", $booking_id, $title, $first_name, $last_name, $mobile_number, $date_of_birth, $gender, $passport_number);
            $stmt->execute();
        }

        $mysqli->commit();
        echo "<div class='success-container'><h2 class='success-header'>Passenger Details Saved</h2><p class='success-message'>Your booking is complete. Thank you for choosing Sky Travellers!</p></div>";
        // Clear booking session data
        unset($_SESSION['booking_id']);
        unset($_SESSION['passengers_count']);
    } catch (Exception $e) {
        $mysqli->rollback();
        echo "<div class='error-container'><h2 class='error-header'>Error</h2><p class='error-message'>There was an error saving passenger details. Please try again.</p></div>";
        error_log("Error saving passenger details: " . $e->getMessage());
    }
} else {
    // Display the form
    ?>
    <div class="passenger-details-container">
        <h2>Passenger Details</h2>
        <form method="POST" action="">
            <?php for ($i = 1; $i <= $passengers_count; $i++): ?>
                <div class="passenger-form">
                    <h3>Passenger <?php echo $i; ?></h3>
                    <div class="form-group">
                        <label for="title_<?php echo $i; ?>">Title</label>
                        <select name="title_<?php echo $i; ?>" id="title_<?php echo $i; ?>" required>
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Ms">Ms</option>
                            <option value="Dr">Dr</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="first_name_<?php echo $i; ?>">First Name</label>
                        <input type="text" name="first_name_<?php echo $i; ?>" id="first_name_<?php echo $i; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name_<?php echo $i; ?>">Last Name</label>
                        <input type="text" name="last_name_<?php echo $i; ?>" id="last_name_<?php echo $i; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile_number_<?php echo $i; ?>">Mobile Number</label>
                        <input type="tel" name="mobile_number_<?php echo $i; ?>" id="mobile_number_<?php echo $i; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth_<?php echo $i; ?>">Date of Birth</label>
                        <input type="date" name="date_of_birth_<?php echo $i; ?>" id="date_of_birth_<?php echo $i; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gender_<?php echo $i; ?>">Gender</label>
                        <select name="gender_<?php echo $i; ?>" id="gender_<?php echo $i; ?>" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="passport_number_<?php echo $i; ?>">Passport Number</label>
                        <input type="text" name="passport_number_<?php echo $i; ?>" id="passport_number_<?php echo $i; ?>" required>
                    </div>
                </div>
            <?php endfor; ?>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="history.back()">Back</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <?php
}

include '../includes/footer.php';
?>