<?php
// File: /skytravellers/api/edit_airline.php
require_once '../includes/db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? null;

    if (!$id || !$name) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $stmt = $mysqli->prepare("UPDATE airlines SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Airline updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to update airline: ' . $mysqli->error]);
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing airline ID']);
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id, name FROM airlines WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($airline = $result->fetch_assoc()) {
        echo json_encode(['status' => 'success', 'data' => $airline]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Airline not found']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

$mysqli->close();
