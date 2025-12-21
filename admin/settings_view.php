<?php
require_once '../config.php';

 $message = '';

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $admin_name = trim($_POST['admin_name']);
    $admin_email = trim($_POST['admin_email']);

    if (!empty($admin_name) && !empty($admin_email)) {
        $profile_data = [
            'name' => $admin_name,
            'email' => $admin_email
        ];
        file_put_contents('admin_profile.json', json_encode($profile_data));
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['message'] = "Name and Email cannot be empty.";
    }
    header("location: index.php?page=settings");
    exit();
}

// Read current profile data to pre-fill the form
 $profile_data = [];
if (file_exists('admin_profile.json')) {
    $json_data = file_get_contents('admin_profile.json');
    $profile_data = json_decode($json_data, true);
} else {
    $profile_data = ['name' => 'Administrator', 'email' => 'admin@example.com'];
}
?>

<!-- Success/Error Message Display -->
<?php if(isset($_SESSION['message'])): ?>
    <div class="alert <?php echo strpos($_SESSION['message'], 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>" id="messageAlert">
        <div>
            <i class="fas <?php echo strpos($_SESSION['message'], 'Error') !== false ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
        <i class="fas fa-times" onclick="document.getElementById('messageAlert').style.display='none';"></i>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><i class="fas fa-palette"></i> Appearance</div>
    <div class="card-body">
        <div class="settings-section">
            <h4>Theme</h4>
            <button id="theme-toggle" class="btn btn-primary">Toggle Dark/Light Mode</button>
        </div>
        <div class="settings-section">
            <h4>Color Scheme</h4>
            <div class="color-palette">
                <div class="color-option" style="background-color: #4e73df;" data-color="#4e73df"></div>
                <div class="color-option" style="background-color: #1cc88a;" data-color="#1cc88a"></div>
                <div class="color-option" style="background-color: #36b9cc;" data-color="#36b9cc"></div>
                <div class="color-option" style="background-color: #f6c23e;" data-color="#f6c23e"></div>
                <div class="color-option" style="background-color: #e74a3b;" data-color="#e74a3b"></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-user"></i> Admin Profile</div>
    <div class="card-body">
<form action="index.php?page=settings" method="post">            <div class="form-group">
                <label>Admin Name</label>
                <input type="text" class="form-control" name="admin_name" value="<?php echo htmlspecialchars($profile_data['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="admin_email" value="<?php echo htmlspecialchars($profile_data['email']); ?>" required>
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-download"></i> Data Management</div>
    <div class="card-body">
        <a href="export_orders.php" class="btn btn-primary"><i class="fas fa-file-csv"></i> Export All Orders (CSV)</a>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-info-circle"></i> System Information</div>
    <div class="card-body">
        <ul>
            <li><strong>PHP Version:</strong> <?php echo phpversion(); ?></li>
            <li><strong>MySQL Server Info:</strong> <?php echo $conn->server_info; ?></li>
        </ul>
    </div>
</div>