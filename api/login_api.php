<?php
session_start();
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password, firstname, role FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['user_role'] = $user['role'];
            if($_SESSION['user_role']=='admin'){
                header('Location: ../pages/admin');
            }
            else{
                header('Location: ../pages/landing.php');
            }
            echo "Login successful";
            // print firstname
            // echo $_SESSION['firstname'];
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No user found with that email address.";
    }

    $stmt->close();
    $mysqli->close();

    if (isset($error)) {
        echo $error;
    }
}