<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = $_POST['username'];
    $pass = $_POST['password']; 
    $role = 'user'; 


    include 'config.php'; 


    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username นี้ถูกใช้งานแล้ว";
    } else {

        $stmt = $conn->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $user, $pass, $role); 
        if ($stmt->execute()) {
            header("Location: login.php"); 
            exit;
        } else {
            $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="style.css"> <!-- ถ้ามีไฟล์ CSS -->
</head>
<body>
    <div class="form-container">
        <h2>สมัครสมาชิก</h2>
        <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form method="POST" action="register.php">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">สมัครสมาชิก</button>
        </form>
        <p>มีบัญชีอยู่แล้ว? <a href="login.php">ล็อกอินที่นี่</a></p>
    </div>
</body>
</html>
