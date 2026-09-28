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
    <title>Scheduler Portal | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            /* --amu-dark-base: #121212; -- Original Dark */
            --amu-navy-bg-heavy: rgba(0, 0, 128, 0.85); /* Navy for header/footer */
            --amu-navy-bg-medium: rgba(0, 0, 128, 0.75); /* Navy for content sections */
            --amu-navy-bg-light: rgba(0, 0, 128, 0.7); /* Navy for sidebar */
            --amu-navy-bg-lighter: rgba(0, 0, 128, 0.5); /* Navy for inner sidebar section */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
            --amu-primary-rgb: 46, 125, 50; 
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            color: var(--amu-text);
            min-height: 100vh;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Header Styles */
        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-heavy); /* NAVY */
            padding: 10px 5%;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3); /* Shadow kept black for depth */
        }

        .logo-container {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 60px;
            max-width: 120px;
        }

        #templatemo_top_panel img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        #site_title {
            font-size: clamp(0.9rem, 3vw, 1.3rem);
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 10px;
            flex: 2;
            min-width: 180px;
        }

        #templatemo_menu {
            flex: 3;
            min-width: 200px;
            max-width: 100%;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }

        #templatemo_menu li {
            position: relative;
            margin: 2px 0;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: clamp(0.7rem, 2vw, 0.85rem);
            display: block;
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
            background-color: rgba(0, 0, 128, 0.95); /* NAVY Darker dropdown */
            border-radius: 0 0 4px 4px;
            min-width: 160px;
            padding: 5px 0;
            z-index: 1001;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        #templatemo_menu ul ul li {
            margin: 0;
            width: 100%;
        }

        #templatemo_menu ul ul a {
            padding: 8px 15px;
            text-align: left;
            white-space: nowrap;
        }

        /* Main Content */
        #templatemo_content_panel {
            flex: 1;
            padding: 20px 0; 
            width: 100%;
        }

        #templatemo_content_section {
            display: flex;
            flex-direction: column;
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            gap: 20px; 
        }

        /* Sidebar */
        #templatemo_content_left {
            background-color: var(--amu-navy-bg-light); /* NAVY */
            padding: 15px; /* MODIFIED: Reduced overall padding for height */
            border-radius: 10px;
            order: 1;
            width: 100%; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }

        #login_section {
            background-color: var(--amu-navy-bg-lighter); /* NAVY */
            border-radius: 8px; 
            padding: 10px 15px; /* MODIFIED: Reduced vertical padding */
            text-align: center;
            width: 100%;
        }

        #login_section_title {
            font-size: 1.2rem; 
            font-weight: 600;
            margin-bottom: 10px; /* MODIFIED: Reduced margin */
            color: var(--amu-primary);
        }

        .scheduler-icon-container {
            margin: 0 auto 10px; /* MODIFIED: Reduced bottom margin for height */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 120px; /* Back to original larger size */
            height: 120px; /* Back to original larger size */
            border-radius: 50%;
            background-color: rgba(0,0,0,0.1); /* Subtle dark background on navy for icon container */
            border: 3px solid var(--amu-primary);
        }
        .scheduler-icon-container .fas.fa-user-tie {
            font-size: 60px; /* Back to original larger size */
            color: var(--amu-primary);
        }

        #login_section_middle p {
            font-size: 0.95rem;
            color: var(--amu-text-secondary);
            margin-top: 5px; /* MODIFIED: Reduced top margin */
        }
         #login_section_middle p strong {
            color: var(--amu-text);
            font-weight: 600;
        }

        /* Main Content Area */
        #templatemo_content_right {
            order: 2;
            width: 100%;
        }

        .right_column_section {
            background-color: var(--amu-navy-bg-medium); /* NAVY */
            border-radius: 10px;
            padding: 20px; 
            margin-bottom: 20px; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }

        .right_column_section_title {
            font-size: clamp(1.2rem, 3.5vw, 1.5rem); 
            font-weight: 600;
            margin-bottom: 20px; 
            color: var(--amu-primary);
            text-align: center;
            border-bottom: 1px solid rgba(var(--amu-primary-rgb), 0.3); 
            padding-bottom: 10px;
        }

        .content-body {
            font-size: 1rem;
            line-height: 1.8;
            text-align: justify;
            padding: 0 10px;
        }

        .content-body p {
            margin-bottom: 15px;
        }
        .content-body ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .content-body li {
            margin-bottom: 8px;
        }

        /* Footer */
        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-heavy); /* NAVY */
            padding: 15px 5%; 
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: clamp(0.75rem, 2.2vw, 0.85rem); 
            width: 100%;
        }

        #templatemo_footer_section a {
            color: var(--amu-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary-dark);
        }

        /* Responsive Adjustments */
        @media (min-width: 768px) {
            #templatemo_content_section {
                flex-direction: row;
            }
            
            #templatemo_content_left {
                flex: 0 0 280px; /* Back to original larger width for sidebar container */
                order: 1;
            }
            
            #templatemo_content_right {
                flex: 1;
                order: 2;
                padding-left: 25px; 
            }

            #templatemo_top_panel img {
                height: 50px;
            }
            .scheduler-icon-container { /* Adjust for desktop */
                width: 150px; /* Back to original larger size */
                height: 150px; /* Back to original larger size */
            }
            .scheduler-icon-container .fas.fa-user-tie {
                font-size: 75px; /* Back to original larger size */
            }
        }

        @media (max-width: 480px) {
            #templatemo_top_panel {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
            
            .logo-container { 
                display: none;
            }
            
            #site_title {
                order: 1;
                width: 100%;
                margin: 5px 0;
            }
            
            #templatemo_menu {
                order: 2;
                width: 100%;
            }
            
            #templatemo_menu ul {
                justify-content: space-around; 
            }
             #templatemo_menu a {
                padding: 8px 5px; 
                font-size: clamp(0.65rem, 1.8vw, 0.75rem);
            }
            #templatemo_content_panel {
                padding: 15px 2%;
            }
            #templatemo_content_left, .right_column_section {
                padding: 10px; /* MODIFIED: Reduced padding for smaller screens for height */
            }
             #login_section {
                padding: 10px; /* MODIFIED: Reduced padding for smaller screens for height */
            }
            .scheduler-icon-container {
                 /* Keep previous smaller size for very small screens, or adjust if needed */
                width: 100px; 
                height: 100px;
            }
            .scheduler-icon-container .fas.fa-user-tie {
                font-size: 50px;
            }


        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => {
                if (!response.ok) { 
                    throw new Error('Network response was not ok for check_login.php');
                }
                return response.text();
            })
            .then(data => {
                if (data.trim().toLowerCase() === 'false') {
                    window.location.href = 'index.html';
                }
            })
            .catch(error => {
                console.error('Error checking login status:', error);
            });
    </script>
</head>
<body>
    <div id="templatemo_top_panel">
        <div class="logo-container">
            <img src="wou arm.jpg.png" alt="AMU Logo">
        </div>
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="scheduler.php" class="current">Home</a></li>
                <li><a href="newsche.php">Schedule</a></li>
                <li><a href="#">View</a>
                    <ul>
                        <li><a href="sviewschedule.php">View Schedule</a></li>
                        <li><a href="smessage.php">View Messages</a></li>
                    </ul>
                </li>
                <li><a href="searchvinfo1.php">Search Vehicle</a></li>
                <li><a href="changepss.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="logo-container" style="justify-content: flex-end;">
            <img src="wou arm.jpg.png" alt="AMU Logo">
        </div>
    </div>

    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">Scheduler Portal</div>
                    <div class="scheduler-icon-container">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div id="login_section_middle">
                        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        Vehicle Scheduling Overview
                    </div>
                    <div class="content-body">
                        <p>Vehicle scheduling, also known as "blocking," is the process of assigning vehicles to cover trips detailed in the timetable. A vehicle "block" represents a vehicle's complete schedule for a given day. This includes:</p>
                        <!-- <ol>
                            <li>Departure from the depot (pull-out).</li>
                            <li>A sequence of trips according to the timetable.</li>
                            <li>Any necessary non-revenue travel (dead-head trips).</li>
                            <li>Return to the depot (pull-in).</li>
                        </ol> -->
                        <p>Once the timetable is established, the time and mileage vehicles spend in revenue service (completing scheduled trips) are fixed. Therefore, the primary objective in vehicle scheduling is to minimize non-revenue time and distance, such as pull-ins, pull-outs, dead-heads, and layovers. These activities are considered "unproductive" and should be optimized to enhance operational efficiency.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © Copyright <?php echo date("Y"); ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Vehicle Management Office</a>
        </div>
    </div>
</body>
</html>