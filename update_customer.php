<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);

    $sql = "UPDATE customers SET 
                full_name = '$full_name',
                phone = '$phone',
                email = '$email',
                address = '$address'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: customers.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดต: " . $conn->error;
    }
} else {
    echo "คำขอไม่ถูกต้อง";
}
?>
