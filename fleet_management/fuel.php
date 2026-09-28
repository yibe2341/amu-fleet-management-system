<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration file
include('config.php');

// Initialize variables
$showFuelForm = false;
$calculationResult = null;
$lid = '';

// Check if vehicle was selected for fuel calculation
if (isset($_GET['fid'])) {
    $lid = $_GET['fid'];
    $showFuelForm = true;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["Submit"])) {
    $date = date('d-m-Y');
    $Car_id = $_POST["Car_id"];
    $km = $_POST["Traveledkm"];
    $oneLettrPrice = $_POST["Price"];
    $leterKM = $_POST["Given_fuel_byL"];

    $feulConsamtion = $km * $leterKM;
    $totalPrice = $feulConsamtion * $oneLettrPrice;

    // Prepare the INSERT query using a prepared statement
    $query = "INSERT INTO fuel (car_id, total_km, total_price, fuel_consumption, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssdds", $Car_id, $km, $totalPrice, $feulConsamtion, $date);
        
        if ($stmt->execute()) {
            $calculationResult = [
                'Car_id' => $Car_id,
                'km' => $km,
                'feulConsamtion' => $feulConsamtion,
                'totalPrice' => $totalPrice,
                'date' => $date
            ];
            echo "<script>alert('Fuel calculated successfully!');</script>";
        } else {
            echo "<script>alert('Error calculating fuel: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing query: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-dark: #000080; /* Changed from #121212 to navy blue */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            color: var(--amu-text);
            min-height: 100vh;
            line-height: 1.6;
        }

        #templatemo_top_panel {
            background-color: rgba(0, 0, 128, 0.85); /* Changed from rgba(0,0,0,0.85) */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4); /* Shadow kept black for depth */
            position: relative;
            z-index: 1000;
        }

        #templatemo_top_panel img {
            height: 50px;
            width: auto;
            object-fit: contain;
        }

        #site_title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 8px;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 128, 0.9); /* Changed from rgba(0,0,0,0.9) */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-panel {
            display: flex;
            min-height: calc(100vh - 160px);
            padding: 20px 0;
        }

        .content-left {
            width: 250px;
            background-color: rgba(0, 0, 128, 0.7); /* Changed from rgba(0,0,0,0.7) */
            padding: 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-section {
            background-color: rgba(0, 0, 128, 0.5); /* Changed from rgba(0,0,0,0.5) */
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }

        .login-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-primary);
        }

        .login-section img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            border: 3px solid var(--amu-primary);
        }

        .content-right {
            flex: 1;
            padding: 20px 40px;
            overflow-y: auto;
        }

        .data-container {
            background-color: rgba(0, 0, 128, 0.75); /* Changed from rgba(0,0,0,0.75) */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); /* Shadow kept black for depth */
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--amu-primary);
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            color: var(--amu-text);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: rgba(46, 125, 50, 0.5); /* This is a transparent green, kept as is */
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05); /* Light hover, kept as is */
        }

        .action-icon {
            width: 20px;
            height: 20px;
            transition: transform 0.2s;
        }

        .action-icon:hover {
            transform: scale(1.2);
        }

        .fuel-form {
            background-color: rgba(0, 0, 128, 0.7); /* Changed from rgba(0,0,0,0.7) */
            border-radius: 10px;
            padding: 25px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1); /* Input background light, kept as is */
            color: var(--amu-text);
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: var(--amu-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
        }

        .btn-reset {
            background-color: var(--amu-danger);
            color: white;
            margin-left: 10px;
        }

        .btn-reset:hover {
            background-color: #c82333;
        }

        .result-table {
            margin-top: 30px;
            width: 100%;
        }

        #templatemo_footer_panel {
            background-color: rgba(0, 0, 128, 0.85); /* Changed from rgba(0,0,0,0.85) */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .content-panel {
                flex-direction: column;
            }
            
            .content-left {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .content-right {
                padding: 15px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="manager.php">Home</a></li>
                <li><a href="#">Vehicle</a>
                    <ul>
                        <li><a href="vehicle-register.html">Register vehicle</a></li>
                        <li><a href="view1.php">Update vehicle</a></li>
                        <li><a href="searchvinfo.html">Search vehicles</a></li>
                    </ul>
                </li>
                <li><a href="#" >View</a>
                    <ul>
                        <li><a href="mviewschedule.php">View schedule</a></li>
                        <li><a href="exitrequest1.php">View exit request</a></li>
                        <li><a href="mrequest-view.php">View maintenance request</a></li>
                        <li><a href="mmessage.php">View message</a></li>
                        <li><a href="comment12.php">View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php" class="current" >Fuel</a></li>
                <li><a href="upload.php">Report</a></li>
                <li><a href="changepssmanager.php">Change Password</a></li>
                <li><a href="index.html">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-panel">
        <div class="content-left">
            <div class="login-section">
                <div class="login-section-title">MANAGER PORTAL</div>
                <div style="font-size: 100px; color: #2e7d32; margin: 20px 0;">
                    <i class="fas fa-user-tie"></i>
                </div>
                
            </div>
        </div>
        
        <div class="content-right">
            <div class="data-container">
                <h1 class="page-title">Registered Vehicles</h1>
                
                <?php
                // Fetch vehicles from the database
                $query = "SELECT * FROM vehicles";
                $result = $conn->query($query);

                if (!$result) {
                    echo '<div class="error-message">Error: ' . $conn->error . '</div>';
                } elseif ($result->num_rows > 0) {
                    echo '<p>Available vehicles to be scheduled: ' . $result->num_rows . '</p>';
                    echo '<div style="overflow-x:auto;">';
                    echo '<table>';
                    echo '<tr>
                            <th>Vehicle ID</th>
                            <th>Plate No</th>
                            <th>Type</th>
                            <th>Model</th>
                            <th>Chassis No</th>
                            <th>Capacity</th>
                            <th>Production Date</th>
                            <th>Engine No</th>
                            <th>Engine Power</th>
                            <th>Owner</th>
                            <th>Action</th>
                          </tr>';

                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['Vehicle_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['PlateNo']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['VehicleType']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Model']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['ChessisNo']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Capacity']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['ProductionDate']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['EngineNo']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['EnginePower']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Owner']) . '</td>';
                        echo '<td>
                                <a href="?fid=' . urlencode($row['PlateNo']) . '" onclick="return confirm(\'Calculate fuel consumption for this vehicle?\');">
                                    <i class="fas fa-gas-pump action-icon" title="Calculate Fuel"></i>
                                </a>
                              </td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<div class="no-data">No registered vehicles found</div>';
                }
                ?>
            </div>

            <?php if ($showFuelForm): ?>
            <div class="data-container fuel-form">
                <h2 class="page-title">Fuel Calculation</h2>
                <form name="fuelForm" method="post">
                    <div class="form-group">
                        <label for="Car_id">Vehicle Plate No</label>
                        <input type="text" name="Car_id" maxlength="80" value="<?php echo htmlspecialchars($lid); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="Traveledkm">Total Traveled km</label>
                        <input type="number" name="Traveledkm" maxlength="80" required step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="Price">Price per liter (ETB)</label>
                        <input type="number" name="Price" maxlength="80" required step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="Given_fuel_byL">Fuel Consumption (L/km)</label>
                        <input type="number" name="Given_fuel_byL" maxlength="80" required step="0.0001">
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="Submit" class="btn btn-primary">Calculate</button>
                        <button type="reset" class="btn btn-reset">Clear</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <?php if ($calculationResult): ?>
            <div class="data-container">
                <h2 class="page-title">Fuel Calculation Results</h2>
                <table class="result-table">
                    <tr>
                        <th>Vehicle Plate No</th>
                        <th>Traveled km</th>
                        <th>Fuel Consumed (L)</th>
                        <th>Total Cost (ETB)</th>
                        <th>Date</th>
                    </tr>
                    <tr>
                        <td><?php echo htmlspecialchars($calculationResult['Car_id']); ?></td>
                        <td><?php echo htmlspecialchars($calculationResult['km']); ?></td>
                        <td><?php echo number_format($calculationResult['feulConsamtion'], 2); ?></td>
                        <td><?php echo number_format($calculationResult['totalPrice'], 2); ?></td>
                        <td><?php echo htmlspecialchars($calculationResult['date']); ?></td>
                    </tr>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>
<?php
// Close the database connection if it's still open
if (isset($conn) && $conn instanceof mysqli) { // Ensure $conn is a mysqli object
    $conn->close();
}
?>