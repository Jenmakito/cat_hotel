<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "ไม่พบข้อมูลลูกค้า";
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM customers WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    echo "ไม่พบลูกค้า";
    exit;
}

$customer = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลลูกค้า</title>
    <link rel="stylesheet" href="stylexw.css">
</head>
<body>

<div class="sidebar">
    <h2>เมนูหลัก</h2>
    <ul class="menu-list">
        <li><a href="index.php">ข้อมูลลูกค้า</a></li>
        <li><a href="cat_menu.php">จัดการข้อมูลแมว</a></li>
    </ul>
</div>

<div class="content">
    <h1>แก้ไขข้อมูลลูกค้า</h1>

    <form class="cat-form" action="update_customer.php" method="POST">
        <input type="hidden" name="id" value="<?= $customer['id'] ?>">

        <label for="full_name">ชื่อลูกค้า:</label>
        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($customer['full_name']) ?>" required>

        <label for="phone">เบอร์โทร:</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>">

        <label for="email">อีเมล:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>">

        <label for="address">ที่อยู่:</label>
        <textarea id="address" name="address" rows="3"><?= htmlspecialchars($customer['address']) ?></textarea>

        <button type="submit">บันทึกการเปลี่ยนแปลง</button>
        <a href="index.php"><button type="button">ยกเลิก</button></a>
    </form>
</div>

</body>
</html>
