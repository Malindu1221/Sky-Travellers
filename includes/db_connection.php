<?php
$host = 'localhost';
$dbname = 'skytravellers';
$username = 'root';
$password = '';

// Create connection using mysqli
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}