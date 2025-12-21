<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TrackSys</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .login-container { background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; width: 100%; max-width: 400px; }
        .login-container h2 { margin-bottom: 20px; color: #5a5c69; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #5a5c69; }
        .form-control { width: 100%; padding: 10px 15px; border: 1px solid #d1d3e2; border-radius: 5px; font-size: 1rem; }
        .btn { width: 100%; padding: 12px; font-size: 1rem; font-weight: 500; border: none; border-radius: 5px; cursor: pointer; background-color: #4e73df; color: #fff; transition: background-color 0.3s; }
        .btn:hover { background-color: #2e59d9; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: left; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2><i class="fas fa-shipping-fast"></i> TrackSys Admin</h2>
        
        <?php if(isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['login_error']; 
                    unset($_SESSION['login_error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="auth_logic.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        <p style="margin-top: 20px;"><a href="register.php">Don't have an account? Register</a></p>
        </form>
    </div>
</body>
</html>