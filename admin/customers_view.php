<?php
 $customers = [];
 $sql = "SELECT customer_id, customer_name, COUNT(tracking_id) as total_orders FROM orders GROUP BY customer_id ORDER BY total_orders DESC";
if($result = $conn->query($sql)){
    while($row = $result->fetch_assoc()){
        $customers[] = $row;
    }
    $result->free();
}
 $conn->close();
?>

<div class="card">
    <div class="card-header"><i class="fas fa-users"></i> All Customers</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Total Orders</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                        <td><?php echo $customer['total_orders']; ?></td>
                        <td class="actions">
                            <a href="index.php?page=orders&search=<?php echo urlencode($customer['customer_id']); ?>" title="View Orders">
                                <i class="fas fa-eye"></i> View Orders
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>