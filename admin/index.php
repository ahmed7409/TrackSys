<?php
session_start();
require_once '../config.php';

// --- SECURITY CHECK ---
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('location: login.php');
    exit();
}
// --- SECURITY CHECK END ---


 $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

require_once 'orders_logic.php';
require_once 'settings_logic.php';


 $profile_data = [];
if (file_exists('admin_profile.json')) {
    $json_data = file_get_contents('admin_profile.json');
    $profile_data = json_decode($json_data, true);
} else {
    $profile_data = ['name' => 'Administrator', 'email' => 'admin@example.com'];
}

 $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Order Tracking</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External CSS -->
</head>
<style>
    /* --- style.css --- */
:root {
    --primary-color: #4e73df;
    --primary-color-dark: #2e59d9;
    --success-color: #1cc88a;
    --danger-color: #e74a3b;
    --warning-color: #f6c23e;
    --info-color: #36b9cc;
    --light-color: #f8f9fc;
    --dark-color: #5a5c69;
    --sidebar-bg: #4e73df;
    --sidebar-text: #fff;
    --card-bg: #fff;
    --text-color: #5a5c69;
    --header-bg: #fff;
    --border-color: #e3e6f0;
    --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

/* Dark Theme Variables */
[data-theme="dark"] {
    --light-color: #1a1d23;
    --card-bg: #2e3440;
    --text-color: #d8dee9;
    --header-bg: #2e3440;
    --border-color: #434c5e;
    --sidebar-bg: #2e3440;
    --dark-color: #eceff4;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--light-color);
    color: var(--text-color);
    transition: background-color 0.3s, color 0.3s;
}

.dashboard-container {
    display: flex;
    min-height: 100vh;
}
.main-header {

    justify-content: space-between;
    align-items: center;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.logout-btn {
    background-color: var(--danger-color);
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background-color 0.2s;
}
.logout-btn:hover {
    background-color: #c0392b;
}
/* Sidebar Styles */
.sidebar {
    width: 250px;
    background-color: var(--sidebar-bg);
    color: var(--sidebar-text);
    padding-top: 20px;
    transition: all 0.3s;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    z-index: 1000;
}

.sidebar .brand {
    text-align: center;
    font-size: 1.5rem;
    font-weight: 700;
    padding: 0 20px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-nav {
    list-style: none;
    padding: 20px 0;
}

.sidebar-nav li {
    margin-bottom: 5px;
}

.sidebar-nav a {
    display: block;
    padding: 15px 25px;
    color: var(--sidebar-text);
    text-decoration: none;
    transition: all 0.3s;
    border-left: 3px solid transparent;
}

.sidebar-nav a:hover, .sidebar-nav a.active {
    background-color: rgba(255, 255, 255, 0.1);
    border-left-color: #fff;
}

.sidebar-nav i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* Main Content Styles */
.main-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    margin-left: 250px;
}

.main-header {
    background-color: var(--header-bg);
    padding: 20px 30px;
    box-shadow: var(--card-shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.main-header h1 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--dark-color);
}

.content {
    padding: 30px;
}

/* Card Styles */
.card {
    background: var(--card-bg);
    border-radius: 8px;
    box-shadow: var(--card-shadow);
    margin-bottom: 30px;
    border: 1px solid var(--border-color);
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--dark-color);
}

.card-body {
    padding: 20px;
}

/* Dashboard Stats Cards */
.stats-card {
    text-align: center;
    padding: 25px;
}

.stats-card .stats-icon {
    font-size: 2rem;
    margin-bottom: 15px;
    color: var(--primary-color);
}

.stats-card .stats-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark-color);
}

.stats-card .stats-label {
    font-size: 0.9rem;
    color: var(--text-color);
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    font-size: 1rem;
    background-color: var(--card-bg);
    color: var(--text-color);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: var(--primary-color);
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: 500;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background-color: var(--primary-color);
    color: #fff;
}

.btn-primary:hover {
    background-color: var(--primary-color-dark);
}

.btn-danger {
    background-color: var(--danger-color);
    color: #fff;
}

.btn-danger:hover {
    background-color: #c0392b;
}

/* Table Styles */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.table thead th {
    font-weight: 600;
    background-color: var(--light-color);
    border-bottom: 2px solid var(--border-color);
}

.table tbody tr:hover {
    background-color: var(--light-color);
}

.actions a {
    margin-right: 10px;
    color: var(--primary-color);
    text-decoration: none;
}

.actions a.delete {
    color: var(--danger-color);
}

/* Alert/Message Styles */
.alert {
    padding: 15px 20px;
    border-radius: 4px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    display: flex;
    justify-content: space-between;
    align-items: center;
    animation: fadeIn 0.5s;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert i {
    margin-right: 10px;
}

/* Settings Page Specific Styles */
.settings-section {
    margin-bottom: 40px;
}
.color-palette {
    display: flex;
    gap: 15px;
}
.color-option {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid transparent;
    transition: transform 0.2s, border-color 0.2s;
}
.color-option:hover {
    transform: scale(1.1);
}
.color-option.active {
    border-color: var(--dark-color);
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: 70px;
        text-align: center;
    }
    .sidebar .brand, .sidebar-nav span {
        display: none;
    }
    .main-content {
        margin-left: 70px;
    }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}


/* --- Footer Styles --- */
.main-footer {
    text-align: center;
    padding: 20px;
    margin-top: auto; /* Pushes footer to the bottom */
    color: var(--text-color);
    background-color: var(--card-bg);
    border-top: 1px solid var(--border-color);
}

/* --- Profile Popup Styles --- */
.header-actions {
    position: relative;
    cursor: pointer;
}

.profile-popup {
    position: absolute;
    top: 60px;
    right: 0;
    width: 280px;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: var(--card-shadow);
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: opacity 0.3s, visibility 0.3s, transform 0.3s;
}

.profile-popup.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.profile-popup-header {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    border-bottom: 1px solid var(--border-color);
    color: var(--primary-color);
}

.profile-popup-header i {
    font-size: 1.5rem;
}

.profile-popup-body {
    padding: 15px 20px;
}

.profile-popup-body p {
    margin: 5px 0;
    color: var(--text-color);
}

.profile-popup-footer {
    padding: 15px 20px;
    border-top: 1px solid var(--border-color);
    text-align: right;
}

/* Responsive Design for popup */
@media (max-width: 768px) {
    .profile-popup {
        width: 250px;
        right: -20px;
    }
}
</style>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <i class="fas fa-shipping-fast"></i> <span>TrackSys</span>
        </div>
        <ul class="sidebar-nav">
            <li><a href="index.php?page=dashboard" class="<?php echo $page == 'dashboard' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="index.php?page=orders" class="<?php echo $page == 'orders' ? 'active' : ''; ?>"><i class="fas fa-box"></i> <span>Orders</span></a></li>
            <li><a href="index.php?page=customers" class="<?php echo $page == 'customers' ? 'active' : ''; ?>"><i class="fas fa-users"></i> <span>Customers</span></a></li>
            <li><a href="index.php?page=settings" class="<?php echo $page == 'settings' ? 'active' : ''; ?>"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo ucfirst($page); ?> Panel</h1>
            <div class="header-actions">
                <i class="fas fa-user-circle fa-2x profile-icon" id="profileIcon"></i>
                
                <!-- Profile Popup -->
                <div class="profile-popup" id="profilePopup">
                    <div class="profile-popup-header">
                        <i class="fas fa-user-circle"></i>
                        <h4>Admin Profile</h4>
                    </div>
                    <div class="profile-popup-body">
                        <p><strong>Name:</strong> <span id="profile-name"><?php echo htmlspecialchars($profile_data['name']); ?></span></p>
                        <p><strong>Email:</strong> <span id="profile-email"><?php echo htmlspecialchars($profile_data['email']); ?></span></p>
                    </div>
                    <div class="profile-popup-footer">
                        <a href="index.php?page=settings" class="btn btn-primary">Edit Profile</a>
                    </div>
                    <div class="profile-popup-footer">
                <a href="logout.php" class="btn btn-danger" style="width: 100%; margin-top: 10px;">Logout</a>
            </div>
                </div>
            </div>
        </header>

        <section class="content">
            <?php
           
            switch ($page) {
                case 'orders':
                    require_once 'orders_view.php';
                    break;
                case 'customers':
                    require_once 'customers_view.php';
                    break;
                case 'settings':
                    require_once 'settings_view.php';
                    break;
                case 'dashboard':
                default:
                    require_once 'dashboard_view.php';
                    break;
            }
            ?>
        </section>

        <!-- Footer -->
        <footer class="main-footer">
            <p>&copy; <?php echo date('Y'); ?> TrackSys. All Rights Reserved. Developed by khdxsohee</p>
        </footer>
    </main>
</div>

<!-- External JS -->
<script src="script.js"></script>

</body>
</html>