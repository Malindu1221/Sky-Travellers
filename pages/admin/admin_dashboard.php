<?php
session_start();
require_once '../../includes/db_connection.php';
require_once '../../includes/functions.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /skytravellers/pages/login.php");
    exit();
}

// Fetch dashboard statistics
$stats = [
    'airlines' => 0,
    'flights' => 0,
    'bookings' => 0,
    'earnings' => 0
];

try {
    // Count airlines
    $result = $mysqli->query("SELECT COUNT(*) FROM airlines");
    $stats['airlines'] = $result->fetch_row()[0];

    // Count flights
    $result = $mysqli->query("SELECT COUNT(*) FROM flights");
    $stats['flights'] = $result->fetch_row()[0];

    // Count bookings
    $result = $mysqli->query("SELECT COUNT(*) FROM bookings");
    $stats['bookings'] = $result->fetch_row()[0];

    // Calculate total earnings
    $result = $mysqli->query("SELECT SUM(total_price) FROM bookings");
    $stats['earnings'] = $result->fetch_row()[0] ?: 0;

} catch (mysqli_sql_exception $e) {
    error_log("Error fetching dashboard stats: " . $e->getMessage());
    // Set default values in case of database error
}

// Get admin name
$pageTitle = "Admin Dashboard";
$pageName = 'admin_dashboard';

require_once '../../includes/admin_header.php';
?>

<div class="dashboard-container">
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Available Airlines</h3>
            <p class="stat-number"><?php echo $stats['airlines']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Available Flights</h3>
            <p class="stat-number"><?php echo $stats['flights']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Bookings</h3>
            <p class="stat-number"><?php echo $stats['bookings']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Earnings</h3>
            <p class="stat-number">LKR <?php echo number_format($stats['earnings'], 2); ?></p>
        </div>
    </div>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>