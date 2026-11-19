<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$pageTitle = "Passenger List - Sky Travellers Admin";
$pageName = 'admin_passenger_list';
include '../../includes/admin_header.php';
include '../../includes/db_connection.php';

// Handle search
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
$searchCondition = '';
if (!empty($search)) {
    $searchCondition = " WHERE pd.first_name LIKE '%$search%' OR pd.last_name LIKE '%$search%' OR pd.mobile_number LIKE '%$search%'";
}

// Fetch passengers
$query = "SELECT pd.id, CONCAT(pd.first_name, ' ', pd.last_name) AS name, pd.mobile_number, pd.date_of_birth, pd.gender, b.booking_id AS booking_id
          FROM passenger_details pd
          JOIN bookings b ON pd.booking_id = b.booking_id
          $searchCondition
          ORDER BY pd.id DESC
          LIMIT 100"; // Limiting to 100 for performance, adjust as needed

$result = $mysqli->query($query);
?>

<div class="admin-container">
    <h1>Passenger List</h1>
    
    <div class="search-container">
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Search by name or mobile" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table class="passenger-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Mobile No</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Booking</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $count++; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                <td><a href="view_booking.php?id=<?php echo $row['booking_id']; ?>">View</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/admin_footer.php'; ?>