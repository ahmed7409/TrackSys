<?php
// Get stats from database
 $totalOrders = 0;
 $totalCustomers = 0;
 $processingOrders = 0;
 $deliveredOrders = 0;

 $sql = "SELECT COUNT(id) as count FROM orders";
if($result = $conn->query($sql)){
    $row = $result->fetch_assoc();
    $totalOrders = $row['count'];
}

 $sql = "SELECT COUNT(DISTINCT customer_id) as count FROM orders";
if($result = $conn->query($sql)){
    $row = $result->fetch_assoc();
    $totalCustomers = $row['count'];
}

 $sql = "SELECT COUNT(id) as count FROM orders WHERE status = 'Processing'";
if($result = $conn->query($sql)){
    $row = $result->fetch_assoc();
    $processingOrders = $row['count'];
}

 $sql = "SELECT COUNT(id) as count FROM orders WHERE status = 'Delivered'";
if($result = $conn->query($sql)){
    $row = $result->fetch_assoc();
    $deliveredOrders = $row['count'];
}

 $conn->close();
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

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
    <div class="card stats-card">
        <div class="stats-icon"><i class="fas fa-box"></i></div>
        <div class="stats-number"><?php echo $totalOrders; ?></div>
        <div class="stats-label">Total Orders</div>
    </div>
    <div class="card stats-card">
        <div class="stats-icon"><i class="fas fa-users"></i></div>
        <div class="stats-number"><?php echo $totalCustomers; ?></div>
        <div class="stats-label">Total Customers</div>
    </div>
    <div class="card stats-card">
        <div class="stats-icon"><i class="fas fa-spinner"></i></div>
        <div class="stats-number"><?php echo $processingOrders; ?></div>
        <div class="stats-label">Processing Orders</div>
    </div>
    <div class="card stats-card">
        <div class="stats-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stats-number"><?php echo $deliveredOrders; ?></div>
        <div class="stats-label">Delivered Orders</div>
    </div>
</div>