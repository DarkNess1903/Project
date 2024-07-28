<?php
include "connectDB.php";

// ดึงข้อมูลรายได้รายเดือนจากฐานข้อมูล
function getMonthlyRevenue($conn) {
    $sql = "SELECT 
                DATE_FORMAT(order_time, '%Y-%m') AS month, 
                SUM(price * quantity) AS total_revenue 
            FROM Orders 
            WHERE order_time >= DATE_SUB(NOW(), INTERVAL 1 YEAR)
            GROUP BY month 
            ORDER BY month DESC 
            LIMIT 12";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

$monthlyRevenue = getMonthlyRevenue($conn);
echo json_encode($monthlyRevenue);
?>