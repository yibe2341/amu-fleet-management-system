<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>View Schedule | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, schedule">
    <meta name="description" content="AMU Fleet Management System - View Vehicle Schedule">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32; /* You might want to adjust this if the new blue clashes */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
            --amu-new-bg: rgba(26, 35, 126, 0.85); /* New background color variable */
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
            background-color: var(--amu-new-bg); /* MODIFIED */
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
            background-color: var(--amu-primary); /* Consider if this green still fits with the new blue */
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.9); /* Submenu background */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-container {
            padding: 40px 5%;
            min-height: calc(100vh - 160px);
            display: flex;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        .schedule-container {
            background-color: var(--amu-new-bg); /* MODIFIED */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 1200px;
            overflow-x: auto;
        }

        .schedule-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
            text-align: center;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .schedule-table th {
            background-color: var(--amu-primary); /* Consider if this green still fits with the new blue */
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .schedule-table td {
            padding: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .schedule-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .schedule-table tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_panel {
            background-color: var(--amu-new-bg); /* MODIFIED */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            
            #templatemo_top_panel img {
                display: none;
            }
            
            #site_title {
                margin: 10px 0 15px;
            }
            
            #templatemo_menu ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            #templatemo_menu li {
                margin: 5px;
            }
            
            .schedule-container {
                padding: 20px;
            }
            
            .schedule-title {
                font-size: 1.5rem;
            }
            
            .schedule-table {
                font-size: 0.85rem;
            }
        }
    </style>
    <script>
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
                <li><a href="Admin.php">Home</a></li>
                <li><a href="#">Account</a>
                    <ul>
                        <li><a href="createaccount.php">Create account</a></li>
                        <li><a href="view.php">Update account</a></li>
                        <li><a href="view2.php">Delete account</a></li>
                    </ul>
                </li>
                <li><a href="aviewschedule.php" class="current">View Schedule</a></li>
                <li><a href="upload1.php">Report</a></li>
                <li><a href="changepssadmin.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-container">
        <div class="schedule-container">
            <h1 class="schedule-title">Vehicle Schedule</h1>
            
            <?php
            // Include the database configuration file
            include('config.php');

            // Check if the connection was successful
            if ($conn->connect_error) {
                echo "<div style='color: var(--amu-danger); text-align: center;'>Connection failed: " . $conn->connect_error . "</div>";
            } else {
                // Get results from the database
                $query = "SELECT * FROM schedule";
                $result = $conn->query($query);

                if (!$result) {
                    echo "<div style='color: var(--amu-danger); text-align: center;'>Error: " . $conn->error . "</div>";
                } else {
                    if ($result->num_rows > 0) {
                        echo "<div style='overflow-x: auto;'>";
                        echo "<table class='schedule-table'>";
                        echo "<thead><tr> 
                                <th>Driver ID</th> 
                                <th>Driver Name</th> 
                                <th>Phone No</th>
                                <th>Vehicle Type</th> 
                                <th>Plate No</th> 
                                <th>Start Place</th>
                                <th>Destination</th> 
                                <th>Service Time</th> 
                                <th>Date</th> 
                                <th>Departure</th> 
                                <th>Arrival</th>
                                <th>Purpose</th>
                              </tr></thead><tbody>";

                        // Loop through results
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo '<td>' . htmlspecialchars($row['Driver_ID']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Driver_Name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Driver_phone_no']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Vehicle_type']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Plate_no']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Place_of_start']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Place_of_arrive']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Service_time']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Date']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Outgoing_time']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['Enterance_time']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['For']) . '</td>';
                            echo "</tr>";
                        }

                        echo "</tbody></table></div>";
                    } else {
                        echo "<div style='text-align: center; color: var(--amu-text-secondary);'>No schedule data available.</div>";
                    }
                }
                
                // Close the database connection
                $conn->close();
            }
            ?>
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