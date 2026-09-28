<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
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
    <title>Driver Portal | AMU Fleet Management</title>
    <meta name="keywords" content="AMU University, Fleet Management, Driver, Portal" />
    <meta name="description" content="AMU University Fleet Management System - Driver Portal" />
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
            display: flex; 
            flex-direction: column; 
        }

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            background-color: var(--amu-new-bg); 
            height: 75px; /* MODIFIED: Slightly reduced height */
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 3%; /* MODIFIED: Slightly reduced horizontal padding */
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.5); /* Slightly deeper shadow */
            position: relative;
            z-index: 1000;
        }

        #templatemo_top_panel img.logo-img { /* Added class for logos */
            height: 45px; /* MODIFIED: Slightly smaller logo */
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 3px rgba(255,255,255,0.2)); /* Subtle glow for logo */
        }

        #site_title {
            font-size: 1.3rem; /* MODIFIED: Slightly smaller title */
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px; /* MODIFIED: Increased letter spacing */
            flex-grow: 1; 
            margin: 0 1rem; 
            text-shadow: 1px 1px 3px rgba(0,0,0,0.4); /* MODIFIED: Added subtle text shadow */
        }

        /* ===== Navigation Styles ===== */
        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0.3rem; /* MODIFIED: Slightly reduced gap between main menu items */
        }

        #templatemo_menu li {
            position: relative;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 0.6rem 0.9rem; /* MODIFIED: Adjusted padding */
            border-radius: 5px; 
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease; /* Added more transitions */
            font-size: 0.85rem; /* MODIFIED: Slightly smaller font */
            display: flex;
            align-items: center;
            gap: 0.5rem; /* MODIFIED: Increased gap for icon and text */
            white-space: nowrap;
        }
        #templatemo_menu a .fas { 
            font-size: 0.9em; 
            line-height: 1; /* Ensure icon aligns well */
        }


        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary); 
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3); /* Enhanced shadow on hover */
        }
        #templatemo_menu a.current { /* Specific style for current to stand out slightly more */
            box-shadow: 0 0 10px rgba(46,125,50,0.5), inset 0 0 5px rgba(0,0,0,0.2);
        }


        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: calc(100% + 5px); /* MODIFIED: Add small gap below parent */
            left: 0;
            background-color: rgba(10, 20, 80, 0.95); /* Darker navy blue for dropdown */
            border-radius: 0 0 6px 6px; 
            width: 210px; /* MODIFIED: Slightly wider dropdown */
            padding: 0.5rem 0;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3); /* Deeper shadow for dropdown */
            border: 1px solid rgba(255,255,255,0.15);
            border-top: none;
            z-index: 1001;
        }

        #templatemo_menu li:hover > ul {
            display: block;
            animation: fadeInDropdown 0.3s ease-out forwards; /* Added forwards */
        }
        @keyframes fadeInDropdown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #templatemo_menu ul ul a {
            padding: 0.7rem 1.2rem; 
            width: 100%;
            border-radius: 0; 
            color: var(--amu-text); /* Lighter text for dropdown */
        }
        #templatemo_menu ul ul a:hover {
            background-color: var(--amu-primary); /* Consistent hover */
            color: #fff;
            padding-left: 1.5rem; 
            transform: none; 
            box-shadow: none;
        }
        #templatemo_menu ul ul a .fas {
             margin-right: 0.6rem; /* More space for icon in dropdown */
        }


        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 40px 5%;
            min-height: calc(100vh - 155px); /* MODIFIED: Adjusted for new header height */
            display: flex;
            backdrop-filter: blur(2px);
            flex: 1; 
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
            background-color: var(--amu-new-bg); 
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
            padding: 0; 
        }

        #login_section_title {
            font-size: 1.5rem;
            font-weight: 600;
            padding: 20px;
            text-align: center;
            color: #fff; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); 
        }

        #login_section_middle {
            padding: 20px;
            text-align: center;
        }

        #login_section_middle img {
            width: 180px; 
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--amu-primary); 
            margin: 0 auto 15px auto; 
            display: block;
            box-shadow: 0 0 15px rgba(46,125,50,0.4);
        }
        #login_section_middle p { 
            color: var(--amu-text-secondary);
            font-weight: 500;
        }

        /* ===== Right Column Styles ===== */
        #templatemo_content_right {
            flex-grow: 1;
            background-color: var(--amu-new-bg); 
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
        }

        .right_column_section_title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px; 
            color: #fff; 
            position: relative;
            padding-bottom: 10px;
            text-align: center; 
        }

        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%; 
            transform: translateX(-50%); 
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); 
        }

        .post_body {
            background-color:  rgba(26, 35, 126, 0.85); 
            border-radius: 8px; 
            padding: 25px;
            color: white;
            line-height: 1.7;
            font-size: 1.1rem;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .post_body h3 {
            color: var(--amu-primary); 
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.4rem; 
        }

        .post_body p {
            margin-bottom: 15px;
        }

        .post_body p:last-child {
            margin-bottom: 0;
        }

        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: var(--amu-new-bg); 
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; 
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
        @media (max-width: 1024px) { /* Adjusted breakpoint for earlier nav wrapping */
            #site_title {
                 font-size: 1.2rem; /* Further reduce title if space is tight */
            }
            #templatemo_menu ul {
                gap: 0.1rem; /* Tighter gap for more items */
            }
             #templatemo_menu a {
                padding: 0.5rem 0.7rem; /* Less padding if items are many */
                font-size: 0.8rem;
            }
        }


        @media (max-width: 992px) {
            #templatemo_content_panel { 
                padding: 30px 3%;
            }
            #templatemo_content_section {
                flex-direction: column;
                align-items: center; 
            }
            
            #templatemo_content_left {
                width: 100%;
                max-width: 500px; 
            }
             #templatemo_content_right {
                width: 100%;
                max-width: 700px; 
            }
            
            #login_section {
                display: flex;
                align-items: center;
                margin-bottom: 20px; 
                padding: 20px; 
            }
            
            #login_section_middle {
                padding: 0 20px; 
                flex-grow: 1;
            }
            
            #login_section_title {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                padding: 20px; 
                margin-bottom: 0; 
                flex-shrink: 0;
            }
        }

        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            
            #templatemo_top_panel img.logo-img { /* Ensure logo is visible if not explicitly hidden */
                display: block; /* Or none if you prefer to hide it on mobile */
                 margin-bottom: 10px;
            }
             #templatemo_top_panel img.logo-img:last-of-type {
                display: none; /* Hide second logo if present */
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
             #templatemo_content_left {
                 max-width: 100%;
             }
             #templatemo_content_right {
                 padding: 25px;
             }

            
            #login_section {
                flex-direction: column;
                padding: 0; 
            }
             #login_section_middle {
                padding: 20px;
            }
            
            #login_section_title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                width: 100%; 
                margin-bottom: 20px; 
            }
            
            #login_section_middle img {
                width: 150px;
                height: 150px;
            }
            .post_body { 
                padding: 20px;
            }
            .right_column_section_title {
                font-size: 1.6rem;
            }
        }
    </style>
    <script>
        // Client-side session check (optional)
        /*
        document.addEventListener('DOMContentLoaded', function() {
            fetch('check_login.php')
                .then(response => response.text())
                .then(data => {
                    if (data.trim().toLowerCase() === 'false') {
                        window.location.href = 'index.html';
                    }
                })
                .catch(error => console.error('Error checking login status:', error));
        });
        */
    </script>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo-img">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="driver.php" class="current"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="requestmaintenance1.php"><i class="fas fa-tools"></i> Request Maintenance</a></li>
                <li>
                    <a href="#"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="dviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="viewmessage.php"><i class="fas fa-envelope"></i> View Messages</a></li>
                        <li><a href="exit11.php"><i class="fas fa-road"></i> View Permission</a></li>
                    </ul>
                </li>
                <li><a href="exitrequest.php"><i class="fas fa-sign-out-alt"></i> Request Exit</a></li>
                <li><a href="changepssdriver.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-power-off"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo-img">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">DRIVER PORTAL</div>
                    <div id="login_section_middle">
                        <img src="Driverr.png" alt="Driver Profile">
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section_title">Welcome to Driver Portal</div>
                <div class="post_body">
                    <h3>Vehicle Driver Responsibilities</h3>
                    <p>Drivers must observe all traffic regulations. Drivers are personally responsible for any traffic citations (tickets) that may be issued as a result of operating a University vehicle.</p>
                    <p>Drivers must take appropriate precautions when driving conditions are hazardous (including but not limited to dust storms, fog, heavy rain, snow, or ice conditions). This includes allowing enough time for travel.</p>
                    <p>Drivers are responsible for taking appropriate measures to secure and safeguard the vehicle until it is returned to the designated location at the University.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            Copyright © <?php echo date('Y'); ?> <a href="#">AMU University</a> | <a href="http://www.AMU.edu.et" target="_blank">AMU Vehicle Management Office</a>
        </div>
    </div>
</body>
</html>