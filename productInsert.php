<?php
include "connectDB.php";

// รับข้อมูลที่ส่งมาจาก form
$data = json_decode(file_get_contents("php://input"));

// เตรียมข้อมูลสำหรับบันทึกลงในฐานข้อมูล
$name = $conn->real_escape_string($data->product);
$price = (float)$data->price;
$quantity = (int)$data->quantity;
$totalPrice = (float)$data->totalPrice;

// เขียนคำสั่ง SQL เพื่อบันทึกข้อมูล
$sql = "INSERT INTO cart (product_name, price, quantity, total_price) VALUES ('$name', $price, $quantity, $totalPrice)";

if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูลเรียบร้อย";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// ปิดการเชื่อมต่อกับฐานข้อมูล MySQL
$conn->close();
?>