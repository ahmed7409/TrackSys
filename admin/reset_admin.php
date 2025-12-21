<?php
require_once '../config.php';


 $username = 'admin';
 $password = 'password123';


 $hashed_password = password_hash($password, PASSWORD_DEFAULT);


 $delete_sql = "DELETE FROM admins WHERE username = ?";
 $conn->prepare($delete_sql)->bind_param("s", $username)->execute();


 $insert_sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
 $stmt = $conn->prepare($insert_sql);
 $stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "Admin account 'admin' with password 'password123' has been reset successfully.";
    echo '<br><a href="login.php">Go to Login</a>';
} else {
    echo "Error resetting admin account.";
}

 $stmt->close();
 $conn->close();
?>