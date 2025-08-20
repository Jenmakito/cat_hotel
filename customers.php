<?php
include 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลลูกค้าทั้งหมด
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการข้อมูลลูกค้า - ระบบจองห้องพักแมว</title>
    <link rel="stylesheet" href="stylexw.css">
</head>
<body>

<div class="sidebar">
    <h2>เมนูหลัก</h2>
    <ul class="menu-list">
        <li><a href="index.php">ข้อมูลลูกค้า</a></li>
        <li><a href="cat_menu.php">จัดการข้อมูลแมว</a></li>
        <li><a href="booking_menu.php">จองห้องพัก</a></li>
    </ul>

    <h2>เมนูเพิ่มเติม</h2>
    <ul class="menu-list">
        <li><a href="#">ตรวจสอบห้อง</a></li>
        <li><a href="#">ชำระเงิน</a></li>
        <li><a href="#">รายงาน / วิเคราะห์ข้อมูล</a></li>
        <li><a href="login.php">ออกจากระบบ</a></li>
    </ul>
</div>

<div class="content">
    <h1>จัดการข้อมูลลูกค้า</h1>

    <!-- ฟอร์มเพิ่มข้อมูลลูกค้า -->
    <form class="cat-form" action="add_customer.php" method="POST">
        <h3>เพิ่มข้อมูลลูกค้า</h3>
        
        <label for="fullName">ชื่อลูกค้า:</label>
        <input type="text" id="fullName" name="full_name" required>

        <label for="phone">เบอร์โทร:</label>
        <input type="text" id="phone" name="phone">

        <label for="email">อีเมล:</label>
        <input type="email" id="email" name="email">

        <label for="address">ที่อยู่:</label>
        <textarea id="address" name="address" rows="3"></textarea>

        <button type="submit">เพิ่มข้อมูล</button>
    </form>

    <hr>

    <h3>รายชื่อลูกค้า</h3>
    <table class="cat-table">
        <thead>
            <tr>
                <th>ชื่อ</th>
                <th>เบอร์โทร</th>
                <th>อีเมล</th>
                <th>ที่อยู่</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td>
                    <div style="display: flex; gap: 5px; justify-content: center;">
                        <form method="POST" action="delete_customer.php" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?')">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit">ลบ</button>
                        </form>

                        <a href="edit_customer.php?id=<?= $row['id'] ?>">
                            <button type="button">แก้ไข</button>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
