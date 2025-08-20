<?php
include 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลแมวทั้งหมด
$sql = "SELECT * FROM cats";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการข้อมูลแมว - ระบบจองห้องพักแมว</title>
    <link rel="stylesheet" href="stylexw.css">
</head>
<body>

<div class="sidebar">
    <h2>เมนูหลัก</h2>
    <ul class="menu-list">
        <li><a href="index.php">หน้าเเรก</a></li>
        <li><a href="cat_menu.php">จัดการข้อมูลแมว</a></li>
        <li><a href="booking_menu.php">จองห้องพัก</a></li>
    </ul>

    <h2>เมนูเพิ่มเติม</h2>
    <ul class="menu-list">
        <li><a href="check_rooms.php">ตรวจสอบห้อง</a></li>
        <li><a href="payment.php">ชำระเงิน</a></li>
        <li><a href="#">รายงาน / วิเคราะห์ข้อมูล</a></li>
        <li><a href="login.php">ออกจากระบบ</a></li>
    </ul>
</div>

<div class="content">
    <h1>จัดการข้อมูลแมว</h1>

    <!-- ฟอร์มเพิ่มข้อมูลแมว -->
    <form class="cat-form" action="add_cat.php" method="POST">
        <h3>เพิ่มข้อมูลแมว</h3>
        <label for="catName">ชื่อแมว:</label>
        <input type="text" id="catName" name="catName" required>

        <label for="catBreed">สี:</label>
        <input type="text" id="catBreed" name="catBreed">

        <label for="catAge">อายุ (ปี):</label>
        <input type="number" id="catAge" name="catAge" min="0">

        <label for="catGender">เพศ:</label>
        <select id="catGender" name="catGender">
            <option value="male">ผู้</option>
            <option value="female">เมีย</option>
        </select>

        <button type="submit">เพิ่มข้อมูล</button>
    </form>
    <hr>

    <h3>รายชื่อแมว</h3>
    <table class="cat-table">
        <thead>
            <tr>
                <th>ชื่อ</th>
                <th>สี</th>
                <th>อายุ</th>
                <th>เพศ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['color']) ?></td>
                <td><?= htmlspecialchars($row['age']) ?></td>
                <td><?= $row['gender'] == 'male' ? 'male' : 'female' ?></td>
                <td>
                    <!-- ปุ่มลบ / แก้ไข (ยังไม่เชื่อมไฟล์) -->
                    <form method="POST" action="delete_cat.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?')">ลบ</button>
                    </form>

                    <!-- ปุ่มแก้ไข (option) -->
                    <!-- <a href="edit_cat.php?id=<?= $row['id'] ?>"><button>แก้ไข</button></a> -->
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
