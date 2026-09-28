<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Database connection
include('config.php'); // Ensure this path is correct

// Retrieve permissions
$permissions = [];
$status_message = ''; // Initialize status message
$status_type = '';    // Initialize status type

try {
    // Check if $conn is a valid mysqli object before proceeding
    if ($conn && $conn instanceof mysqli) {
        $query = "SELECT * FROM permission ORDER BY Date DESC";
        $result = $conn->query($query);
        
        if ($result) {
            $permissions = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            // Use $conn->error for MySQLi error
            throw new Exception($conn->error);
        }
        $conn->close(); // Close connection after fetching data
    } else {
        throw new Exception("Database connection is not valid.");
    }
} catch (Exception $e) {
    $status_message = "Error loading permissions: " . $e->getMessage();
    $status_type = "error";
    // If $conn was valid but an error occurred, it might already be closed or not.
    // If it's an error before $conn->query, $conn might still be open.
    // Consider closing $conn here if it's still open and valid, but it's safer to assume it's handled or problematic.
}
// $conn->close(); // Moved inside the try block after successful query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Permissions | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32; /* AMU green - consider if this still fits with the new blue */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
            --amu-new-bg: rgba(26, 35, 126, 0.85); /* Navy Blue */
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
            background-color: rgba(0, 0, 0, 0.9); /* Submenu background */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        #templatemo_content_panel {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 160px); /* Header and Footer height */
            padding: 20px 0; /* Padding for the overall content area */
            width: 100%;
            position: relative;
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px; /* Max width for content */
            margin: 0 auto; /* Center content */
            padding: 0 15px; /* Padding for content within max-width */
            gap: 20px; /* If left panel is restored */
        }

        /* Styles for the left panel (currently commented out in HTML) */
        #templatemo_content_left {
            width: 250px;
            background-color: var(--amu-new-bg); /* MODIFIED: Navy Blue */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            flex-shrink: 0;
            height: fit-content;
        }

        #login_section {
            background-color: var(--amu-new-bg); /* MODIFIED: Navy Blue - Can be slightly different if needed */
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            padding: 20px;
        }

        #login_section_title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-primary); /* Consider changing this to white or a light color */
        }

        #login_section img {
            width: 150px; /* Adjusted for consistency with other pages */
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px auto;
            display: block;
            border: 3px solid var(--amu-primary); /* Consider changing this border color */
        }
        /* End of left panel styles */

        #templatemo_content_right {
            flex: 1;
            position: relative;
            backdrop-filter: blur(2px); /* Applies blur to the area behind the content box */
            width: 100%; /* Ensures it takes full width if left panel is commented out */
        }

        .right_column_section { /* This is the "Exit Permissions" container */
            background-color: var(--amu-new-bg); /* MODIFIED: Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* More pronounced blur for this specific box, if desired */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            margin-bottom: 20px; /* If multiple sections in right column */
            width: 100%;
        }

        .right_column_section_title {
            font-size: 1.8rem; /* Made title larger */
            font-weight: 600;
            margin-bottom: 25px;
            color: #fff; /* MODIFIED: White for better contrast on navy blue */
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        .right_column_section_title::after { /* Underline for title */
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); /* Consider changing this accent color */
        }


        /* Status message styles */
        .status-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: rgba(0,0,0,0.6); /* Darker background for messages */
            color: white;
            text-align: center;
            border-left: 4px solid var(--amu-primary); /* Default border */
        }
        
        .status-success {
            border-left-color: var(--amu-success);
        }
        
        .status-error {
            border-left-color: var(--amu-danger);
        }

        /* Table styles */
        .permission-table-container {
            overflow-x: auto; /* Enable horizontal scroll for the container */
            margin-top: 20px;
            width: 100%;
        }
        
        .permission-table {
            width: 100%;
            min-width: 700px; /* Adjust as needed for your columns */
            border-collapse: collapse;
            /* background-color: rgba(255, 255, 255, 0.1); Removed as parent has new bg */
        }
        
        .permission-table th {
            background-color: var(--amu-primary); /* Consider changing this accent color */
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            position: sticky; /* For sticky headers */
            top: 0;
            z-index: 1; /* Ensure headers are above scrolling content */
        }
        
        .permission-table td {
            padding: 10px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .permission-table tr:hover td { /* Applied hover to td for better visibility */
            background-color: rgba(255, 255, 255, 0.08);
        }
        .permission-table tr:nth-child(even) td { /* Subtle striping */
             background-color: rgba(255, 255, 255, 0.03);
        }
        .permission-table tr:nth-child(even):hover td {
            background-color: rgba(255, 255, 255, 0.1);
        }


        #templatemo_footer_panel {
            background-color: var(--amu-new-bg); /* MODIFIED: Navy Blue */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* Pushes footer to bottom */
            width: 100%;
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0 auto;
            max-width: 1200px;
        }
         #templatemo_footer_section a {
            color: #4caf50; /* Consider changing this link color */
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary); /* Consider changing this hover color */
            text-decoration: underline;
        }


        @media (max-width: 992px) { /* Adjusted breakpoint for more general tablet/desktop changes */
            #templatemo_content_section {
                flex-direction: column;
                align-items: center; /* Center items when stacked */
            }
            #templatemo_content_left { /* If restored */
                width: 100%;
                max-width: 400px; /* Limit width on medium screens */
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                margin-bottom: 20px;
            }
            #templatemo_content_right {
                width: 100%;
                /* padding: 0; Padding is on .right_column_section */
            }
            #templatemo_menu ul { /* Allow menu to wrap on medium screens */
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
            #templatemo_menu li {
                margin: 5px; /* Adjust margin for better spacing */
            }
        }

        @media (max-width: 768px) { /* Styles for smaller mobile screens */
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            #templatemo_top_panel img { /* Hide logos on very small screens if needed */
                 display: none;
            }
            #site_title {
                margin: 10px 0;
                font-size: 1.2rem;
            }
            #templatemo_menu ul { /* Stack menu items vertically */
                flex-direction: column;
                align-items: center;
            }
            #templatemo_menu li {
                margin: 5px 0;
            }
            
            #templatemo_content_right {
                 padding: 0; /* Remove outer padding, .right_column_section has its own */
            }
            .right_column_section {
                padding: 20px; /* Reduce padding for smaller screens */
            }
             .right_column_section_title {
                font-size: 1.5rem;
            }
            
            .permission-table {
                font-size: 0.85rem;
            }
            
            .permission-table th, 
            .permission-table td {
                padding: 8px 10px;
            }
        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') { // Made check case-insensitive and trimmed
                    window.location.href = 'index.html';
                }
            })
            .catch(error => console.error('Error checking login:', error));
    </script>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="driver.php">Home</a></li>
                <li><a href="requestmaintenance.php">Request Maintenance</a></li>
                <li><a href="#" class="current">View</a>
                    <ul>
                        <li><a href="dviewschedule.php">View Schedule</a></li>
                        <li><a href="viewmessage.php">View Messages</a></li>
                        <li><a href="exit11.php" class="current">View Permission</a></li>
                    </ul>
                </li>
                <li><a href="exitrequest.php">Request Exit</a></li>
                <li><a href="changepssdriver.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <!-- Left Panel is commented out in your HTML structure -->
            <!--
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">Driver Portal</div>
                    <img src="Driverr.png" alt="Driver">
                    <div id="login_section_middle">
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    </div>
                </div>
            </div>
            -->
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        Exit Permissions
                    </div>
                    <div class="right_column_section_body">
                        <?php if (!empty($status_message)): ?>
                            <div class="status-message status-<?php echo htmlspecialchars($status_type); ?>">
                                <?php echo htmlspecialchars($status_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="permission-table-container">
                            <?php if (empty($permissions) && empty($status_message)): ?>
                                <div class='status-message' style='border-left-color: var(--amu-info, #17a2b8);'>No permissions found.</div>
                            <?php elseif (!empty($permissions)): ?>
                                <table class="permission-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Plate Number</th>
                                            <th>Start Time</th>
                                            <th>Return Time</th>
                                            <th>Permission</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($permissions as $permission): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($permission['id']); ?></td>
                                                <td><?php echo htmlspecialchars($permission['Plate_no']); ?></td>
                                                <td><?php echo htmlspecialchars($permission['Start_time']); ?></td>
                                                <td><?php echo htmlspecialchars($permission['Return_time']); ?></td>
                                                <td><?php echo htmlspecialchars($permission['Permission']); ?></td>
                                                <td><?php echo htmlspecialchars($permission['Date']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
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