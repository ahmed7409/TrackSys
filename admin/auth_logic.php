<?php
session_start();
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Username and password are required!';
        header('location: login.php');
        exit();
    }

    $sql = "SELECT id, username, password FROM admins WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            

            if (password_verify($password, $user['password'])) {

                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user'] = $user['username'];
                $_SESSION['admin_id'] = $user['id'];
                header('location: index.php');
            } else {

                $_SESSION['login_error'] = 'Invalid username or password!';
                header('location: login.php');
            }
        } else {

            $_SESSION['login_error'] = 'Invalid username or password!';
            header('location: login.php');
        }
        $stmt->close();
    } else {
        $_SESSION['login_error'] = 'Database error. Please try again later.';
        header('location: login.php');
    }

    $conn->close();
    exit();
}
?>