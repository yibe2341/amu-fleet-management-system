<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Retrieve status message if it exists
$status_message = $_SESSION['status_message'] ?? '';
$status_type = $_SESSION['status_type'] ?? '';
unset($_SESSION['status_message']);
unset($_SESSION['status_type']);
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
            /* --amu-dark: #121212; /* Original black, replaced by navy */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;

            /* Navy Blue Theme Variables */
            --amu-navy-bg-header-footer: rgba(26, 35, 126, 0.85);
            --amu-navy-bg-panel: rgba(26, 35, 126, 0.75);
            --amu-navy-bg-box: rgba(26, 35, 126, 0.65); /* For inner boxes like login_section */
             --amu-navy-bg-table-container: rgba(26, 35, 126, 0.7); /* For table container background */
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            color: var(--amu-text);
            min-height: 100vh;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-header-footer);
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1%; 
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 1000;
            width: 100%;
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
            margin: 0 10px; 
            white-space: nowrap; 
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 4px; /* MODIFIED: Reduced horizontal margin from 8px to 4px */
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            white-space: nowrap;
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
            background-color: var(--amu-navy-bg-box); /* MODIFIED: Dropdown background */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
            z-index: 1001; /* Ensure dropdown is on top */
            box-shadow: 0 3px 8px rgba(0,0,0,0.3);
        }
         #templatemo_menu ul ul a:hover {
            background-color: var(--amu-primary-dark); /* Hover for dropdown items */
        }


        #templatemo_menu li:hover > ul {
            display: block;
        }

        #templatemo_content_panel {
            display: flex;
            flex: 1;
            /* min-height: calc(100vh - 160px); */ /* Let flex:1 handle height */
            padding: 20px 0;
            width: 100%;
            position: relative;
            backdrop-filter: blur(2px); /* Keep if desired */
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px; /* Or your preferred max width */
            margin: 0 auto;
            padding: 0 15px; /* Keep padding around content */
        }

        /* #templatemo_content_left is commented out in HTML, so its styles are not strictly needed here */
        /* but keeping for reference if it's uncommented */
        #templatemo_content_left {
            width: 250px;
            background-color: var(--amu-navy-bg-panel); /* MODIFIED */
            padding: 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            border-radius: 10px; /* Added for consistency */
            box-shadow: 0 3px 10px rgba(0,0,0,0.2); /* Added */
        }

        #login_section {
            background-color: var(--amu-navy-bg-box); /* MODIFIED */
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }

        #login_section_title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-text); /* MODIFIED: white/light gray on navy */
        }

        #login_section img {
            width: 150px; /* Slightly smaller if desired */
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px; /* Adjusted margin */
            display: block;
            border: 3px solid var(--amu-primary);
        }
        /* End of #templatemo_content_left styles */


        #templatemo_content_right {
            flex: 1;
            /* padding: 70px ; /* Original padding - this seems very large, reducing */
            padding: 20px; /* More reasonable padding */
            overflow: hidden; /* Keep if needed */
            position: relative;
            width: 100%; /* Ensure it takes full width if left is removed */
        }

        .right_column_section {
            background-color: var(--amu-navy-bg-panel); /* MODIFIED */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* Can be intense if body already has it */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 25px; /* Adjusted padding */
            /* margin-bottom: 20px; /* Removed if it's the only section */
            width: 100%;
        }

        .right_column_section_title {
            font-size: 1.6rem; /* Slightly larger title */
            font-weight: 600;
            margin-bottom: 25px; /* Increased margin */
            color: var(--amu-text); /* MODIFIED: White/light gray for contrast */
            text-align: center;
            padding-bottom: 10px; /* Space for underline */
            border-bottom: 2px solid var(--amu-primary); /* Underline */
        }

        /* Status message styles */
        .status-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: var(--amu-navy-bg-box); /* MODIFIED */
            color: white;
            text-align: center;
            border-left: 4px solid var(--amu-primary);
        }
        
        .status-success {
            border-left-color: var(--amu-success);
            background-color: rgba(40, 167, 69, 0.3); /* Success tint */
        }
        
        .status-error {
            border-left-color: var(--amu-danger);
            background-color: rgba(220, 53, 69, 0.3); /* Danger tint */
        }

        /* Table styles */
        .schedule-table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 20px;
            position: relative;
            border-radius: 8px; /* Rounded corners for table container */
            background-color: var(--amu-navy-bg-table-container); /* MODIFIED */
            padding: 1px; /* To contain border radius if table has borders */
        }
        
        .schedule-table {
            width: 100%;
            min-width: 900px; /* Or your preferred min-width */
            border-collapse: collapse;
            /* background-color: rgba(255, 255, 255, 0.1); /* Original, now handled by container */
        }
        
        .schedule-table th {
            background-color: var(--amu-primary);
            color: white;
            padding: 12px 15px; /* Adjusted padding */
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1; /* Ensure header on top */
        }
        
        .schedule-table td {
            padding: 10px 15px; /* Adjusted padding */
            border-bottom: 1px solid rgba(255, 255, 255, 0.15); /* Slightly more visible border */
        }
        
        .schedule-table tr:hover td { /* Keep row hover */
            background-color: rgba(255, 255, 255, 0.08);
        }
        .schedule-table tbody tr:last-child td {
            border-bottom: none; /* Remove border from last row */
        }


        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* Stick to bottom */
            width: 100%;
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0 auto;
            max-width: 1200px;
        }
         #templatemo_footer_section a {
            color: #4caf50; /* Green for links */
            text-decoration: none;
        }
        #templatemo_footer_section a:hover {
            text-decoration: underline;
            color: var(--amu-primary-dark);
        }


        @media (max-width: 992px) {
            #templatemo_content_section {
                flex-direction: column;
            }
            
            #templatemo_content_left {
                width: 100%;
                max-width: 450px; /* Limit width of left panel on tablets when stacked */
                margin: 0 auto 20px auto; /* Center it and add bottom margin */
                border-right: none;
                /* border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* Original, removed if left panel not shown */
            }
            
            #templatemo_content_right {
                padding: 15px;
                 width: 100%; /* Ensure right content takes full width when left is gone or stacked */
            }
            
            #templatemo_menu ul {
                flex-direction: column;
                align-items: center; /* Center menu items */
            }
            
            #templatemo_menu li {
                margin: 5px 0;
                width: 100%; /* Make menu items full width for easier tapping */
                text-align: center;
            }
             #templatemo_menu a {
                display: block; /* Ensure full clickable area */
            }

            
            .schedule-table {
                font-size: 0.9rem; /* Adjust font for readability */
            }
            
            .schedule-table th, 
            .schedule-table td {
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px; /* Padding for stacked header */
            }
            
            #templatemo_top_panel img { /* Optionally hide one logo */
                 /* display: none; */
            }
             #templatemo_top_panel img:last-of-type {
                display: none; /* Hide second logo on small screens */
            }

            
            #site_title {
                margin: 10px 0; /* Adjust vertical margin when title is stacked */
                font-size: 1.2rem; /* Adjust title size */
            }
            
            .right_column_section {
                padding: 20px;
            }
            .right_column_section_title {
                font-size: 1.4rem;
            }
        }
    </style>
    <script>
        // Client-side session check (original)
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') { // Robust check
                    window.location.href = 'index.html';
                }
            })
            .catch(error => console.error('Error checking login status:', error));
    </script>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="driver.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="requestmaintenance1.php"><i class="fas fa-tools"></i> Request Maintenance</a></li>
                <li><a href="#" class="current"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a>
                    <ul>
                        <li><a href="dviewschedule.php" class="current">View Schedule</a></li>
                        <li><a href="viewmessage.php">View Messages</a></li>
                        <li><a href="exit11.php">View Permission</a></li>
                    </ul>
                </li>
                <li><a href="exitrequest.php"><i class="fas fa-door-open"></i> Request Exit</a></li>
                <li><a href="changepssdriver.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <!-- Left content is commented out in your HTML, so it won't display -->
            <!-- <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">Driver Portal</div>
                    <img src="Driverr.png" alt="Driver" width="200" height="200">
                    <div id="login_section_middle">
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    </div>
                </div>
            </div> -->
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                       <i class="fas fa-calendar-alt"></i> Vehicle Schedule
                    </div>
                    <div class="right_column_section_body">
                        <?php if (!empty($status_message)): ?>
                            <div class="status-message status-<?php echo htmlspecialchars($status_type); ?>">
                                <?php echo htmlspecialchars($status_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Connect to the database
                        // Ensure config.php is in the correct path or include it correctly
                        include_once('config.php'); // Using include_once is safer

                        // Check connection
                        if ($conn->connect_error) {
                            // It's better to show a user-friendly message than die() directly in production
                            // For debugging, die() is okay.
                            error_log("Database Connection failed: " . $conn->connect_error); // Log error
                            echo "<p style='color:red; text-align:center;'>Error: Could not connect to the database. Please try again later.</p>";
                            // Optionally exit or skip table display
                        } else {

                            // Get results from database
                            // It's good to select specific columns and order results
                            $query = "SELECT Driver_ID, Driver_Name, Driver_phone_no, Vehicle_type, Plate_no, Place_of_start, Place_of_arrive, Service_time, `Date`, Outgoing_time, Enterance_time, `For` FROM schedule ORDER BY `Date` DESC, Outgoing_time DESC";
                            $result = $conn->query($query);

                            if (!$result) {
                                error_log("Database Query Error: " . $conn->error); // Log error
                                echo "<p style='color:red; text-align:center;'>Error: Could not retrieve schedule data. " . htmlspecialchars($conn->error) . "</p>";
                            } else {
                                if ($result->num_rows > 0) {
                        ?>
                                    <div class="schedule-table-container">
                                        <table class="schedule-table">
                                            <thead>
                                                <tr> 
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['Driver_ID']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Driver_Name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Driver_phone_no']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Vehicle_type']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Plate_no']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Place_of_start']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Place_of_arrive']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Service_time']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Date']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Outgoing_time']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['Enterance_time']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['For']); ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                        <?php   
                                } else {
                                    echo "<p style='text-align:center; padding: 20px; color: var(--amu-text-secondary);'>No schedules currently available.</p>";
                                }
                                $result->free(); // Free result set
                            }
                            // Close the database connection
                            $conn->close();
                        } // End of database connection check
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            Copyright © <?php echo date("Y"); ?> <a href="#">AMU University</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>