<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hearing Test Graphs</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2"></script>
    <!-- เพิ่ม plugin -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Hearing Test Results</h1>
        <h2>Ear Left</h2>
        <canvas id="earLeftChart"></canvas>
        <h2>Ear Right</h2>
        <canvas id="earRightChart"></canvas>
    </div>

    <script>
        // ดึงข้อมูลจาก data.php ด้วย fetch 
        async function fetchData() {
            const response = await fetch('data.php');
            const data = await response.json();
            return data;
        }
         
        fetchData().then(data => renderCharts(data));
        // สร้างกราฟ
        function renderCharts(data) {
            const earLeftData = {
                labels: data.ear_left.map(item => item.frequency), //แกน x
                datasets: [{
                    label: 'dB Level',
                    data: data.ear_left.map(item => item.dB_level), //แกน y
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            const earRightData = {
                labels: data.ear_right.map(item => item.frequency),
                datasets: [{
                    label: 'dB Level',
                    data: data.ear_right.map(item => item.dB_level),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            };

            const leftChartCtx = document.getElementById('earLeftChart').getContext('2d');
            const rightChartCtx = document.getElementById('earRightChart').getContext('2d');

            new Chart(leftChartCtx, {
                type: 'line',
                data: earLeftData,
                options: {
                    responsive: true,  // ทำให้ responsive
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
                data: earRightData,
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

       
    </script>
</body>
</html>
