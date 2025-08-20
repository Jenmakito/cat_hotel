<?php
include 'db_connect.php';

$customer_id = $_POST['customer_id'];
$cat_id      = $_POST['cat_id'];
$room_type   = $_POST['room_type'];
$date_from   = $_POST['date_from'];
$date_to     = $_POST['date_to'];

// กำหนดจำนวนห้องตามประเภท
$room_limits = [
    'ธรรมดา' => 7,
    'VIP'     => 3
];

// ตรวจสอบจำนวนการจองในช่วงเวลานั้น
$sql = "SELECT COUNT(*) AS booked_count
        FROM bookings
        WHERE room_type = ?
          AND date_from <= ?
          AND date_to >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $room_type, $date_to, $date_from);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$booked_count = (int) $row['booked_count'];

// เช็คว่ามีห้องว่างหรือไม่
if ($booked_count < $room_limits[$room_type]) {
    $insert = $conn->prepare("INSERT INTO bookings (customer_id, cat_id, room_type, date_from, date_to) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("iisss", $customer_id, $cat_id, $room_type, $date_from, $date_to);
    $insert->execute();
    header("Location: booking_menu.php");
    exit();
} else {
    echo "<script>alert('ไม่มีห้องว่างในประเภท $room_type ในช่วงวันที่ที่คุณเลือก'); window.history.back();</script>";
}
?>
