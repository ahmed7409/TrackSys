<?php


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_order'])) {
    $tracking_id = trim($_POST['tracking_id']);
    $customer_name = trim($_POST['customer_name']);

    if(empty($tracking_id) || empty($customer_name)){
        $_SESSION['message'] = "Tracking ID aur Customer Name zaroori hain.";
    } else {
      
        function generateUniqueCustomerId($conn) {
            $prefix = 'KS';
            $sql = "SELECT customer_id FROM orders ORDER BY id DESC LIMIT 1";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $last_id = $row['customer_id'];
                $last_number = (int)substr($last_id, strlen($prefix));
                $new_number = $last_number + 1;
            } else {
                $new_number = 1;
            }
            $new_customer_id = $prefix . str_pad($new_number, 6, '0', STR_PAD_LEFT);
            return $new_customer_id;
        }
        
        $new_customer_id = generateUniqueCustomerId($conn);
        $sql = "INSERT INTO orders (tracking_id, customer_name, customer_id) VALUES (?, ?, ?)";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("sss", $tracking_id, $customer_name, $new_customer_id);
            
            if($stmt->execute()){
                $_SESSION['message'] = "Order successfully added! Customer ID is: <strong>" . $new_customer_id . "</strong>";
            } else {
                $_SESSION['message'] = "Error! Yeh Tracking ID pehlay se mojood hai.";
            }
            $stmt->close();
        }
    }
   
    header("location: index.php?page=orders");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $tracking_id = $_POST['tracking_id'];
    $status = $_POST['status'];

    $sql = "UPDATE orders SET status = ? WHERE tracking_id = ?";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ss", $status, $tracking_id);
        $stmt->execute();
        $stmt->close();
    }

    header("location: index.php?page=orders");
    exit();
}


if (isset($_GET['delete'])) {
    $tracking_id = $_GET['delete'];

    $sql = "DELETE FROM orders WHERE tracking_id = ?";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("s", $tracking_id);
        $stmt->execute();
        $stmt->close();
    }

    header("location: index.php?page=orders");
    exit();
}


 $orders = [];
 $sql = "SELECT * FROM orders ORDER BY created_at DESC";
if($result = $conn->query($sql)){
    while($row = $result->fetch_assoc()){
        $orders[] = $row;
    }
    $result->free();
}

 $conn->close();
?>

<?php if(isset($_SESSION['message'])): ?>
    <div class="alert <?php echo strpos($_SESSION['message'], 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>" id="messageAlert">
        <div>
            <i class="fas <?php echo strpos($_SESSION['message'], 'Error') !== false ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
        <i class="fas fa-times" onclick="document.getElementById('messageAlert').style.display='none';"></i>
    </div>
<?php endif; ?>

<!-- Add New Order Card -->
<div class="card">
    <div class="card-header"><i class="fas fa-plus-circle"></i> Add New Order</div>
    <div class="card-body">
      
        <form action="index.php?page=orders" method="post">
            <input type="hidden" name="add_order" value="1">
            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex-grow: 1;">
                    <input type="text" class="form-control" name="tracking_id" placeholder="Enter Tracking ID (e.g., XYZ123)" required>
                </div>
                <div class="form-group" style="flex-grow: 1;">
                    <input type="text" class="form-control" name="customer_name" placeholder="Enter Customer Name" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Order</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- All Orders Card -->
<div class="card">
    <div class="card-header"><i class="fas fa-list"></i> All Orders</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tracking ID</th>
                        <th>Customer Name</th>
                        <th>Customer ID</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['tracking_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_id']); ?></td>
                        <td>
<form action="index.php?page=orders" method="post">                                <input type="hidden" name="update_status" value="1">
                                <input type="hidden" name="tracking_id" value="<?php echo $order['tracking_id']; ?>">
                                <select name="status" class="form-control" onchange="this.form.submit()" style="width: auto;">
                                    <option value="Processing" <?php if($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                    <option value="Packed" <?php if($order['status'] == 'Packed') echo 'selected'; ?>>Packed</option>
                                    <option value="On-The-Way" <?php if($order['status'] == 'On-The-Way') echo 'selected'; ?>>On the Way</option>
                                    <option value="Delivered" <?php if($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                    <option value="Canceled" <?php if($order['status'] == 'Canceled') echo 'selected'; ?>>Canceled</option>
                                    <option value="Returned" <?php if($order['status'] == 'Returned') echo 'selected'; ?>>Returned</option>
                                </select>
                            </form>
                        </td>
                        <td class="actions">
<a href="index.php?page=orders&delete=<?php echo $order['tracking_id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this order?');" title="Delete">
    <i class="fas fa-trash-alt"></i>
</a>                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>