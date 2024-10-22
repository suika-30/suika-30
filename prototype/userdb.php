<?php
$host = 'localhost';
$db = 'farming_system'; 
$user = 'root';
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

// farmer data
$farmerId = 1; 
$farmerQuery = "SELECT * FROM Farmers WHERE farmer_id = $farmerId";
$farmerResult = $conn->query($farmerQuery)->fetch_assoc();

// crops
$cropsQuery = "SELECT * FROM Crops WHERE farmer_id = $farmerId";
$cropsResult = $conn->query($cropsQuery);

// soil quality
$soilQuery = "SELECT * FROM Soil_Quality WHERE farmer_id = $farmerId";
$soilResult = $conn->query($soilQuery)->fetch_assoc();

// fertilizer
$fertilizerQuery = "SELECT * FROM Fertilizer_Recommendations WHERE crop_id IN (SELECT crop_id FROM Crops WHERE farmer_id = $farmerId)";
$fertilizerResult = $conn->query($fertilizerQuery);

// weather
$weatherQuery = "SELECT * FROM Weather ORDER BY date ASC LIMIT 7"; // Last 7 days
$weatherResult = $conn->query($weatherQuery);

// action log
$logQuery = "SELECT * FROM Action_Log WHERE farmer_id = $farmerId ORDER BY date DESC";
$logResult = $conn->query($logQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="db.css">
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
        <h1>Welcome, <?php echo $farmerResult['name']; ?>!</h1>

        <!-- Farmer -->
        <div class="section">
            <h2>Your Information</h2>
            <p><strong>Location:</strong> <?php echo $farmerResult['location']; ?></p>
            <p><strong>Farm Size:</strong> <?php echo $farmerResult['farm_size']; ?> hectares</p>
        </div>

        <!-- Crops -->
        <div class="section">
            <h2>Your Crops</h2>
            <table>
                <tr>
                    <th>Crop Type</th>
                    <th>Growth Stage</th>
                    <th>Expected Harvest Time</th>
                </tr>
                <?php while($row = $cropsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['crop_type']; ?></td>
                    <td><?php echo $row['growth_stage']; ?></td>
                    <td><?php echo $row['expected_harvest_time']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Soil Quality -->
        <div class="section">
            <h2>Your Soil Quality</h2>
            <p><strong>Soil Type:</strong> <?php echo $soilResult['soil_type']; ?></p>
            <p><strong>Nutrient Level:</strong> <?php echo $soilResult['nutrient_level']; ?></p>
        </div>

        <!-- Fertilizer -->
        <div class="section">
            <h2>Fertilizer Recommendations</h2>
            <table>
                <tr>
                    <th>Recommended Fertilizer</th>
                    <th>Application Time</th>
                    <th>Status</th>
                </tr>
                <?php while($row = $fertilizerResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['recommended_fertilizer']; ?></td>
                    <td><?php echo $row['application_time']; ?></td>
                    <td><?php echo $row['fertilizer_status'] ? 'Applied' : 'Not Applied'; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Weather Conditions -->
        <div class="section" id="weather-conditions">
            <h2>Weather Conditions for the Next 7 Days</h2>
            <div class="timeline">
                <?php while($row = $weatherResult->fetch_assoc()): ?>
                    <div class="weather-item">
                        <div class="weather-icon">
                            <?php
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

       <!-- Action Log -->
       <div class="section" id="action-log">
            <h2>Action Log</h2>
            <table>
                <thead>
                    <tr>
                        <th>Action Taken</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $logResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['action_taken']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($row['date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>

<?php
$conn->close();
?>
