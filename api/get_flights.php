<?php
require_once '../includes/db_connection.php';
header('Content-Type: application/json');

$query = "
    SELECT 
        f.id, 
        a.name AS airline_name, 
        f.flight_number, 
        f.economy_seats, 
        f.business_seats, 
        f.first_seats
    FROM 
        flights f
    JOIN 
        airlines a ON f.airline_id = a.id
";

$result = $mysqli->query($query);

if ($result === false) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to execute query: ' . $mysqli->error
    ]);
    exit;
}

$flights = array();
while ($row = $result->fetch_assoc()) {
    $flights[] = $row;
}

echo json_encode([
    'status' => 'success',
    'data' => $flights
]);

$result->free();
$mysqli->close();