<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
include('config.php'); // Ensure this doesn't output anything
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Vehicle Schedule | AMU Fleet System</title>
    <meta name="keywords" content="AMU University, Fleet Management, Vehicle Schedule" />
    <meta name="description" content="AMU University Fleet Management System - Vehicle Schedule" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
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

        /* ===== Header Styles ===== */
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

        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 40px 5%;
            min-height: calc(100vh - 160px); /* Header and Footer height */
            display: flex; /* To center content_section */
            justify-content: center; /* Center content_section */
            align-items: flex-start; /* Align content_section to top */
            backdrop-filter: blur(2px); /* Blur background behind content */
            flex: 1; /* Allow panel to grow */
        }

        #templatemo_content_section {
            display: flex; /* This will now primarily manage .content-right */
            width: 100%;
            max-width: 1200px; /* Max width for the content area */
            /* margin: 0 auto; /* Centering handled by parent */
            /* gap: 30px; /* No longer needed as left column is gone */
        }

        /* ===== Left Column Styles Removed ===== */
        /* #templatemo_content_left, #login_section, etc. styles are removed */

        /* ===== Right Column Styles (Now the main content block) ===== */
        #templatemo_content_right {
            flex-grow: 1; /* Take up all available space in content_section */
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* Optional: can be here or on parent */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            overflow-x: auto; /* For table responsiveness */
            width: 100%; /* Ensure it uses full width of its container */
        }

        .schedule-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff; /* MODIFIED: White for better contrast */
            position: relative;
            padding-bottom: 10px;
            text-align: center;
        }

        .schedule-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); /* Original color, consider changing */
        }

        .clock-display {
            text-align: center;
            font-size: 1.1rem; /* Adjusted size */
            color: var(--amu-text); /* Changed for better visibility on navy */
            margin: 0 auto 25px auto; /* Center and add bottom margin */
            font-family: 'Arial', sans-serif;
            background-color: rgba(0, 0, 0, 0.2); /* Slightly transparent black */
            padding: 8px 15px; /* Adjusted padding */
            border-radius: 6px;
            display: table; /* To make auto margin work for centering block */
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            /* background-color: rgba(0, 0, 0, 0.3); /* Removed, parent has new bg */
        }

        .data-table th {
            background-color: var(--amu-primary); /* Original color, consider changing */
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

        .data-table tr:hover td { /* Applied hover to td */
            background-color: rgba(255, 255, 255, 0.08); /* Subtle hover */
        }
        .data-table tr:nth-child(even) td { /* Subtle striping */
            background-color: rgba(255, 255, 255, 0.03);
        }
        .data-table tr:nth-child(even):hover td {
            background-color: rgba(255, 255, 255, 0.1);
        }


        .no-data {
            text-align: center;
            padding: 30px;
            font-size: 1.2rem;
            color: var(--amu-text-secondary);
            background-color: rgba(0, 0, 0, 0.1); /* Light background for message */
            border-radius: 8px;
            margin: 20px 0;
            border: 1px dashed rgba(255,255,255,0.2);
        }

        /* ===== Footer Styles ===== */
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

        /* ===== Responsive Adjustments ===== */
        @media (max-width: 992px) {
            /* #templatemo_content_section { flex-direction: column; } /* No longer needed */
            /* #templatemo_content_left { width: 100%; } /* Removed */
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

            #templatemo_content_panel {
                padding: 20px; /* Adjust padding */
            }
            #templatemo_content_right {
                padding: 20px; /* Adjust padding for smaller screens */
            }
            
            .schedule-title {
                font-size: 1.6rem;
            }
            
            .data-table {
                font-size: 0.85rem;
                /* display: block; /* Redundant as parent has overflow-x: auto */
                /* overflow-x: auto; /* Handled by parent #templatemo_content_right */
            }
            
            .data-table th, 
            .data-table td {
                padding: 8px 10px;
                white-space: nowrap; /* Prevent text wrapping */
            }
        }
    </style>
    <script>
        // Client-side session check (optional if check_login.php is set up)
        /*
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') {
                    window.location.href = 'index.html';
                }
            }).catch(error => console.error('Error checking login status:', error));
        */
            
        // Live clock display
        function showTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateString = now.toLocaleDateString([], { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            
            const clockElements = document.querySelectorAll('.clock-display');
            clockElements.forEach(el => {
                el.textContent = `${dateString} | ${timeString}`;
            });
            
            setTimeout(showTime, 1000);
        }
        
        // Initialize clock when page loads
        window.onload = showTime;
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
                <li><a href="#" class="current">View</a>
                    <ul>
                        <li><a href="mviewschedule.php" class="current">View schedule</a></li> <!-- Assuming this is mviewschedule.php -->
                        <li><a href="exitrequest1.php">View exit request</a></li>
                        <li><a href="mrequest-view.php">View maintenance request</a></li>
                        <li><a href="mmessage.php">View message</a></li>
                        <li><a href="comment12.php">View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php">Fuel</a></li>
                <li><a href="upload.php">Report</a></li>
                <li><a href="changepssmanager.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <!-- Left Column Removed -->
            <div id="templatemo_content_right">
                <h1 class="schedule-title">Vehicle Schedule</h1>
                
                <div class="clock-display"></div>
                
                <?php
                if ($conn && $conn instanceof mysqli) { // Check connection
                    $query = "SELECT Driver_ID, Driver_Name, Driver_phone_no, Vehicle_type, Plate_no, Place_of_start, Place_of_arrive, Service_time, Date, Outgoing_time, Enterance_time, `For` FROM schedule"; // Using backticks for `For`
                    $result = $conn->query($query);

                    if (!$result) {
                        echo '<div class="no-data">Error loading schedule: ' . htmlspecialchars($conn->error) . '</div>';
                    } elseif ($result->num_rows > 0) {
                        echo '<table class="data-table">';
                        echo '<thead><tr>
                                <th>Driver ID</th>
                                <th>Driver Name</th>
                                <th>Phone No</th>
                                <th>Vehicle Type</th>
                                <th>Plate No</th>
                                <th>Departure</th>
                                <th>Destination</th>
                                <th>Service Time</th>
                                <th>Date</th>
                                <th>Out Time</th>
                                <th>Return Time</th>
                                <th>Purpose</th>
                              </tr></thead><tbody>';

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

                        echo "</tbody></table>";
                    } else {
                        echo '<div class="no-data">No schedule data available at the moment.</div>';
                    }

                    if ($conn) { $conn->close(); } // Close connection
                } else {
                     echo '<div class="no-data">Database connection error.</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            Copyright © 2025 <a href="#">AMU University</a> | <a href="http://www.AMU.edu.et" target="_blank">AMU Vehicle Management Office</a>
        </div>
    </div>
</body>
</html>