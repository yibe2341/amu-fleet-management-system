<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Include the database configuration file
include('config.php');

// Get the date range from POST data
$dayFrom = isset($_POST['dayfrom']) ? $_POST['dayfrom'] : '';
$dayTo = isset($_POST['dayto']) ? $_POST['dayto'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Consumption Report | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32; /* Original AMU Green - kept for buttons/accent */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0; /* Light text for dark backgrounds */
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;

            /* Navy Blue Theme Variables */
            --navy-header-footer-bg: rgba(25, 25, 112, 0.9);  /* Midnight Blue / Dark Navy */
            --navy-container-bg: rgba(40, 50, 110, 0.85); /* Slightly Lighter Navy for container */
            --navy-table-header-bg: rgba(30, 40, 100, 0.8);  /* Navy for table header */
            --navy-menu-dropdown-bg: rgba(25, 25, 112, 0.95); /* Consistent with header/footer for dropdown */
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
            display: flex;
            flex-direction: column;
        }

        #templatemo_top_panel {
            background-color: var(--navy-header-footer-bg); /* NAVY BLUE */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
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
            color: #fff; /* White text for good contrast on navy */
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
            color: #fff; /* White text for good contrast on navy */
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary); /* AMU Green for hover/current - contrasts well */
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--navy-menu-dropdown-bg); /* NAVY BLUE for submenu */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .report-container {
            background-color: var(--navy-container-bg); /* NAVY BLUE */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 30px;
            max-width: 1000px;
            margin: 20px auto;
            flex: 1;
        }

        .report-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--amu-primary); /* AMU Green title - still visible and provides accent */
            /* color: #FFFFFF; */ /* Alternative: White title for stronger contrast on navy */
            text-align: center;
        }

        .date-range {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.1rem;
            color: var(--amu-text); /* Light text on navy container */
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            color: var(--amu-text); /* Light text on navy container */
        }

        .report-table th, .report-table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .report-table th {
            background-color: var(--navy-table-header-bg); /* NAVY BLUE */
            font-weight: 600;
            color: #FFFFFF; /* Ensure header text is white for contrast */
        }

        .report-table tr:hover {
            background-color: rgba(255, 255, 255, 0.08); /* Slightly brighter hover */
        }

        .no-results {
            text-align: center;
            padding: 20px;
            color: var(--amu-danger);
            font-size: 1.1rem;
        }

        .print-btn {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            background-color: var(--amu-primary); /* AMU Green button */
            color: white;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .print-btn:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #templatemo_footer_panel {
            background-color: var(--navy-header-footer-bg); /* NAVY BLUE */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            width: 100%;
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.8); /* Slightly brighter footer text */
            font-size: 0.85rem;
            margin: 0 auto;
            max-width: 1200px;
        }

        @media print {
            body {
                background: none;
                color: #000;
            }
            
            .report-container {
                background: none;
                box-shadow: none;
                border: none;
                padding: 0;
            }
            
            .print-btn {
                display: none;
            }
            
            #templatemo_top_panel, #templatemo_footer_panel {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .report-table {
                display: block;
                overflow-x: auto;
            }
            
            #templatemo_menu ul {
                flex-direction: column;
            }
            
            #templatemo_menu li {
                margin: 5px 0;
            }
        }
    </style>
    <script>
        function printReport() {
            if (confirm('Are you sure you want to print this report?')) {
                window.print();
            }
        }
        
        // Client-side session check
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data === 'false') {
                    window.location.href = 'index.html';
                }
            });
    </script>
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
                <li><a href="#">View</a>
                    <ul>
                        <li><a href="mviewschedule.php">View schedule</a></li>
                        <li><a href="exitrequest1.php">View exit request</a></li>
                        <li><a href="mrequest-view.php">View maintenance request</a></li>
                        <li><a href="mmessage.php">View message</a></li>
                        <li><a href="comment12.php">View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php">Fuel</a></li>
                <li><a href="report.php" class="current">Report</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="report-container">
        <h1 class="report-title">FLEET MANAGEMENT SYSTEM REPORT</h1>
        <h2 class="report-title">FUEL CONSUMPTION REPORT</h2>
        
        <div class="date-range">
            From: <strong><?php echo htmlspecialchars($dayFrom); ?></strong> 
            To: <strong><?php echo htmlspecialchars($dayTo); ?></strong>
        </div>
        
        <?php
        // Query the database for fuel consumption data
        $query = "SELECT * FROM fuel WHERE date BETWEEN ? AND ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("ss", $dayFrom, $dayTo);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 0) {
                echo '<div class="no-results">No fuel consumption records found for the selected date range.</div>';
            } else {
                echo '<table class="report-table">';
                echo '<thead>
                        <tr>
                            <th>Vehicle ID</th>
                            <th>Distance (km)</th>
                            <th>Total Cost (ETB)</th>
                            <th>Fuel Consumption (L)</th>
                            <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>';
                
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['car_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['total_km']) . '</td>';
                    echo '<td>' . number_format($row['total_price'], 2) . '</td>';
                    echo '<td>' . number_format($row['fuel_consumption'], 2) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
            }
            
            $stmt->close();
        } else {
            echo '<div class="no-results">Error preparing database query: ' . htmlspecialchars($conn->error) . '</div>';
        }
        ?>
        
        <a href="javascript:void(0)" class="print-btn" onclick="printReport()">
            <i class="fas fa-print"></i> Print Report
        </a>
    </div>

    <!-- Footer Section - Now properly aligned at bottom -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>