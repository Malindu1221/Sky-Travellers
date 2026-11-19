<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Sky Travellers Admin</title>
    <link rel="stylesheet" href="/skytravellers/assets/css/styles.css">
    <link rel="stylesheet" href="/skytravellers/assets/css/admin_styles.css">
    <link rel="stylesheet" href="/skytravellers/assets/css/<?php echo $pageName; ?>.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <!-- <img src="/images/logo.png" alt="Sky Travellers Logo"> -->
             <h3 style="color:aliceblue">SkyTravellers</h3>
        </div>
        <div class="admin-info">
            <span class="admin-name"><?php echo $_SESSION['firstname']; ?></span>
            <a href="/skytravellers/api/logout.php?logout=true" class="logout-btn">Logout</a>
        </div>
    </header>
    <nav class="admin-nav">
        <ul>
            <li><a href="/skytravellers/pages/admin/admin_dashboard.php" <?php if($pageName == 'dashboard') echo 'class="active"'; ?>>Dashboard</a></li>
            <li><a href="/skytravellers/pages/admin/airline_management.php" <?php if($pageName == 'airline_management') echo 'class="active"'; ?>>Airline Management</a></li>
            <li><a href="/skytravellers/pages/admin/flight_management.php" <?php if($pageName == 'flight_management') echo 'class="active"'; ?>>Flight Management</a></li>
            <li><a href="/skytravellers/pages/admin/flight_schedule.php" <?php if($pageName == 'schedule_flight') echo 'class="active"'; ?>>Schedule Flight</a></li>
            <li><a href="/skytravellers/pages/admin/passenger_list.php" <?php if($pageName == 'passenger_list') echo 'class="active"'; ?>>Passenger List</a></li>
            <li><a href="/skytravellers/pages/account.php" <?php if($pageName == 'profile') echo 'class="active"'; ?>>Admin Profile</a></li>
        </ul>
    </nav>
    <div class="admin-container"></div>