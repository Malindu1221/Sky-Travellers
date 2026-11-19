<?php
require_once 'db_connection.php';

function sanitize_input($data) {
    // Trim whitespace from the beginning and end of the string
    $data = trim($data);
    // Remove backslashes if any
    $data = stripslashes($data);
    // Convert special characters to HTML entities
    $data = htmlspecialchars($data);
    return $data;
}


function get_all_flights($mysqli) {
    $sql = "SELECT f.id, f.flight_number, a.name as airline_name 
            FROM flights f 
            JOIN airlines a ON f.airline_id = a.id";
    $result = $mysqli->query($sql);
    $flights = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $flights[] = $row;
        }
    }
    
    return $flights;
}

function getUserData($userId) {
    $result = read('users', ['id' => $userId]);
    return !empty($result) ? $result[0] : null; // Return the first user record, or null if no user found
}

function create($table, $data, $types = null) {
    global $mysqli;

    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), '?'));
    $values = array_values($data);

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $mysqli->prepare($sql);

    // If types are not provided, default to strings
    if ($types === null) {
        $types = str_repeat('s', count($values));
    }

    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        return $mysqli->insert_id;
    } else {
        return false;
    }
}


function read($table, $conditions = [], $fields = '*', $limit = null, $orderBy = null) {
    global $mysqli;

    $sql = "SELECT $fields FROM $table";
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(' AND ', array_map(function($col) {
            return "$col = ?";
        }, array_keys($conditions)));
    }
    
    if ($orderBy) {
        $sql .= " ORDER BY $orderBy";
    }

    if ($limit) {
        $sql .= " LIMIT ?";
    }

    $stmt = $mysqli->prepare($sql);

    $bindValues = array_values($conditions);
    if ($limit) {
        $bindValues[] = $limit;
    }
    
    if (!empty($conditions) || $limit) {
        $types = str_repeat('s', count($conditions)) . ($limit ? 'i' : ''); // Assuming conditions are strings and limit is integer
        $stmt->bind_param($types, ...$bindValues);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function update($table, $data, $conditions, $types = null) {
    global $mysqli;

    $setFields = implode(", ", array_map(function($key) {
        return "$key = ?";
    }, array_keys($data)));

    $conditionFields = implode(" AND ", array_map(function($key) {
        return "$key = ?";
    }, array_keys($conditions)));

    $sql = "UPDATE $table SET $setFields WHERE $conditionFields";
    $stmt = $mysqli->prepare($sql);

    $values = array_merge(array_values($data), array_values($conditions));

    // If types are not provided, default to strings
    if ($types === null) {
        $types = str_repeat('s', count($values)); // Assuming string types
    }

    $stmt->bind_param($types, ...$values);
    
    return $stmt->execute();
}


function delete($table, $conditions) {
    global $mysqli;

    $conditionFields = implode(" AND ", array_map(function($key) {
        return "$key = ?";
    }, array_keys($conditions)));

    $sql = "DELETE FROM $table WHERE $conditionFields";
    $stmt = $mysqli->prepare($sql);

    $types = str_repeat('s', count($conditions)); // Assuming string types
    $stmt->bind_param($types, ...array_values($conditions));

    if ($stmt->execute()) {
        return $stmt->affected_rows; // Return the number of affected rows
    } else {
        return false;
    }
}


