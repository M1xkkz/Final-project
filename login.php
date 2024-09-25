<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // เชื่อมต่อกับฐานข้อมูล
    include 'config.php';

    // ตรวจสอบผู้ใช้
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // เปรียบเทียบรหัสผ่านแบบ plain text
        if ($pass === $user['password_hash']) {
            // รหัสผ่านถูกต้อง สร้าง session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php"); // เปลี่ยนไปหน้า dashboard หรือ index.php
            exit;
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ไม่มีชื่อผู้ใช้นี้";
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="style.css"> <!-- ถ้ามีไฟล์ CSS -->
</head>
<body>
    <div class="form-container">
        <h2>เข้าสู่ระบบ</h2>
        <!-- แสดงข้อความผิดพลาด -->
        <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form method="POST" action="login.php">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิกที่นี่</a></p>
    </div>
</body>
</html>
