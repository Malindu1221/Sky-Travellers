<?php
require_once '../includes/db_connection.php';
header('Content-Type: application/json');

// SQL query to fetch flight details
$query = "
    SELECT 
        sf.id, 
        sf.flight_id, 
        a.name AS airline_name, 
        f.flight_number, 
        sf.source, 
        sf.destination, 
        sf.arrival_datetime, 
        sf.departure_datetime, 
        sf.economy_seats, 
        sf.business_seats, 
        sf.first_class_seats, 
        sf.economy_cost, 
        sf.business_cost, 
        sf.first_class_cost
    FROM 
        scheduled_flights sf
    JOIN 
        flights f ON sf.flight_id = f.id
    JOIN 
        airlines a ON f.airline_id = a.id
    ORDER BY 
        sf.departure_datetime
";

// Execute the query
$result = $mysqli->query($query);

// Error handling
if ($result === false) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to execute query: ' . $mysqli->error
    ]);
    exit;
}

// Fetch and structure data
$flights = array();
while ($row = $result->fetch_assoc()) {
    $flights[] = $row;
}

// Send the JSON response
echo json_encode([
    'status' => 'success',
    'data' => $flights
]);

// Free result and close connection
$result->free();
$mysqli->close();
