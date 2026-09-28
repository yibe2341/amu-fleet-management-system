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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports | AMU Fleet System</title>
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
            justify-content: center; /* Center the content vertically */
            flex: 1; /* Takes remaining vertical space */
            min-height: calc(100vh - 160px); /* Header and Footer height */
            padding: 40px 20px; /* Padding for the overall content area */
            width: 100%;
            backdrop-filter: blur(2px); /* Blur background behind content */
        }

        /* .content-left styles removed as the element is removed */

        .content-right { /* This holds the report-container */
            width: 100%; /* Takes full width of its parent (.content-panel) */
            max-width: 800px; /* Max width for the report search area */
            /* padding: 20px 40px; /* Removed as .report-container will have padding */
        }

        .report-container {
            background-color: var(--amu-new-bg); /* MODIFIED: Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* Optional: can be here or on parent */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            /* max-width: 800px; /* Moved to .content-right for overall centering behavior */
            /* margin: 0 auto; /* Centering handled by parent .content-panel */
            width: 100%; /* Ensure it uses the width from .content-right */
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px; /* Adjusted margin */
            color: #fff; /* MODIFIED: White for better contrast on navy blue */
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        .page-title::after { /* Underline for title */
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); /* Consider changing accent color */
        }


        .search-form {
            background-color: rgba(0, 0, 0, 0.2); /* Lighter background for form inside navy blue */
            border-radius: 8px;
            padding: 25px;
            margin-top: 20px;
        }

        .form-group {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px; /* Added gap for better spacing */
        }

        .form-group label {
            /* margin: 0 10px; /* Removed, gap handles spacing */
            font-weight: 500;
        }

        .form-group input {
            padding: 10px 15px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--amu-text);
            /* margin: 0 10px; /* Removed, gap handles spacing */
            width: 150px;
        }
        .form-group input::placeholder { /* Style placeholder text */
            color: var(--amu-text-secondary);
            opacity: 0.7;
        }


        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            background-color: var(--amu-primary); /* Consider changing accent color */
            color: white;
            display: inline-flex; /* For icon alignment */
            align-items: center;
            gap: 8px; /* Space between icon and text */
        }

        .btn:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .date-format-note {
            text-align: center;
            font-size: 0.9rem;
            color: var(--amu-text-secondary);
            margin-top: 10px;
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
                padding: 20px; /* Adjust padding for smaller screens */
                justify-content: flex-start; /* Align content to top */
            }
            
            .content-right {
                /* padding: 15px; /* Padding now on .report-container or .content-panel */
            }
            
            .report-container {
                padding: 20px;
            }
            .page-title {
                font-size: 1.6rem;
            }
            
            .form-group {
                flex-direction: column;
                align-items: stretch; /* Make labels and inputs full width */
                gap: 10px;
            }
            
            .form-group label {
                text-align: left;
                margin-bottom: 5px;
            }

            .form-group input {
                width: 100%;
                /* margin: 5px 0; /* Removed, gap handles spacing */
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
            }).catch(error => console.error('Error checking login status:', error));
        */
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
                <li><a href="#" >View</a>
                    <ul>
                        <li><a href="mviewschedule.php">View schedule</a></li>
                        <li><a href="exitrequest1.php">View exit request</a></li>
                        <li><a href="mrequest-view.php">View maintenance request</a></li>
                        <li><a href="mmessage.php">View message</a></li>
                        <li><a href="comment12.php">View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php">Fuel</a></li>
                <li><a href="upload.php" class="current">Report</a></li> <!-- Assuming this is the current page, so keeping upload.php -->
                <li><a href="changepssmanager.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-panel">
        <!-- Left content panel removed -->
        <div class="content-right">
            <div class="report-container">
                <h1 class="page-title">View Reports</h1>
                
                <div class="search-form">
                    <form action="report.php" method="post"> <!-- Assuming report.php will handle the form submission -->
                        <div class="form-group">
                            <label for="dayfrom">From:</label>
                            <input type="text" name="dayfrom" id="dayfrom" class="tcal" required placeholder="DD-MM-YYYY">
                            
                            <label for="dayto">To:</label>
                            <input type="text" name="dayto" id="dayto" class="tcal" required placeholder="DD-MM-YYYY">
                        </div>
                        
                        <p class="date-format-note">Date Format: DD-MM-YYYY</p>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
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
    <!-- You might need to include the tcal.js script for the date picker if it's not globally included -->
    <!-- <script type="text/javascript" src="path/to/tcal.js"></script> --> 
</body>
</html>