<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Include the database configuration file
include('config.php'); // Ensure this doesn't output anything

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Vehicle Management | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, vehicle management">
    <meta name="description" content="AMU Fleet Management System - Vehicle Management">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32; /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
            --amu-new-bg: rgba(26, 35, 126, 0.85); /* Navy Blue */
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
            display: flex; /* Added to help footer stick to bottom if content is short */
            flex-direction: column; /* Added to help footer stick to bottom */
        }

        #templatemo_top_panel {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
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
            flex-grow: 1; /* Allows title to take available space */
            margin: 0 15px; /* Adds some spacing around the title */
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap; /* Allow menu items to wrap */
            justify-content: center; /* Center items if they wrap */
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 5px; /* Reduced margin for tighter fit if wrapping */
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 10px; /* Adjusted padding */
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem; /* Slightly smaller font for more items */
            white-space: nowrap;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary); /* Original color, consider changing */
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
            z-index: 1001; /* Ensure dropdown is above */
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-container {
            padding: 40px 5%;
            min-height: calc(100vh - 160px); /* Header and Footer height */
            backdrop-filter: blur(2px); /* Apply blur to area behind vehicle container */
            flex: 1; /* Allows this container to grow and push footer down */
            display: flex; /* To center child if needed, or for structure */
            justify-content: center; /* Center .vehicle-container if it's narrower */
            align-items: flex-start; /* Align .vehicle-container to the top */
        }

        .vehicle-container {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* Blur can be here or on parent */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            width: 100%; /* Take full width of its parent column (content-container) */
            max-width: 1200px; /* Max width for the table container */
            overflow-x: auto; /* For table responsiveness */
        }

        .vehicle-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px; /* Increased margin */
            color: #fff; /* MODIFIED: White for better contrast on navy blue */
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        .vehicle-title::after { /* Underline for title */
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); /* Original color, consider changing */
        }


        .vehicle-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            /* background-color: rgba(0,0,0,0.1); /* Lighter background for table inside navy blue */
        }

        .vehicle-table th {
            background-color: var(--amu-primary); /* Original color, consider changing */
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            position: sticky; /* For sticky headers */
            top: 0; /* Stick to top of scrollable container (.vehicle-container) */
            z-index: 1; /* Ensure headers are above table content */
        }

        .vehicle-table td {
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .vehicle-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.03); /* Subtle striping */
        }

        .vehicle-table tr:hover {
            background-color: rgba(255, 255, 255, 0.08); /* Subtle hover */
        }

        .action-icon {
            /* width: 24px; height: 24px; /* Using font-size for Font Awesome */
            font-size: 1.1em; /* Control icon size */
            transition: transform 0.3s, color 0.3s;
            /* color is set inline in HTML, can be moved here if preferred */
        }

        .action-icon:hover {
            transform: scale(1.2);
        }

        .action-link {
            display: inline-block;
            margin: 0 5px;
            text-align: center; /* Center icons within their cells */
        }
        .vehicle-table td.action-link { /* Specific styling for action cells */
            text-align: center;
        }


        .no-vehicles {
            text-align: center;
            padding: 20px;
            color: var(--amu-text-secondary);
            font-style: italic;
            background-color: rgba(0,0,0,0.1); /* Light background for the message box */
            border-radius: 6px;
            border: 1px dashed rgba(255,255,255,0.2);
        }

        #templatemo_footer_panel {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            /* margin-top: auto; /* Ensured by flex on body */
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
         #templatemo_footer_section a {
            color: #4caf50; /* Original color, consider changing */
            text-decoration: none;
            transition: color 0.3s;
        }
        #templatemo_footer_section a:hover {
            color: var(--amu-primary); /* Original color, consider changing */
            text-decoration: underline;
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
                font-size: 1.2rem;
            }
            
            #templatemo_menu ul {
                flex-wrap: wrap;
                justify-content: center;
                flex-direction: column; /* Stack menu on small screens */
                align-items: center;
            }
            
            #templatemo_menu li {
                margin: 5px 0; /* Vertical margin */
            }
            
            .content-container {
                 padding: 20px; /* Adjust padding for smaller screens */
            }
            .vehicle-container {
                padding: 20px;
            }
            
            .vehicle-table {
                font-size: 0.85rem;
                display: block; /* Allow horizontal scroll on table itself */
                /* overflow-x: auto; /* Handled by parent .vehicle-container */
            }
            .vehicle-table th, .vehicle-table td {
                white-space: nowrap; /* Prevent text wrapping in cells */
            }
        }
    </style>
    <script>
        // Client-side session check (optional, if check_login.php exists)
        /*
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') {
                    window.location.href = 'index.html';
                }
            }).catch(error => console.error('Error checking login status:', error));
        */
            
        function confirmDelete() {
            return confirm('Are you sure you want to delete this vehicle?');
        }
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
                <li><a href="#" class="current">Vehicle</a>
                    <ul>
                        <li><a href="vehicle-register.html">Register vehicle</a></li>
                        <li><a href="view1.php">Update vehicle</a></li> <!-- Assuming this is the current page -->
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
                <li><a href="upload.php">Report</a></li>
                <li><a href="changepssmanager.php">Change Password</a></li>
                <li><a href="index.html">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-container">
        <div class="vehicle-container">
            <h1 class="vehicle-title">Registered Vehicles of AMU</h1>
            
            <?php
            // Get results from the database
            $query = "SELECT Vehicle_id, PlateNo, VehicleType, Model, ChessisNo, Capacity, ProductionDate, EngineNo, EnginePower, Owner FROM vehicles"; // Explicitly list columns
            $result = $conn->query($query);

            if (!$result) {
                echo "<div class='no-vehicles'>Error loading vehicle data: " . htmlspecialchars($conn->error) . "</div>";
            } elseif ($result->num_rows == 0) {
                echo "<div class='no-vehicles'>No vehicles currently registered</div>";
            } else {
                echo "<table class='vehicle-table'>";
                echo "<thead><tr>
                        <th>Vehicle ID</th>
                        <th>Plate No</th>
                        <th>Type</th>
                        <th>Model</th>
                        <th>Chassis No</th>
                        <th>Capacity</th>
                        <th>Prod. Date</th>
                        <th>Engine No</th>
                        <th>Eng. Power</th>
                        <th>Owner</th>
                        <th colspan='2' style='text-align:center;'>Actions</th>
                      </tr></thead><tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Vehicle_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['PlateNo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['VehicleType']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Model']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ChessisNo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Capacity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ProductionDate']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['EngineNo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['EnginePower']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Owner']) . "</td>";
                    echo "<td class='action-link'>
                            <a href='editvehicle.php?PlateNo=" . urlencode($row['PlateNo']) . "' title='Edit Vehicle'>
                                <i class='fas fa-edit action-icon' style='color: var(--amu-primary);'></i>
                            </a>
                          </td>";
                    echo "<td class='action-link'>
                            <a href='delvehicle.php?PlateNo=" . urlencode($row['PlateNo']) . "&view=delete' onclick='return confirmDelete()' title='Delete Vehicle'>
                                <i class='fas fa-trash-alt action-icon' style='color: var(--amu-danger);'></i>
                            </a>
                          </td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
            }

            // Close the database connection
            if ($conn) { // Check if connection is still valid before closing
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