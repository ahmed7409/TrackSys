<?php
require_once '../config.php';

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
?>