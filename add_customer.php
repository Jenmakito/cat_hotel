<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);

    $sql = "INSERT INTO customers (full_name, phone, email, address) VALUES ('$full_name', '$phone', '$email', '$address')";

    if ($conn->query($sql) === TRUE) {
        header("Location: customers.php");
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>
