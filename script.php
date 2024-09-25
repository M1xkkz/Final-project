<?php
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// เชื่อมต่อฐานข้อมูล
include 'config.php';

// กำหนดรอบที่ต้องการแสดง (ค่าจาก GET parameter, ถ้าไม่มีให้เป็นรอบ 1)
$current_round = isset($_GET['round']) ? (int)$_GET['round'] : 1;

// จำนวนคอลัมน์ต่อรอบ (ในที่นี้คือ 7)
$columns_per_round = 7;

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT column1, column2, column3, column4, column5, column6, column7, column8, column9, column10, column11, column12, column13, column14 FROM your_table";
$result = $conn->query($query);

// จัดเก็บข้อมูลทั้งหมด
$all_data = [];
while ($row = $result->fetch_assoc()) {
    $all_data[] = $row;
}

// คำนวณตำแหน่งคอลัมน์เริ่มต้นและสิ้นสุดสำหรับรอบที่เลือก
$start_column = ($current_round - 1) * $columns_per_round + 1; // คำนวณคอลัมน์เริ่มต้นของรอบ
$end_column = $start_column + $columns_per_round - 1; // คำนวณคอลัมน์สุดท้ายของรอบ

// เก็บข้อมูลที่จะแสดงตามรอบ
$round_data = [];
foreach ($all_data as $row) {
    $round_data[] = array_slice($row, $start_column - 1, $columns_per_round); // ตัดข้อมูลที่ต้องการแสดงในรอบนี้
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data by Round</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>แสดงข้อมูลตามรอบ</h1>

        <!-- ปุ่มเปลี่ยนรอบ -->
        <div class="round-selector">
            <form method="GET" action="your_script.php">
                <label for="round">เลือกรอบ:</label>
                <select name="round" id="round">
                    <option value="1" <?php if ($current_round == 1) echo 'selected'; ?>>รอบ 1 (1-7)</option>
                    <option value="2" <?php if ($current_round == 2) echo 'selected'; ?>>รอบ 2 (8-14)</option>
                    <!-- เพิ่ม option สำหรับรอบอื่นๆ ตามต้องการ -->
                </select>
                <button type="submit">เปลี่ยนรอบ</button>
            </form>
        </div>

        <!-- แสดงข้อมูลรอบที่เลือก -->
        <h2>ข้อมูลรอบ <?php echo $current_round; ?></h2>
        <table border="1">
            <thead>
                <tr>
                    <?php for ($i = $start_column; $i <= $end_column; $i++): ?>
                        <th>คอลัมน์ <?php echo $i; ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($round_data as $data_row): ?>
                    <tr>
                        <?php foreach ($data_row as $data): ?>
                            <td><?php echo htmlspecialchars($data); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
