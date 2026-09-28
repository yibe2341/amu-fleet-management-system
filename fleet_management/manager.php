<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Vehicle Manager | AMU Fleet System</title>
    <meta name="keywords" content="AMU University, Fleet Management, Vehicle Manager" />
    <meta name="description" content="AMU University Fleet Management System - Vehicle Manager Panel" />
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

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            background-color: rgba(26, 35, 126, 0.85); /* MODIFIED: Header Navy Blue */
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
            background-color: var(--amu-primary);
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
            z-index: 1001; /* Ensure dropdown is above other content */
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 40px 5%;
            min-height: calc(100vh - 160px); /* Considers header and footer */
            display: flex;
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            gap: 30px;
        }

        /* ===== Left Column Styles ===== */
        #templatemo_content_left {
            width: 300px;
            flex-shrink: 0;
        }

        #login_section {
            background-color: #1A237E; /* MODIFIED: Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        #login_section_title {
            font-size: 1.5rem;
            font-weight: 600;
            padding: 20px;
            text-align: center;
            color: #fff;
            background-color: rgba(46, 125, 50, 0.3); /* AMU Green tint for distinction */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #login_section_middle {
            padding: 20px;
            text-align: center;
        }

        .manager-icon {
            font-size: 100px;
            color: var(--amu-primary); /* Keep existing icon color */
            margin: 20px 0;
        }

        /* ===== Right Column Styles ===== */
        #templatemo_content_right {
            flex-grow: 1;
            background-color: #1A237E; /* MODIFIED: Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
        }

        .right_column_section_title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
            position: relative;
            padding-bottom: 10px;
        }

        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        .post_body {
            background-color: #1A237E;; /* Keeping distinct green-tinted background */
            border-radius: 10px;
            padding: 25px;
            color: white;
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .responsibilities {
            margin-top: 30px;
        }

        .responsibilities h3 {
            color: var(--amu-primary); /* Keep existing heading color */
            margin-bottom: 15px;
        }

        .responsibilities ul {
            padding-left: 20px;
        }

        .responsibilities li {
            margin-bottom: 10px;
        }

        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: rgba(26, 35, 126, 0.85); /* MODIFIED: Footer Navy Blue */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* Ensure footer sticks to bottom if content is short */
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
        @media (max-width: 992px) {
            #templatemo_content_panel {
                padding: 30px 3%;
            }
            #templatemo_content_section {
                flex-direction: column;
                align-items: center; /* Center items when stacked */
            }
            
            #templatemo_content_left {
                width: 100%;
                max-width: 400px; /* Give left panel a max width when stacked */
                 margin-bottom: 20px; /* Space when stacked */
            }
            #templatemo_content_right {
                width: 100%; /* Ensure right panel takes full width when stacked */
                padding: 25px;
            }
            
            #login_section {
                display: flex;
                align-items: center;
                /* margin-bottom: 20px; */ /* Removed as parent has margin */
            }
            
            #login_section_middle {
                padding: 20px;
                flex-grow: 1; /* Allow icon area to grow */
            }
            
            #login_section_title {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                padding: 20px; /* Ensure consistent padding */
                flex-shrink: 0; /* Prevent title from shrinking too much */
            }
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
            }
            
            #templatemo_menu li {
                margin: 5px;
            }
            
            #login_section {
                flex-direction: column;
            }
            
            #login_section_title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                width: 100%; /* Ensure title takes full width */
            }
            
            .manager-icon {
                font-size: 80px;
            }
            .post_body, .responsibilities {
                padding: 20px; /* Adjust padding for smaller screens */
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="manager.php" class="current">Home</a></li>
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
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">MANAGER PANEL</div>
                    <div id="login_section_middle">
                        <div class="manager-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section_title">Welcome to Vehicle Manager Dashboard</div>
                <div class="post_body">
                    <p>The University's Vehicle Manager is responsible for maintaining compliance with state mandates governing vehicle fleet management and ensuring efficient operation of all university vehicles.</p>
                    
                    <div class="responsibilities">
                        <h3>Key Responsibilities:</h3>
                        <ul>
                            <li>Monthly collection and data entry of vehicle use report information and vehicle specific data into the fleet database</li>
                            <li>Planning, directing, and managing programs for vehicle acquisition, assignment, utilization, maintenance, repair, replacement and disposal</li>
                            <li>Developing and implementing University-level policies and procedures related to vehicle fleet management</li>
                           
                            
                        </ul>
                    </div>
                </div>
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