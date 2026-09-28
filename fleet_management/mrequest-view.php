<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include('config.php'); // Ensure this doesn't output anything

// Check database connection after including config
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get results from the database
$query = "SELECT * FROM request"; // Assuming 'request' table stores maintenance requests
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Maintenance Requests | AMU Fleet System</title>
    <meta name="keywords" content="AMU University, Fleet Management, Maintenance Requests" />
    <meta name="description" content="AMU University Fleet Management System - Maintenance Requests" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212; /* Original Dark, not used if all navy */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-danger: #dc3545;
            --amu-new-bg: rgba(0, 0, 128, 0.85); /* Navy Blue base for opaque backgrounds */
            --amu-new-bg-medium: rgba(0, 0, 128, 0.75); /* Slightly more transparent for content areas */
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

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            background-color: var(--amu-new-bg); 
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
            flex-grow: 1; 
            margin: 0 15px; 
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap; 
            justify-content: center; 
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 5px; 
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 10px; 
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem; 
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
            background-color: rgba(0, 0, 128, 0.95); /* Darker navy for dropdown */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
            z-index: 1001; 
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 40px 5%;
            /* min-height: calc(100vh - 160px); -- Handled by flex-grow */
            display: flex; 
            justify-content: center; /* Center the content section */
            backdrop-filter: blur(2px); 
            flex: 1; 
        }

        #templatemo_content_section {
            /* display: flex; -- No longer needed as left column is removed */
            width: 100%;
            max-width: 1200px; 
            margin: 0 auto; 
            /* gap: 30px; -- Not needed */
        }

        /* Left Column Styles - REMOVED */
        /* #templatemo_content_left, #login_section, #login_section_title, #login_section_middle, .manager-icon CSS rules are removed */


        /* ===== Right Column (Now Main Content Area) Styles ===== */
        #templatemo_content_right {
            /* flex-grow: 1; -- Not needed as it's the only child in #templatemo_content_section */
            background-color: var(--amu-new-bg-medium); /* Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            overflow-x: auto; 
        }

        .maintenance-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff; 
            position: relative;
            padding-bottom: 10px;
            text-align: center;
        }

        .maintenance-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); 
        }

        .data-table-wrapper { /* Added wrapper for table */
            overflow-x: auto;
            width: 100%;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            min-width: 800px; /* Ensure table has a base width */
        }

        .data-table th {
            background-color: var(--amu-primary); 
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            position: sticky; 
            top: 0;
            z-index: 1; 
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .data-table tr:hover td { 
            background-color: rgba(255, 255, 255, 0.08); 
        }
        .data-table tr:nth-child(even) td { 
            background-color: rgba(0, 0, 0, 0.05); /* Subtle dark striping on navy */
        }
        .data-table tr:nth-child(even):hover td {
            background-color: rgba(255, 255, 255, 0.1);
        }


        .action-btn {
            color: var(--amu-danger); 
            text-decoration: none;
            font-size: 1.2rem;
            transition: all 0.3s;
            display: inline-block; 
        }

        .action-btn:hover {
            color: #c82333; 
            transform: scale(1.2);
        }
        .data-table td.action-cell { 
            text-align: center;
        }

        .no-requests {
            text-align: center;
            padding: 30px;
            font-size: 1.2rem;
            color: var(--amu-text-secondary);
            background-color: rgba(0, 0, 0, 0.1); 
            border-radius: 8px;
            margin: 20px 0;
            border: 1px dashed rgba(255,255,255,0.2);
        }
        .no-requests .fas { 
            font-size: 1.5rem;
            margin-right: 10px;
            color: var(--amu-primary); 
        }

        .clock-display-wrapper { /* Wrapper for clock */
            text-align: center;
            margin-bottom: 25px;
        }
        .clock-display {
            font-size: 1.1rem; 
            color: var(--amu-text); 
            font-family: 'Arial', sans-serif;
            background-color: rgba(0, 0, 0, 0.2); 
            padding: 8px 15px; 
            border-radius: 6px;
            display: inline-block; /* To allow centering via text-align on parent */
        }

        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: var(--amu-new-bg); 
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: #4caf50; 
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary); 
            text-decoration: underline;
        }

        /* ===== Responsive Adjustments ===== */
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
                flex-direction: column; 
                align-items: center;
            }
            
            #templatemo_menu li {
                margin: 5px 0; 
            }
            #templatemo_content_panel {
                padding: 20px;
            }
            #templatemo_content_right {
                padding: 20px; 
            }
            
            .maintenance-title {
                font-size: 1.6rem;
            }
            
            .data-table {
                font-size: 0.85rem;
                min-width: 0; /* Allow table to shrink further */
            }
            
            .data-table th, 
            .data-table td {
                padding: 8px 10px;
                white-space: normal; /* Allow wrapping on small screens */
            }
            .clock-display {
                font-size: 1rem;
                padding: 6px 10px;
            }
        }
    </style>
    <script>
        // Client-side session check (optional)
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
                if (el) { // Check if element exists
                    el.textContent = `${dateString} | ${timeString}`;
                }
            });
            
            setTimeout(showTime, 1000);
        }
        
        // Initialize clock when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.clock-display')) {
                 showTime();
            }
        });
        
        // Confirm deletion
        function confirmDeletion(driverName) {
            return confirm(`Are you sure you want to delete the request from ${driverName}?`);
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
                <li><a href="#">Vehicle</a>
                    <ul>
                        <li><a href="vehicle-register.html">Register vehicle</a></li>
                        <li><a href="view1.php">Update vehicle</a></li>
                        <li><a href="searchvinfo.html">Search vehicles</a></li>
                    </ul>
                </li>
                <li><a href="#" class="current">View</a>
                    <ul>
                        <li><a href="mviewschedule.php">View schedule</a></li>
                        <li><a href="exitrequest1.php">View exit request</a></li>
                        <li><a href="mrequest-view.php" class="current">View maintenance request</a></li>
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
            <!-- Left content column (#templatemo_content_left) removed -->
            
            <div id="templatemo_content_right">
                <h1 class="maintenance-title">Maintenance Requests</h1>
                
                <div class="clock-display-wrapper">
                    <div class="clock-display">Loading time...</div>
                </div>
                
                <div class="data-table-wrapper">
                <?php if ($result && $result->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Driver ID</th>
                                <th>Driver Name</th>
                                <th>Mechanic</th>
                                <th>Plate No</th>
                                <th>Vehicle Type</th>
                                <th>Date</th>
                                <th>Problem Description</th>
                                <th style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Driver_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Driver_Name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Mechanic_Name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PlateNo']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Vehicle_Type']); ?></td>
                                    <td><?php echo htmlspecialchars(date("M d, Y", strtotime($row['Date']))); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($row['Problem_of_vehicle'])); ?></td>
                                    <td class="action-cell">
                                        <a href="tt3.php?Driver_id=<?php echo urlencode($row['Driver_id']); ?>&plate_no=<?php echo urlencode($row['PlateNo']); ?>&date=<?php echo urlencode($row['Date']); ?>&view=delete" 
                                           class="action-btn"
                                           title="Delete request from <?php echo htmlspecialchars($row['Driver_Name']); ?>"
                                           onclick="return confirmDeletion('<?php echo htmlspecialchars($row['Driver_Name']); ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php elseif($result): ?>
                    <div class="no-requests">
                        <i class="fas fa-check-circle"></i> No maintenance requests found at the moment.
                    </div>
                <?php else: ?>
                     <div class="no-requests" style="color: var(--amu-danger); border-color: var(--amu-danger);">
                        <i class="fas fa-exclamation-triangle"></i> Error loading maintenance requests. Please try again later.
                    </div>
                <?php endif; ?>
                </div> <!-- End data-table-wrapper -->
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            Copyright © <?php echo date("Y"); ?> <a href="#">AMU University</a> | <a href="http://www.AMU.edu.et" target="_blank">AMU Vehicle Management Office</a>
        </div>
    </div>
</body>
</html>
<?php
// Check if $conn is a valid mysqli object before closing
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>