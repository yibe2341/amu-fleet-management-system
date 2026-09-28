<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include("config.php"); // Ensure this path is correct and config.php doesn't output anything

// Get the plate number with trimming
$PlateNo = isset($_POST['PlateNo']) ? trim($_POST['PlateNo']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Vehicle Search Results | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, vehicle search">
    <meta name="description" content="AMU Fleet Management System - Vehicle Search Results">
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
            display: flex; /* For footer positioning */
            flex-direction: column; /* For footer positioning */
        }

        #templatemo_top_panel {
            background-color: var(--amu-new-bg); /* MODIFIED: Navy Blue */
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
            background-color: var(--amu-primary); /* Consider changing this accent color */
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

        .content-panel { /* This will now center the .content-right */
            display: flex;
            flex-direction: column; /* Stack children vertically */
            align-items: center; /* Center children horizontally */
            justify-content: flex-start; /* Align content to the top */
            flex: 1; /* Takes remaining vertical space */
            min-height: calc(100vh - 160px); /* Header and Footer height */
            padding: 40px 20px; /* Padding for the overall content area */
            width: 100%;
            backdrop-filter: blur(2px); /* Blur background behind content */
        }

        /* .content-left styles removed as the element is removed */

        .content-right { /* This holds the results-container */
            width: 100%; /* Takes full width of its parent (.content-panel) */
            max-width: 1200px; /* Max width for the results area */
            /* padding: 20px 40px; /* Removed as .results-container will have padding */
        }

        .results-container {
            background-color: var(--amu-new-bg); /* MODIFIED: Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* Optional: can be here or on parent */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            /* max-width: 1200px; /* Moved to .content-right for overall centering behavior */
            /* margin: 0 auto; /* Centering handled by parent .content-panel */
            overflow-x: auto; /* For table scrolling */
            width: 100%; /* Ensure it uses the width from .content-right */
        }

        .results-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px; /* Adjusted margin */
            color: #fff; /* MODIFIED: White for better contrast on navy blue */
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
         .results-title::after { /* Underline for title */
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); /* Consider changing accent color */
        }


        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            /* background-color: rgba(0, 0, 0, 0.3); /* Removed as parent has new bg */
        }

        .data-table th {
            background-color: var(--amu-primary); /* Consider changing accent color */
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            position: sticky; /* For sticky headers */
            top: 0;
            z-index: 1; /* Ensure headers are above table content */
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .data-table tr:hover td { /* Applied hover to td for better visibility */
             background-color: rgba(255, 255, 255, 0.08);
        }
        .data-table tr:nth-child(even) td { /* Subtle striping */
            background-color: rgba(255, 255, 255, 0.03);
        }
         .data-table tr:nth-child(even):hover td {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .no-results {
            text-align: center;
            padding: 30px;
            font-size: 1.2rem;
            color: var(--amu-text-secondary);
            background-color: rgba(0,0,0,0.1); /* Lighter than debug for distinction */
            border-radius: 8px;
            margin: 20px 0;
            border: 1px dashed rgba(255,255,255,0.2);
        }

        .debug-info {
            font-family: monospace;
            background-color: rgba(0, 0, 0, 0.2); /* Slightly different from no-results */
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            font-size: 0.9rem;
            color: var(--amu-text-secondary);
            word-break: break-all; /* Ensure long queries wrap */
        }

        .back-link {
            display: inline-flex; /* For icon alignment */
            align-items: center;
            gap: 8px; /* Space between icon and text */
            margin-top: 20px;
            padding: 12px 25px;
            background-color: var(--amu-primary); /* Consider changing accent color */
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 600;
        }

        .back-link:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .clock-display {
            text-align: center;
            font-size: 1.1rem; /* Adjusted size */
            color: var(--amu-text); /* Changed for better visibility on navy */
            margin-bottom: 25px; /* Increased margin */
            font-family: 'Arial', sans-serif;
            background-color: rgba(0, 0, 0, 0.2); /* Slightly transparent black */
            padding: 8px 15px; /* Adjusted padding */
            border-radius: 6px;
            display: inline-block; /* To fit content */
            /* width: auto; /* Not needed with inline-block */
            /* margin: 0 auto 20px; /* Centering handled by text-align on parent if needed */
        }

        #templatemo_footer_panel {
            background-color: var(--amu-new-bg); /* MODIFIED: Navy Blue */
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
            color: #4caf50; /* Consider changing link color */
            text-decoration: none;
            transition: color 0.3s;
        }
        #templatemo_footer_section a:hover {
            color: var(--amu-primary); /* Consider changing hover color */
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
                flex-wrap: wrap; /* Already set, but good for clarity */
                justify-content: center; /* Already set */
                flex-direction: column; /* Stack items on small screens */
                align-items: center;
            }
            
            #templatemo_menu li {
                margin: 5px 0; /* Vertical margin */
            }
            
            .content-panel {
                /* flex-direction: column; /* Already set */
                padding: 20px; /* Adjust padding for smaller screens */
            }
            
            .content-right {
                /* padding: 20px; /* Padding now on .results-container or .content-panel */
            }
            
            .results-container {
                padding: 20px;
            }
            .results-title {
                font-size: 1.6rem;
            }
            
            .data-table {
                font-size: 0.85rem;
            }
            .data-table th, .data-table td {
                padding: 10px 8px; /* Adjust padding for smaller table cells */
            }
        }
    </style>
    <script>
        // Client-side session check (optional, if check_login.php exists and works)
        /*
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') {
                    window.location.href = 'index.html';
                }
            }).catch(error => console.error('Error checking login:', error));
        */
            
        // Live clock display
        function showTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }); // Format time
            const dateString = now.toLocaleDateString([], { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }); // Format date
            
            const clockElements = document.querySelectorAll('.clock-display');
            clockElements.forEach(el => {
                el.textContent = `${dateString} | ${timeString}`;
            });
            
            setTimeout(showTime, 1000);
        }
        
        // Initialize clock when page loads
        window.onload = function() {
            showTime();
            // Any other onload functions
        };
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
                <li><a href="upload.php">Report</a></li>
                <li><a href="index.html">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-panel">
        <!-- Left content panel removed -->
        <div class="content-right">
            <div class="results-container">
                <h1 class="results-title">Vehicle Search Results</h1>
                
                <div style="text-align:center;"><div class="clock-display"></div></div> 
                
                <?php
                if (!empty($PlateNo)) {
                    // Prepare the SQL query with LIKE for more flexible matching
                    $query = "SELECT * FROM vehicles WHERE PlateNo LIKE ?";
                    $stmt = $conn->prepare($query);

                    if ($stmt) {
                        // Add wildcards for partial matching
                        $searchTerm = "%" . $PlateNo . "%"; // Corrected wildcard placement
                        $stmt->bind_param("s", $searchTerm);

                        // Execute the query
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                echo '<table class="data-table">';
                                echo '<thead><tr>
                                        <th>Vehicle ID</th>
                                        <th>Plate No</th>
                                        <th>Vehicle Type</th>
                                        <th>Model</th>
                                        <th>Chassis No</th>
                                        <th>Capacity</th>
                                        <th>Production Date</th>
                                        <th>Engine No</th>
                                        <th>Engine Power</th>
                                        <th>Owner</th>
                                      </tr></thead><tbody>';

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
                                    echo '</tr>';
                                }

                                echo '</tbody></table>';
                            } else {
                                echo '<div class="no-results">No vehicle found matching: "' . htmlspecialchars($PlateNo) . '"</div>';
                                // echo '<div class="debug-info">Query executed: ' . htmlspecialchars(str_replace('?', "'".$searchTerm."'", $query)) . '</div>'; // More accurate debug for prepared statements
                            }
                        } else {
                            echo '<div class="no-results">Error executing query: ' . htmlspecialchars($stmt->error) . '</div>';
                        }

                        $stmt->close();
                    } else {
                        echo '<div class="no-results">Error preparing statement: ' . htmlspecialchars($conn->error) . '</div>';
                    }
                } else {
                    echo '<div class="no-results">No search query submitted. Please use the search form.</div>';
                }
                ?>
                
                <div style="text-align: center; margin-top: 30px;">
                    <a href="searchvinfo.html" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to Search
                    </a>
                </div>
            </div>
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
if (isset($conn) && $conn instanceof mysqli) { // Check if $conn is a valid mysqli object before closing
    $conn->close();
}
?>