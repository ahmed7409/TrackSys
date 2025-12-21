<?php
require_once '../config.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="orders_export.csv"');

 $output = fopen('php://output', 'w');

// Add the column headings
fputcsv($output, array('Tracking ID', 'Customer Name', 'Customer ID', 'Status', 'Created At'));

// Fetch the data
 $sql = "SELECT tracking_id, customer_name, customer_id, status, created_at FROM orders ORDER BY created_at DESC";
if($result = $conn->query($sql)){
    while($row = $result->fetch_assoc()){
        fputcsv($output, $row);
    }
    $result->free();
}

fclose($output);
 $conn->close();
?>