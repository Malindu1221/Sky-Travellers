<?php
require_once '../includes/db_connection.php';

header('Content-Type: application/json');

$query = "
    SELECT 
        a.id,
        a.name,
        a.flights as flight_count,
        a.added_date
    FROM 
        airlines a
    GROUP BY 
        a.id
";

$result = $mysqli->query($query);

if ($result) {
    $airlines = array();
    while ($row = $result->fetch_assoc()) {
        $airlines[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $airlines
    ]);
    
    $result->free();
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch airlines: ' . $mysqli->error
    ]);
}

$mysqli->close();