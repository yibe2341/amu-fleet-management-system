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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedule | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; */ /* Original black, replaced by navy */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;

            /* Navy Blue Theme Variables */
            --amu-navy-bg-header-footer: rgba(26, 35, 126, 0.85);
            --amu-navy-bg-panel: rgba(26, 35, 126, 0.75);
        }

        * {
            box-sizing: border-box;
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
            overflow-x: hidden; /* Prevent horizontal page scroll */
            display: flex; /* For footer positioning */
            flex-direction: column; /* For footer positioning */
        }

        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            position: relative;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        #templatemo_top_panel img {
            height: 50px;
            width: auto;
            max-width: 120px;
            object-fit: contain;
        }

        #site_title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex-grow: 1;
            margin: 0 20px;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: nowrap;
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
            white-space: nowrap;
            display: inline-flex; /* For icon alignment */
            align-items: center; /* For icon alignment */
            gap: 6px; /* Space for icon */
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        #templatemo_content_panel {
            flex: 1; /* Takes remaining vertical space */
            display: flex;
            padding: 30px 0; /* Adjusted padding */
            width: 100%;
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            display: flex; /* Kept as original */
            width: 100%;
            max-width: 1200px; /* Or your preferred max-width for the table view */
            margin: 0 auto;
            padding: 0 20px; /* Keep padding for content within the section */
        }

        /* #templatemo_content_left and its children CSS are removed or commented out */
        /*
        #templatemo_content_left {
            width: 250px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        #login_section {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        #login_section_title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-primary);
        }

        #login_section img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            border: 3px solid var(--amu-primary);
        }
        */

        #templatemo_content_right {
            flex: 1; /* Takes up available space */
            /* padding: 0 20px; /* Original padding, review if needed after left removal */
            /* width: calc(100% - 250px); /* Original width, now just flex: 1 */
            overflow: hidden; /* Keep if needed */
        }

        .right_column_section {
            background-color: var(--amu-navy-bg-panel); /* MODIFIED */
            border-radius: 10px;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        .right_column_section_title {
            font-size: 1.8rem; /* Made title slightly larger */
            font-weight: 600;
            padding: 20px;
            color: var(--amu-text); /* MODIFIED: White/light gray for contrast on navy */
            text-align: center;
            /* background-color: rgba(0, 0, 0, 0.75); /* Original, now inherited or same as .right_column_section */
            border-radius: 10px 10px 0 0;
            position: relative;
            border-bottom: 1px solid rgba(255,255,255,0.1); /* Add a separator */
        }
         /* Optional: Underline for title if desired
        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary);
        }
        */


        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 20px;
            /* background-color: rgba(0, 0, 0, 0.75); /* Inherits from .right_column_section now */
            border-radius: 0 0 10px 10px;
            width: 100%;
        }

        .schedule-table {
            width: 100%;
            min-width: 1100px; /* Keep min-width for horizontal scroll */
            border-collapse: collapse;
            /* background-color: rgba(255, 255, 255, 0.1); /* Table itself doesn't need separate bg if container has it */
        }

        .schedule-table th,
        .schedule-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15); /* Slightly more visible border */
            color: var(--amu-text-secondary); /* Default text color for cells */
        }
        .schedule-table td {
             color: var(--amu-text); /* Brighter text for data cells */
        }

        .schedule-table th {
            background-color: var(--amu-primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0; /* For sticky header */
            z-index: 1; /* Ensure header is above scrolling content */
        }

        .schedule-table tr:hover td { /* Hover effect for table rows */
            background-color: rgba(255, 255, 255, 0.08);
        }

        .button-container {
            padding: 20px; /* Adjusted padding */
            /* background-color: rgba(0, 0, 0, 0.75); /* Inherits from .right_column_section */
            border-radius: 0 0 10px 10px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1); /* Separator for button */
        }

        .print-button {
            background-color: var(--amu-primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
            display: inline-flex; /* For icon */
            align-items: center; /* For icon */
            gap: 8px; /* Space for icon */
        }
        .print-button:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
        }

        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
            margin-top: auto; /* Stick footer to bottom */
        }
        #templatemo_footer_section {
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
        }
        #templatemo_footer_section a {
            color: #4caf50;
            text-decoration: none;
        }
        #templatemo_footer_section a:hover {
            color: var(--amu-primary-dark);
            text-decoration: underline;
        }


        @media (max-width: 992px) { /* Added a breakpoint for slightly larger screens */
            #templatemo_content_right {
                 /* padding: 0 10px; /* Reduce padding slightly if needed */
            }
            .schedule-table {
                min-width: 900px; /* Adjust min-width for tablets */
            }
        }


        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px 5%;
            }
            #templatemo_top_panel img:first-of-type {
                display: none;
            }
            #site_title {
                margin: 10px 0;
                font-size: 1.2rem;
            }
            #templatemo_menu ul {
                flex-direction: column;
                align-items: center;
                width: 100%;
            }
            #templatemo_menu li {
                margin: 5px 0;
                width: 100%;
                text-align: center;
            }
             #templatemo_menu a {
                display: block;
            }

            #templatemo_content_section {
                flex-direction: column;
                padding: 0 10px; /* Adjust padding for smaller screens */
            }

            /* #templatemo_content_left styles are not needed as it's removed */
            /*
            #templatemo_content_left {
                width: 100%;
                margin-bottom: 20px;
            }
            */

            #templatemo_content_right {
                width: 100%;
                padding: 0; /* Remove padding if section has it */
            }
            .schedule-table {
                min-width: 700px; /* Further adjust for mobile if necessary */
            }
             .right_column_section_title {
                font-size: 1.5rem;
            }
            .table-container, .button-container {
                padding: 15px;
            }
        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') { // Make check case-insensitive and trim whitespace
                    window.location.href = 'index.html';
                }
            })
            .catch(error => console.error('Error checking login status:', error)); // Added error logging

        function printpage() {
            window.print();
        }
    </script>
</head>
<body>
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="user.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="userviewschedule.php" class="current"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                <li><a href="changepssuser.html"><i class="fas fa-key"></i> Change Password</a></li> <!-- Assuming .php -->
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <!-- The <div id="templatemo_content_left"> ... </div> has been REMOVED -->
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        <i class="fas fa-list-alt"></i> Vehicle Schedule
                    </div>
                    <div class="table-container">
                        <?php
                        // Ensure config.php is in the same directory or adjust path
                        // include_once('config.php'); // Use include_once or require_once
                        
                        // ---- START MOCK DB CONNECTION & DATA for testing without DB ----
                        // This is for standalone testing if config.php is problematic.
                        // Remove or comment this block when using your actual config.php
                        
                        // Mock $conn object
                        $conn = new stdClass();
                        $conn->connect_error = null; // No connection error
                        $conn->error = null; // No query error

                        // Mock $result object and fetch_assoc method
                        $mock_data = [
                            ['Driver_ID' => 'D001', 'Driver_Name' => 'John Doe', 'Driver_phone_no' => '123-456-7890', 'Vehicle_type' => 'Sedan', 'Plate_no' => 'AB 123 CD', 'Place_of_start' => 'Main Office', 'Place_of_arrive' => 'Client Site A', 'Service_time' => '09:00 - 17:00', 'Date' => '2023-10-27', 'Outgoing_time' => '08:30', 'Enterance_time' => '17:30', 'For' => 'Client Meeting'],
                            ['Driver_ID' => 'D002', 'Driver_Name' => 'Jane Smith', 'Driver_phone_no' => '987-654-3210', 'Vehicle_type' => 'Van', 'Plate_no' => 'EF 456 GH', 'Place_of_start' => 'Warehouse', 'Place_of_arrive' => 'Branch Office', 'Service_time' => '10:00 - 16:00', 'Date' => '2023-10-27', 'Outgoing_time' => '09:45', 'Enterance_time' => '16:15', 'For' => 'Delivery'],
                            // Add more mock rows if needed
                        ];
                        $current_row = 0;
                        $mock_result = new stdClass();
                        $mock_result->fetch_assoc = function() use (&$mock_data, &$current_row) {
                            if ($current_row < count($mock_data)) {
                                return $mock_data[$current_row++];
                            }
                            return null;
                        };
                        
                        // Mock $conn->query method
                        $conn->query = function($query) use ($mock_result) {
                            // You could add basic query validation here if needed for testing
                            return $mock_result;
                        };
                        
                        // Mock $conn->close method
                        $conn->close = function() {};
                        // ---- END MOCK DB CONNECTION & DATA ----
                        
                        // **IMPORTANT**: Comment out or remove the MOCK block above and UNCOMMENT the line below
                        //                when you have your actual 'config.php' ready.
                        include_once('config.php');


                        if ($conn->connect_error) {
                            die("<p style='color:red; text-align:center; padding:10px;'>Connection failed: " . htmlspecialchars($conn->connect_error) . "</p>");
                        }

                        $query = "SELECT Driver_ID, Driver_Name, Driver_phone_no, Vehicle_type, Plate_no, Place_of_start, Place_of_arrive, Service_time, Date, Outgoing_time, Enterance_time, `For` FROM schedule ORDER BY Date DESC, Outgoing_time DESC";
                        // Note: `For` is a reserved keyword in SQL, so it's good practice to escape it with backticks if that's your column name.
                        
                        $result = $conn->query($query);

                        if (!$result) {
                            die("<p style='color:red; text-align:center; padding:10px;'>Error fetching schedule: " . htmlspecialchars($conn->error) . "</p>");
                        }

                        if ($result->num_rows > 0) {
                            echo "<table class='schedule-table'>";
                            echo "<thead><tr> 
                                    <th>Driver ID</th> 
                                    <th>Driver Name</th> 
                                    <th>Phone No</th>
                                    <th>Vehicle Type</th> 
                                    <th>Plate No</th> 
                                    <th>Start Place</th>
                                    <th>Arrival Place</th> 
                                    <th>Service Time</th> 
                                    <th>Date</th> 
                                    <th>Outgoing Time</th> 
                                    <th>Entrance Time</th>
                                    <th>Purpose</th>
                                  </tr></thead>";
                            echo "<tbody>";
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
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                             echo "<p style='text-align:center; padding: 20px; color: var(--amu-text-secondary);'>No schedules found.</p>";
                        }

                        $conn->close();
                        ?>
                    </div>
                    <?php if (isset($result) && $result->num_rows > 0): // Only show button if there's data ?>
                    <div class="button-container">
                        <button class="print-button" onclick="printpage()">
                            <i class="fas fa-print"></i> Print Schedule
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
        Copyright © <?php echo date("Y"); ?> <a href="#">AMU University</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>