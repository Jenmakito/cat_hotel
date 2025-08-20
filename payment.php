<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

// อัปเดตสถานะชำระเงิน
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    $stmt = $conn->prepare("UPDATE bookings SET paid = 1 WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปเดตสถานะได้']);
    }
    exit;
}

// ดึงรายการ
$sql = "SELECT b.id, c.full_name, m.name AS cat_name, b.room_type, b.date_from, b.date_to, b.paid
        FROM bookings b
        JOIN customers c ON b.customer_id = c.id
        JOIN cats m ON b.cat_id = m.id
        ORDER BY b.date_from DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <title>ชำระเงิน - ระบบจองห้องพักแมว</title>
    <link rel="stylesheet" href="stylexw.css" />
    <style>
.cat-table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    margin-top: 20px;
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

.paid {
    color: green;
    font-weight: bold;
}

.unpaid {
    color: red;
    font-weight: bold;
}

.payBtn {
    padding: 5px 10px;
    font-size: 14px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    background-color: #391d53;
    color: rgb(197, 113, 215);
    transition: background-color 0.3s ease;
}

.payBtn:hover {
    background-color: #74b9ff;
}

/* Modal popup styles (เดิมไว้ตามนั้นได้) */
#qrCodeModal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}

#qrCodeModal.active {
    display: flex;
}

#qrCodeContent {
    background: #fff;
    padding: 20px 30px;
    border-radius: 8px;
    text-align: center;
    max-width: 320px;
    width: 90%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

#qrCodeContent button {
    margin: 10px 5px 0 5px;
    padding: 8px 15px;
    font-size: 16px;
    cursor: pointer;
}

#qrCodeContent input[type="file"] {
    margin-top: 10px;
}

#qrPreview img {
    max-width: 100%;
    height: auto;
    border: 1px solid #ccc;
    padding: 5px;
    margin-top: 10px;
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
    <h1>รายการชำระเงิน</h1>

    <table class="cat-table">
        <thead>
            <tr>
                <th>ลูกค้า</th>
                <th>แมว</th>
                <th>ประเภทห้อง</th>
                <th>วันที่เข้า</th>
                <th>วันที่ออก</th>
                <th>สถานะ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr data-id="<?= $row['id'] ?>">
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['cat_name']) ?></td>
                <td><?= htmlspecialchars($row['room_type']) ?></td>
                <td><?= htmlspecialchars($row['date_from']) ?></td>
                <td><?= htmlspecialchars($row['date_to']) ?></td>
                <td class="status <?= $row['paid'] ? 'paid' : 'unpaid' ?>">
                    <?= $row['paid'] ? 'ชำระแล้ว' : 'ยังไม่ชำระ' ?>
                </td>
                <td>
                    <?php if (!$row['paid']): ?>
                        <button class="payBtn" data-id="<?= $row['id'] ?>">ชำระเงิน</button>
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="qrCodeModal">
    <div id="qrCodeContent">
        <h3>กรุณาสแกน QR Code เพื่อชำระ</h3>
        <img src="imggood.png" alt="QR Code" style="max-width: 100%; height: auto; margin-bottom: 15px;" />

        <label>ส่งหลักฐานการชำระ:</label><br>
        <input type="file" id="qrInputFile" accept="image/*" />
        <div id="qrPreview"></div>

        <button id="paidBtn">ชำระแล้วเรียบร้อย</button>
        <button id="closeBtn">ปิด</button>
    </div>
</div>

<script>
    const qrCodeModal = document.getElementById('qrCodeModal');
    const qrInputFile = document.getElementById('qrInputFile');
    const qrPreview = document.getElementById('qrPreview');
    const paidBtn = document.getElementById('paidBtn');
    const closeBtn = document.getElementById('closeBtn');
    let currentBookingId = null;

    document.querySelectorAll('.payBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentBookingId = btn.getAttribute('data-id');
            qrInputFile.value = '';
            qrPreview.innerHTML = '';
            qrCodeModal.classList.add('active');
        });
    });

    closeBtn.addEventListener('click', () => {
        qrCodeModal.classList.remove('active');
        qrPreview.innerHTML = '';
        currentBookingId = null;
    });

    qrInputFile.addEventListener('change', () => {
        const file = qrInputFile.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                qrPreview.innerHTML = `<img src="${e.target.result}" alt="Proof" />`;
            }
            reader.readAsDataURL(file);
        } else {
            qrPreview.innerHTML = '';
        }
    });

    paidBtn.addEventListener('click', () => {
        if (!currentBookingId) return;

        if (qrPreview.innerHTML.trim() === '') {
            alert('กรุณาแนบหลักฐานการชำระเงิน');
            return;
        }

        fetch('payment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'booking_id=' + encodeURIComponent(currentBookingId)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('ชำระเงินเรียบร้อยแล้ว');
                const row = document.querySelector(`tr[data-id="${currentBookingId}"]`);
                row.querySelector('.status').textContent = 'ชำระแล้ว';
                row.querySelector('.status').classList.remove('unpaid');
                row.querySelector('.status').classList.add('paid');
                row.querySelector('.payBtn').style.display = 'none';
                qrCodeModal.classList.remove('active');
                currentBookingId = null;
            } else {
                alert('เกิดข้อผิดพลาด: ' + data.message);
            }
        })
        .catch(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อ'));
    });
</script>

</body>
</html>
