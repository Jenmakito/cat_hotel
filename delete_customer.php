<?php
include 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // เตรียมคำสั่ง SQL เพื่อลบข้อมูลลูกค้า
    $sql = "DELETE FROM customers WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // ลบสำเร็จ กลับไปหน้า index.php หรือหน้าลูกค้า
        header("Location: customers.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบ: " . $conn->error;
    }
} else {
    echo "คำขอไม่ถูกต้อง";
}
?>
