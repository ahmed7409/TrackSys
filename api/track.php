<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


header('Content-Type: application/json');


require_once '../config.php';


if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}


if (isset($_GET['id'])) {
    $tracking_id = trim($_GET['id']);

    if (empty($tracking_id)) {
        echo json_encode(['success' => false, 'message' => 'Tracking ID cannot be empty.']);
        exit();
    }

    // Prepare statement to prevent SQL injection
    $sql = "SELECT customer_name, status FROM orders WHERE tracking_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $tracking_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $json_response = [
                    "success" => true,
                    "customerName" => $row['customer_name'],
                    "status" => $row['status']
                ];
                // Check for JSON encoding errors
                if (json_encode($json_response) === false) {
                    echo json_encode(['success' => false, 'message' => 'JSON encoding error: ' . json_last_error_msg()]);
                } else {
                    echo json_encode($json_response);
                }
            } else {
                
                echo json_encode(['success' => false, 'message' => 'Tracking ID not found.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Database query failed.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare database statement.']);
    }
} else {
    
    echo json_encode(['success' => false, 'message' => 'No tracking ID provided.']);
}

 $conn->close();
?>