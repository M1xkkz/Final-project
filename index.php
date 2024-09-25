<?php
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // ถ้ายังไม่ได้ล็อกอินให้ไปที่หน้าล็อกอิน
    exit;
}

// เชื่อมต่อฐานข้อมูล
include 'config.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$username = $_SESSION['username']; // สมมติว่า session เก็บ username ไว้จากตอนล็อกอิน

// ตรวจสอบบทบาทผู้ใช้ ถ้าเป็น admin สามารถดูข้อมูลของผู้ใช้ทุกคนได้
if ($role === 'admin' && isset($_GET['view_user_id'])) {
    $user_id = $_GET['view_user_id']; // admin สามารถดูผลทดสอบของ user คนอื่นได้
}

// รับค่าจากฟอร์มสำหรับวันที่และรอบที่ต้องการ
$selected_date = isset($_GET['date']) ? $_GET['date'] : null; // กำหนดวันที่ที่เลือก
$round = isset($_GET['round']) ? intval($_GET['round']) : 1; // กำหนดค่าเริ่มต้นที่รอบ 1 ถ้าไม่ได้ส่งค่าเข้ามา

// ตรวจสอบว่าผู้ใช้ได้เลือกวันที่หรือไม่
if ($selected_date) {
    // คำนวณการดึงข้อมูลตามรอบที่เลือกในวันที่เลือก
    $offset = ($round - 1) * 7;
    $limit = 7;

    // ดึงข้อมูลผลทดสอบหูซ้ายและขวาของผู้ใช้ในวันที่เลือก
    $earLeftResults = $conn->query("SELECT frequency, dB_level FROM ear_left WHERE user_id = $user_id AND DATE(test_date) = '$selected_date' LIMIT $limit OFFSET $offset");
    $earRightResults = $conn->query("SELECT frequency, dB_level FROM ear_right WHERE user_id = $user_id AND DATE(test_date) = '$selected_date' LIMIT $limit OFFSET $offset");

    // เก็บข้อมูลในรูปแบบ array เพื่อนำไปแสดงในกราฟ
    $earLeftData = [];
    while ($row = $earLeftResults->fetch_assoc()) {
        $earLeftData[] = $row;
    }

    $earRightData = [];
    while ($row = $earRightResults->fetch_assoc()) {
        $earRightData[] = $row;
    }
}

// ปิดการเชื่อมต่อ
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hearing Test Graphs</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- ส่วนแสดงชื่อผู้ใช้ที่ล็อกอินและปุ่มล็อกเอาต์ -->
        <div class="user-info">
            <p>สวัสดี, <?php echo htmlspecialchars($username); ?> (<?php echo $role; ?>)</p>
            <form action="logout.php" method="POST">
                <button type="submit">ล็อกเอาต์</button>
            </form>
        </div>

        <h1>Hearing Test Results</h1>

        <!-- แบบฟอร์มสำหรับเลือกวันที่และรอบ -->
        <form method="GET">
            <label for="date">เลือกวันที่:</label>
            <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($selected_date); ?>" required>
            
            <label for="round">เลือกรอบ:</label>
            <select name="round" id="round" onchange="this.form.submit()">
                <?php for ($i = 1; $i <= 5; $i++): // สมมติว่ามีทั้งหมด 5 รอบ ?>
                    <option value="<?php echo $i; ?>" <?php if ($round == $i) echo 'selected'; ?>>รอบที่ <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit">ยืนยัน</button>
        </form>

        <?php if ($selected_date): ?>
            <h2>Ear Left (วันที่ <?php echo htmlspecialchars($selected_date); ?>, รอบที่ <?php echo $round; ?>)</h2>
            <canvas id="earLeftChart"></canvas>
            <h2>Ear Right (วันที่ <?php echo htmlspecialchars($selected_date); ?>, รอบที่ <?php echo $round; ?>)</h2>
            <canvas id="earRightChart"></canvas>
        <?php endif; ?>
    </div>

    <script>
        // ข้อมูลจาก PHP แปลงเป็น JSON
        const earLeftData = <?php echo json_encode($earLeftData ?? []); ?>;
        const earRightData = <?php echo json_encode($earRightData ?? []); ?>;

        // สร้างกราฟ
        function renderCharts() {
            const leftChartCtx = document.getElementById('earLeftChart').getContext('2d');
            const rightChartCtx = document.getElementById('earRightChart').getContext('2d');

            // ข้อมูลหูซ้าย
            const earLeftChartData = {
                labels: earLeftData.map(item => item.frequency),
                datasets: [{
                    label: 'dB Level',
                    data: earLeftData.map(item => item.dB_level),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            // ข้อมูลหูขวา
            const earRightChartData = {
                labels: earRightData.map(item => item.frequency),
                datasets: [{
                    label: 'dB Level',
                    data: earRightData.map(item => item.dB_level),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            };

            new Chart(leftChartCtx, {
                type: 'line',
                data: earLeftChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { title: { display: true, text: 'Frequency (Hz)' }},
                        y: { title: { display: true, text: 'dB Level' }, min: -10, max: 120 }
                    },
                    plugins: {
                        annotation: {
                            annotations: {
                                line1: {
                                    type: 'line',
                                    yMin: 26,
                                    yMax: 26,
                                    borderColor: 'red',
                                    borderWidth: 2,
                                    label: {
                                        content: 'ผิดปกติ',
                                        enabled: true,
                                        position: 'top'
                                    }
                                }
                            }
                        }
                    }
                }
            });

            new Chart(rightChartCtx, {
                type: 'line',
                data: earRightChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { title: { display: true, text: 'Frequency (Hz)' }},
                        y: { title: { display: true, text: 'dB Level' }, min: -10, max: 120 }
                    },
                    plugins: {
                        annotation: {
                            annotations: {
                                line1: {
                                    type: 'line',
                                    yMin: 26,
                                    yMax: 26,
                                    borderColor: 'red',
                                    borderWidth: 2,
                                    label: {
                                        content: 'ผิดปกติ',
                                        enabled: true,
                                        position: 'top'
                                    }
                                }
                            }
                        }
                    }
                }
            });
        }

        renderCharts();
    </script>
</body>
</html>
