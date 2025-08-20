<?php
include 'db_connect.php';

// ดึงข้อมูลลูกค้าและแมว
$customers = $conn->query("SELECT id, full_name FROM customers");
$cats = $conn->query("SELECT id, name FROM cats");

// ดึงข้อมูลการจองทั้งหมด (ไม่มี room_number)
$sql = "SELECT b.id, c.full_name, m.name AS cat_name, b.room_type, b.date_from, b.date_to
        FROM bookings b
        JOIN customers c ON b.customer_id = c.id
        JOIN cats m ON b.cat_id = m.id
        ORDER BY b.date_from DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จองห้องพัก - ระบบจองห้องพักแมว</title>
    <link rel="stylesheet" href="stylexw.css">
    <style>
        .cat-form, select, input[type="date"], button {
            margin-bottom: 10px;
            display: block;
            width: 100%;
            padding: 8px;
        }
        .cat-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cat-table th, .cat-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        .cat-form {
            max-width: 500px;
        }
    </style>
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
    <h1>จองห้องพัก</h1>

    <!-- ฟอร์มจองห้องพัก -->
    <form class="cat-form" action="add_booking.php" method="POST">
        <h3>เพิ่มการจอง</h3>

        <label for="customer_id">ชื่อลูกค้า:</label>
        <select id="customer_id" name="customer_id" required>
            <option value="">-- เลือกลูกค้า --</option>
            <?php $customers->data_seek(0); while($c = $customers->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['full_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="cat_id">ชื่อแมว:</label>
        <select id="cat_id" name="cat_id" required>
            <option value="">-- เลือกแมว --</option>
            <?php $cats->data_seek(0); while($m = $cats->fetch_assoc()): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="room_type">ประเภทห้อง:</label>
        <select id="room_type" name="room_type" required>
            <option value="">-- เลือกประเภทห้อง --</option>
            <option value="ธรรมดา">ธรรมดา</option>
            <option value="VIP">VIP</option>
        </select>

        <label for="date_from">วันที่เข้าพัก:</label>
        <input type="date" id="date_from" name="date_from" required>

        <label for="date_to">วันที่ออก:</label>
        <input type="date" id="date_to" name="date_to" required>

        <button type="submit">เพิ่มการจอง</button>
    </form>

    <hr>

    <h3>รายการการจอง</h3>
    <table class="cat-table">
        <thead>
            <tr>
                <th>ลูกค้า</th>
                <th>แมว</th>
                <th>ประเภทห้อง</th>
                <th>วันที่เข้า</th>
                <th>วันที่ออก</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['cat_name']) ?></td>
                <td><?= htmlspecialchars($row['room_type']) ?></td>
                <td><?= htmlspecialchars($row['date_from']) ?></td>
                <td><?= htmlspecialchars($row['date_to']) ?></td>
                <td>
                    <div style="display: flex; gap: 5px; justify-content: center;">
                        <form method="POST" action="delete_booking.php" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบการจองนี้?')">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit">ลบ</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
