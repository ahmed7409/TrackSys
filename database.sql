CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT, 
  `tracking_id` varchar(50) NOT NULL,  
  `customer_name` varchar(255) NOT NULL, 
  `customer_id` varchar(100) DEFAULT NULL, 
  `status` enum('Processing','Packed','On-the-Way','Delivered','Canceled','Returned') NOT NULL DEFAULT 'Processing', 
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
  PRIMARY KEY (`id`),
  UNIQUE KEY `tracking_id` (`tracking_id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;