<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration file
include('config.php');

// Check if a comment was deleted
$delete_message = ''; // Initialize to avoid undefined variable notice
if (isset($_GET['deleted'])) {
    $delete_message = '<div class="confirmation-message success">Comment was successfully deleted.</div>';
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
    <title>View Comments | AMU Fleet System</title>
    <meta name="keywords" content="AMU University, Fleet Management, View Comments" />
    <meta name="description" content="AMU University Fleet Management System - View Comments" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; -- Original Dark, replaced by navy */
            --amu-navy-base: #000080; /* Solid Navy for reference */
            --amu-navy-transparent-heavy: rgba(0, 0, 128, 0.85);
            --amu-navy-transparent-medium: rgba(0, 0, 128, 0.75);
            --amu-navy-transparent-light: rgba(0, 0, 128, 0.5);
            --amu-navy-transparent-hover: rgba(0, 0, 128, 0.1); /* Lighter for hover */
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
            display: flex;
            flex-direction: column;
        }

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            background-color: var(--amu-navy-transparent-heavy); /* Changed to navy */
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
            background-color: rgba(0, 0, 128, 0.9); /* Changed to navy (darker dropdown) */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 40px 5%;
            flex-grow: 1; 
            display: flex; 
            justify-content: center; 
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            width: 100%;
            max-width: 1200px; 
        }

        /* ===== Main Content Area (formerly Right Column) Styles ===== */
        #templatemo_content_right {
            background-color: var(--amu-navy-transparent-medium); /* Changed to navy */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); /* Shadow kept black */
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            overflow-x: auto; 
        }

        .messages-title { 
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--amu-primary); 
            position: relative;
            padding-bottom: 10px;
            text-align: center;
        }

        .messages-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        .clock-display-wrapper {
            text-align: center; 
            margin-bottom: 20px;
        }

        .clock-display {
            font-size: 1.2rem;
            color: var(--amu-primary);
            font-family: 'Arial', sans-serif;
            background-color: var(--amu-navy-transparent-light); /* Changed to navy */
            padding: 10px 15px;
            border-radius: 6px;
            display: inline-block; 
        }

        /* Table Styles */
        .table-wrapper { 
            overflow-x: auto;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            color: var(--amu-text);
            min-width: 700px; 
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: rgba(46, 125, 50, 0.6); /* Semi-transparent green, slightly more opaque */
            font-weight: 600;
            position: sticky; 
            top: 0;
            z-index: 1;
        }
        td:last-child { 
            text-align: center;
        }
        td pre { 
            white-space: pre-wrap; 
            white-space: -moz-pre-wrap; 
            white-space: -pre-wrap; 
            white-space: -o-pre-wrap; 
            word-wrap: break-word; 
            margin: 0;
            font-family: inherit; 
        }


        tr:hover {
            background-color: var(--amu-navy-transparent-hover); /* Changed to navy hover */
        }

        .action-icon { 
            color: var(--amu-danger);
            font-size: 1.1rem; 
            transition: transform 0.2s, color 0.2s;
        }

        .action-icon:hover {
            transform: scale(1.2);
            color: #a71d2a; 
        }

        .confirmation-message {
            padding: 15px;
            margin-bottom: 20px;
            border-left-width: 4px;
            border-left-style: solid;
            border-radius: 4px;
            color: var(--amu-text); 
        }
        .confirmation-message.success {
            background-color: rgba(40, 167, 69, 0.2); 
            border-left-color: var(--amu-success);
        }
        .confirmation-message.error { /* Example for error messages if needed */
            background-color: rgba(220, 53, 69, 0.2);
            border-left-color: var(--amu-danger);
        }


        .no-data {
            text-align: center;
            padding: 30px;
            color: var(--amu-text-secondary);
            font-size: 1.1rem;
            background-color: var(--amu-navy-transparent-light); /* Changed to navy */
            border-radius: 10px;
            margin: 20px 0;
        }
        .no-data i {
            margin-right: 8px;
            color: var(--amu-primary);
        }


        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: var(--amu-navy-transparent-heavy); /* Changed to navy */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: var(--amu-primary); 
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary-dark);
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
            }
            
            #templatemo_menu ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            #templatemo_menu li {
                margin: 5px;
            }

            #templatemo_content_panel {
                padding: 20px 3%; 
            }
            #templatemo_content_right {
                padding: 20px; 
            }
            
            .messages-title {
                font-size: 1.5rem;
            }
            .clock-display {
                font-size: 1rem;
                padding: 8px 12px;
            }

            table {
                min-width: 0; 
                font-size: 0.9rem;
            }
             th, td {
                padding: 8px 10px;
             }
        }
    </style>
    <script>
        // Live clock display
        function showTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateString = now.toLocaleDateString([], { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            
            const clockElements = document.querySelectorAll('.clock-display');
            clockElements.forEach(el => {
                if(el) {
                    el.textContent = `${dateString} | ${timeString}`;
                }
            });
            
            setTimeout(showTime, 1000);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.clock-display')) {
                showTime();
            }
        });

        function confirmDeleteComment() {
            return confirm('Are you sure you want to delete this comment? This action cannot be undone.');
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
                        <li><a href="mrequest-view.php">View maintenance request</a></li>
                        <li><a href="mmessage.php">View message</a></li>
                        <li><a href="comment12.php" class="current">View comment</a></li>
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
            <div id="templatemo_content_right">
                <h1 class="messages-title">View Comments</h1>
                
                <div class="clock-display-wrapper">
                    <div class="clock-display">Loading time...</div>
                </div>
                
                <?php if (!empty($delete_message)) echo $delete_message; ?>
                
                <div class="table-wrapper">
                <?php
                if (isset($conn) && $conn instanceof mysqli) {
                    $query = "SELECT * FROM contact ORDER BY date DESC";
                    $result = $conn->query($query);

                    if (!$result) {
                        echo '<div class="no-data error"><i class="fas fa-exclamation-triangle"></i> Error fetching comments: ' . htmlspecialchars($conn->error) . '</div>';
                    } elseif ($result->num_rows > 0) {
                        echo '<table>';
                        echo '<thead><tr> 
                                <th>First Name</th> 
                                <th>Last Name</th> 
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Date</th> 
                                <th>Comments</th>
                                <th>Action</th> 
                              </tr></thead><tbody>';

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['telephone']) . '</td>';
                            echo '<td>' . htmlspecialchars(date("M d, Y H:i", strtotime($row['date']))) . '</td>';
                            echo '<td><pre>' . htmlspecialchars($row['comments']) . '</pre></td>'; 
                            echo '<td>
                                    <a href="tt4.php?first_name=' . urlencode($row['first_name']) . 
                                    '&last_name=' . urlencode($row['last_name']) . 
                                    '&view=delete" onclick="return confirmDeleteComment();">
                                        <i class="fas fa-trash-alt action-icon"></i>
                                    </a>
                                  </td>';
                            echo '</tr>';
                        }

                        echo '</tbody></table>';
                    } else {
                        echo '<div class="no-data"><i class="fas fa-comment-slash"></i> No comments found in the database.</div>';
                    }

                    $conn->close();
                } else {
                    echo '<div class="no-data error"><i class="fas fa-database"></i> Database connection error. Please check configuration.</div>';
                }
                ?>
                </div> 
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