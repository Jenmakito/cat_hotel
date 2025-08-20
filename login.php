<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ - ระบบจองห้องพักแมว</title>
    <link rel="stylesheet" href="stylexw.css">

    <style>
        body {
            background-color: #391d53; /* สีฟ้าอ่อน */
        }
    </style>

</head>
<body>



<div class="login-container">
    <h2>เข้าสู่ระบบ</h2>
    <form action="index.php" method="POST">
        <label for="username">ชื่อผู้ใช้</label>
        <input type="text" id="username" name="username" required>

        <label for="password">รหัสผ่าน</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <div class="footer">
        สำหรับลูกค้าและพนักงาน
    </div>
</div>

</body>
</html>
