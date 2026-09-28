<?php
session_start();
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
    <title>View Messages | AMU Fleet System</title>
    <meta name="keywords" content="AMU University, Fleet Management, View Messages" />
    <meta name="description" content="AMU University Fleet Management System - View Messages" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; -- Original dark, replaced by navy where appropriate */
            --amu-navy-transparent-heavy: rgba(0, 0, 128, 0.85); /* Navy Blue base for solid-like backgrounds */
            --amu-navy-transparent-medium: rgba(0, 0, 128, 0.75);/* Navy Blue for content areas */
            --amu-navy-transparent-light: rgba(0, 0, 128, 0.5);  /* Navy Blue for lighter elements like inputs */
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
            background-color: var(--amu-navy-transparent-heavy); /* Changed from rgba(0, 0, 0, 0.85) */
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
            background-color: rgba(0, 0, 128, 0.9); /* Changed from rgba(0, 0, 0, 0.9) */
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
            min-height: calc(100vh - 160px);
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
            background-color: var(--amu-navy-transparent-medium); /* Changed from rgba(0, 0, 0, 0.75) */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); /* Shadow kept black */
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
            background-color: rgba(46, 125, 50, 0.3); /* AMU Green transparent, kept as is */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #login_section_middle {
            padding: 20px;
            text-align: center;
        }

        .manager-icon {
            font-size: 100px;
            color: var(--amu-primary);
            margin: 20px 0;
        }

        /* ===== Right Column Styles ===== */
        #templatemo_content_right {
            flex-grow: 1;
            background-color: var(--amu-navy-transparent-medium); /* Changed from rgba(0, 0, 0, 0.75) */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); /* Shadow kept black */
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
        }

        .messages-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
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

        .search-form {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: var(--amu-navy-transparent-light); /* Changed from rgba(0, 0, 0, 0.5) */
            color: var(--amu-text);
            font-size: 1rem;
        }

        .search-input:focus {
            border-color: var(--amu-primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3);
        }

        .search-btn {
            padding: 12px 25px;
            background-color: var(--amu-primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .search-btn:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
        }

        .no-messages {
            text-align: center;
            padding: 30px;
            font-size: 1.2rem;
            color: var(--amu-text-secondary);
            background-color: rgba(0, 0, 128, 0.2); /* Lighter navy tint for this message box */
            border-radius: 10px;
            margin: 20px 0;
        }
         .no-messages i {
            color: var(--amu-primary); /* Make icon green for better visibility if desired */
            margin-right: 8px;
        }


        .clock-display {
            text-align: center;
            font-size: 1.2rem;
            color: var(--amu-primary);
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
            background-color: var(--amu-navy-transparent-light); /* Changed from rgba(0, 0, 0, 0.5) */
            padding: 10px;
            border-radius: 6px;
            display: inline-block; /* Changed from block to inline-block */
            width: auto; /* ensure it doesn't take full width */
            margin: 0 auto 20px; /* Center the inline-block */

        }

        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: var(--amu-navy-transparent-heavy); /* Changed from rgba(0, 0, 0, 0.85) */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: #4caf50; /* Kept as is, good contrast */
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary);
            text-decoration: underline;
        }

        /* ===== Responsive Adjustments ===== */
        @media (max-width: 992px) {
            #templatemo_content_section {
                flex-direction: column;
            }
            
            #templatemo_content_left {
                width: 100%;
            }
            
            #login_section {
                display: flex;
                align-items: center;
            }
            
            #login_section_middle {
                padding: 20px;
            }
            
            #login_section_title {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
        }

        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            
            #templatemo_top_panel img {
                display: none; /* Kept as is from original */
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
            
            #login_section {
                flex-direction: column;
            }
            
            #login_section_title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .manager-icon {
                font-size: 80px;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            /* .search-input width is already 100% due to flex:1 and column direction */
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
            
        // Live clock display
        function showTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            const dateString = now.toLocaleDateString();
            
            const clockElements = document.querySelectorAll('.clock-display');
            clockElements.forEach(el => {
                if (el) { // Check if element exists
                    el.textContent = `${dateString} ${timeString}`;
                }
            });
            
            setTimeout(showTime, 1000);
        }
        
        // Initialize clock when page loads
        window.onload = function() {
            // Ensure the element exists before trying to start the clock
            if (document.querySelector('.clock-display')) {
                showTime();
            }
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
                        <li><a href="mmessage.php" class="current">View message</a></li>
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
                    <div id="login_section_title">MANAGER PORTAL</div>
                    <div id="login_section_middle">
                        <div class="manager-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <h1 class="messages-title">View Messages</h1>
                
                <div style="text-align: center;"> <!-- Wrapper to center the inline-block clock -->
                    <div class="clock-display"></div>
                </div>
                
                <form method="post" action="viewmrmessage.php" class="search-form">
                    <input type="text" name="search" class="search-input" placeholder="Search by manager keyword..." required>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
                
                <!-- Message content would be displayed here -->
                <div class="no-messages">
                    <i class="fas fa-envelope-open-text"></i> Use the search form above to view messages.
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