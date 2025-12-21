<?php
require_once '../config.php';
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
?>