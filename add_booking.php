<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $cat_id = $_POST['cat_id'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];

    $stmt = $conn->prepare("INSERT INTO bookings (customer_id, cat_id, date_from, date_to) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $customer_id, $cat_id, $date_from, $date_to);
    $stmt->execute();

    header("Location: booking_menu.php");
    exit();
}
?>
