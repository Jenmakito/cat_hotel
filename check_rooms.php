<?php
include 'db_connect.php';

// จำนวนห้องทั้งหมดแต่ละประเภท
$total_rooms = [
    'ธรรมดา' => 7,
    'VIP' => 3
];

// ดึงจำนวนการจองของแต่ละประเภทห้องทั้งหมด
$sql = "SELECT room_type, COUNT(*) as booked_count
        FROM bookings
        GROUP BY room_type";
$result = $conn->query($sql);

// เตรียมข้อมูลสรุป
$room_summary = [
    'ธรรมดา' => 0,
    'VIP' => 0
];

while ($row = $result->fetch_assoc()) {
    $type = $row['room_type'];
    $room_summary[$type] = $row['booked_count'];
}

// ดึงรายละเอียดการจองทั้งหมด
$sql_all = "SELECT b.*, c.full_name, m.name AS cat_name
            FROM bookings b
            JOIN customers c ON b.customer_id = c.id
            JOIN cats m ON b.cat_id = m.id
            ORDER BY b.date_from DESC";
$result_all = $conn->query($sql_all);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตรวจสอบห้อง - ระบบจองห้องพักแมว</title>
    <link rel="stylesheet" href="stylexw.css">
    <style>
    .cat-table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .cat-table th,
    .cat-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    .cat-table th {
        background-color: #f0f0f0;
    }

    .content {
        padding: 20px;
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
    <h1>สรุปการใช้ห้องพัก</h1>

    <table class="cat-table">
        <thead>
            <tr>
                <th>ประเภทห้อง</th>
                <th>จำนวนห้องทั้งหมด</th>
                <th>จำนวนการจองทั้งหมด</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ธรรมดา</td>
                <td><?= $total_rooms['ธรรมดา'] ?></td>
                <td><?= $room_summary['ธรรมดา'] ?></td>
            </tr>
            <tr>
                <td>VIP</td>
                <td><?= $total_rooms['VIP'] ?></td>
                <td><?= $room_summary['VIP'] ?></td>
            </tr>
        </tbody>
    </table>

    <h2>รายการการจองทั้งหมด</h2>
    <table class="cat-table">
        <thead>
            <tr>
                <th>ชื่อลูกค้า</th>
                <th>ชื่อแมว</th>
                <th>ประเภทห้อง</th>
                <th>วันที่เข้า</th>
                <th>วันที่ออก</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result_all->num_rows > 0): ?>
                <?php while ($row = $result_all->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['cat_name']) ?></td>
                        <td><?= htmlspecialchars($row['room_type']) ?></td>
                        <td><?= htmlspecialchars($row['date_from']) ?></td>
                        <td><?= htmlspecialchars($row['date_to']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">ไม่มีข้อมูลการจอง</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
