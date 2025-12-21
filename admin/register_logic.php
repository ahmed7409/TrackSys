<?php
session_start();
require_once '../config.php';
require_once 'admin_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $secret_key = trim($_POST['secret_key']);

    if ($secret_key !== REGISTRATION_SECRET_KEY) {
        $_SESSION['register_error'] = 'Invalid secret key!';
        header('location: register.php');
        exit();
    }


    if (empty($username) || empty($password)) {
        $_SESSION['register_error'] = 'Username and password are required!';
        header('location: register.php');
        exit();
    }


    $check_sql = "SELECT id FROM admins WHERE username = ?";
    if ($stmt = $conn->prepare($check_sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['register_error'] = 'Username already exists!';
            $stmt->close();
            header('location: register.php');
            exit();
        }
        $stmt->close();
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $insert_sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    if ($stmt = $conn->prepare($insert_sql)) {
        $stmt->bind_param("ss", $username, $hashed_password);
        if ($stmt->execute()) {
            $_SESSION['register_success'] = 'Registration successful! You can now log in.';
        } else {
            $_SESSION['register_error'] = 'Registration failed. Please try again.';
        }
        $stmt->close();
    }

    $conn->close();
    header('location: register.php');
    exit();
}
?>