<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']); // ป้องกัน SQL Injection

    $sql = "DELETE FROM cats WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: cat_menu.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
} else {
    echo "ไม่พบข้อมูลที่ต้องการลบ";
}
?>
