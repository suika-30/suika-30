<?php
$host = 'localhost';
$db = 'farming_system'; 
$user = 'root';
$pass = ''; 
$conn = new mysqli($host, $user, $pass, $db);

// farmer, crop, and soil data
$farmerQuery = "SELECT * FROM Farmers";
$farmerResult = $conn->query($farmerQuery);

// soil quality data (pie chart)
$soilQuery = "SELECT soil_type, COUNT(*) as count FROM Soil_Quality GROUP BY soil_type";
$soilResult = $conn->query($soilQuery);

// soil type data for pie chart
$soilTypes = [];
$soilCounts = [];
while ($row = $soilResult->fetch_assoc()) {
    $soilTypes[] = $row['soil_type'];
    $soilCounts[] = $row['count'];
}

// fertilizer data (for pie chart)
$fertilizerQuery = "SELECT fertilizer_status, COUNT(*) as count FROM Fertilizer_Recommendations GROUP BY fertilizer_status";
$fertilizerResult = $conn->query($fertilizerQuery);

// fertilizer application data for pie chart
$fertilizerStatus = [];
$fertilizerCounts = [];
while ($row = $fertilizerResult->fetch_assoc()) {
    $fertilizerStatus[] = $row['fertilizer_status'] == 1 ? 'Applied' : 'Not Applied';
    $fertilizerCounts[] = $row['count'];
}

// (temporary) weather conditions for the next 7 days
$weatherQuery = "SELECT * FROM Weather ORDER BY date ASC LIMIT 7"; 
$weatherResult = $conn->query($weatherQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <style>
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        .section {
            margin: 20px 0;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            background-color: #f9f9f9;
        }
        .card-header {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .timeline {
            display: flex;
            justify-content: space-between;
            background-color: #f0f0f0;
            border-radius: 5px;
            padding: 10px;
            overflow-x: auto;
        }
        .weather-item {
            text-align: center;
            flex: 0 0 auto;
            width: 120px;
            margin-right: 10px;
        }
        .weather-item:last-child {
            margin-right: 0;
        }
        .weather-icon {
            font-size: 24px;
        }
        .weather-date {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>

        <!-- farmer and crop -->
        <div class="section" id="farmer-details">
            <h2>Farmer and Crop Details</h2>
            <?php while($row = $farmerResult->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-header">Farmer: <?php echo $row['name']; ?></div>
                    <p>Location: <?php echo $row['location']; ?></p>
                    <p>Farm Size: <?php echo $row['farm_size']; ?> hectares</p>

                    <!-- fetch and display crops for current farmer -->
                    <h4>Crops:</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Crop Type</th>
                                <th>Expected Harvest Time</th>
                                <th>Growth Stage</th>
                                <th>Planting Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cropsQuery = "SELECT * FROM Crops WHERE farmer_id = " . $row['farmer_id'];
                            $cropsResult = $conn->query($cropsQuery);
                            while ($cropRow = $cropsResult->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $cropRow['crop_type']; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($cropRow['expected_harvest_time'])); ?></td>
                                    <td><?php echo $cropRow['growth_stage']; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($cropRow['planting_date'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Soil Quality Pie Chart -->
        <div class="section" id="soil-quality-chart">
            <h2>Soil Quality Distribution</h2>
            <canvas id="soilQualityChart" width="200" height="200"></canvas>
        </div>

        <!-- Fertilizer Application Pie Chart -->
        <div class="section" id="fertilizer-chart">
            <h2>Fertilizer Application Status</h2>
            <canvas id="fertilizerChart" width="200" height="200"></canvas>
        </div>

        <!-- Weather Timeline -->
        <div class="section" id="weather-conditions">
            <h2>Weather Conditions for the Next 7 Days</h2>
            <div class="timeline">
                <?php while($row = $weatherResult->fetch_assoc()): ?>
                    <div class="weather-item">
                        <div class="weather-icon">
                            <?php
                            // weather icons for temporary use
                            if (strpos($row['weather_forecast'], 'Sunny') !== false) {
                                echo '☀️';
                            } elseif (strpos($row['weather_forecast'], 'Rainy') !== false) {
                                echo '🌧️';
                            } elseif (strpos($row['weather_forecast'], 'Cloudy') !== false) {
                                echo '☁️';
                            } elseif (strpos($row['weather_forecast'], 'Stormy') !== false) {
                                echo '🌩️';
                            } else {
                                echo '🌈';
                            }
                            ?>
                        </div>
                        <div class="weather-date"><?php echo date('D', strtotime($row['date'])); ?></div>
                        <div><?php echo $row['weather_forecast']; ?></div>
                        <div><?php echo $row['suitable_for_planting'] ? 'Yes' : 'No'; ?></div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>

    <script>
        // Soil Quality Pie Chart
        const soilCtx = document.getElementById('soilQualityChart').getContext('2d');
        const soilQualityChart = new Chart(soilCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($soilTypes); ?>, // soil types
                datasets: [{
                    label: 'Soil Quality Distribution',
                    data: <?php echo json_encode($soilCounts); ?>, // soil counts
                    backgroundColor: [
                        '#ff6384',
                        '#36a2eb',
                        '#cc65fe',
                        '#ffce56',
                        '#4bc0c0'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Fertilizer Application Pie Chart
        const fertilizerCtx = document.getElementById('fertilizerChart').getContext('2d');
        const fertilizerChart = new Chart(fertilizerCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($fertilizerStatus); ?>,
                datasets: [{
                    label: 'Fertilizer Application Status',
                    data: <?php echo json_encode($fertilizerCounts); ?>,
                    backgroundColor: [
                        '#36a2eb',
                        '#ff6384'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>
