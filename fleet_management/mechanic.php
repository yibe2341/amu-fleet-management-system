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
    <title>Mechanic Portal | AMU Fleet System</title>
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
            --amu-navy-bg-box: rgba(26, 35, 126, 0.65);
            --amu-navy-bg-content-box: rgba(26, 35, 126, 0.55); /* For inner content boxes */
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
            display: flex; /* For footer */
            flex-direction: column; /* For footer */
        }

        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 15px rgba(0,0,0,0.4);
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
            padding: 8px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        #templatemo_content_panel {
            display: flex;
            flex: 1; /* Allow content to take available space */
            padding: 30px 5%;
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            gap: 30px;
        }

        #templatemo_content_left {
            width: 240px; /* Same reduced width as user portal */
            flex-shrink: 0;
            background-color: var(--amu-navy-bg-panel); /* MODIFIED */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            /* border-right: 1px solid rgba(255, 255, 255, 0.1); /* Original, can remove if not desired */
        }

        #login_section {
            background-color: var(--amu-navy-bg-box); /* MODIFIED */
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 0; /* As it's the only element in left panel */
            text-align: center;
        }

        #login_section_title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-text); /* MODIFIED: White for contrast */
            border-bottom: 1px solid var(--amu-primary);
            padding-bottom: 8px;
        }

        /* Styles for the mechanic icon */
        .mechanic-icon-container {
            margin: 20px 0;
            text-align: center;
        }
        .mechanic-icon-container .fas {
            font-size: 80px; /* Same reduced size as user icon */
            color: var(--amu-primary);
        }
        #login_section_middle p { /* Copied from user portal for welcome message */
            font-size: 0.9rem;
            color: var(--amu-text-secondary);
            margin-top: 10px;
        }


        #templatemo_content_right {
            flex: 1;
            padding: 0; /* Padding will be on .right_column_section */
        }

        .right_column_section {
            background-color: var(--amu-navy-bg-panel); /* MODIFIED */
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        .right_column_section_title {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--amu-text); /* MODIFIED: White for contrast */
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        .content-box {
            background-color: var(--amu-navy-bg-content-box); /* MODIFIED */
            border-radius: 8px;
            padding: 25px; /* Increased padding */
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.1); /* Subtle border */
        }
        .content-box:last-child {
            margin-bottom: 0;
        }

        .content-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--amu-primary);
            margin-bottom: 15px;
            text-align: left; /* Changed to left for better readability of content title */
            padding-bottom: 5px;
            border-bottom: 1px solid var(--amu-primary-dark);
        }

        .content-body {
            font-size: 1rem;
            line-height: 1.7; /* Adjusted line height */
            text-align: justify;
            color: var(--amu-text-secondary); /* Softer text for body */
        }
        .content-body p {
            margin-bottom: 1em;
        }
         .content-body p:last-child {
            margin-bottom: 0;
        }


        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* Stick footer to bottom */
        }
        #templatemo_footer_section {
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: #4caf50;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary-dark);
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            #templatemo_content_section {
                flex-direction: column;
                gap: 20px;
            }
            #templatemo_content_left {
                width: 100%;
                max-width: 400px;
                margin: 0 auto;
            }
            #templatemo_content_right {
                width: 100%;
            }
            #site_title {
                font-size: 1.2rem;
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
            #templatemo_content_panel {
                padding: 20px 3%;
            }
            .content-title {
                font-size: 1.2rem;
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
            })
            .catch(error => console.error('Error checking login status:', error));
        */
    </script>
</head>
<body>
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="mechanic.php" class="current"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="Mmrequest-view.php"><i class="fas fa-tools"></i> View Requests</a></li>
                <li><a href="massage2.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="changepssmechanic.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">Mechanic Portal</div>
                    <!-- Replaced image with icon -->
                    <div class="mechanic-icon-container">
                        <i class="fas fa-user-cog"></i> <!-- Mechanic related icon -->
                    </div>
                    <div id="login_section_middle">
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        <i class="fas fa-info-circle"></i> Welcome to Mechanic Dashboard
                    </div>
                    <div class="content-box">
                        <div class="content-title"><i class="fas fa-shield-alt"></i> Why is Preventive Maintenance Important?</div>
                        <div class="content-body">
                            <p>Preventive maintenance is crucial for ensuring the longevity and reliability of any fleet. Regular checks and timely interventions help in identifying potential issues before they escalate into costly repairs or, worse, lead to vehicle breakdowns. This proactive approach significantly contributes to operational efficiency and safety.</p>
                            
                            <p><strong>Saves Money:</strong> By addressing minor issues early, you prevent them from becoming major, expensive problems. This also helps in getting the most out of your vehicle investment.</p>
                            
                            <p><strong>Increases Longevity:</strong> Well-maintained vehicles last longer. It's not uncommon for modern vehicles to exceed 250,000 miles with consistent care, reducing the frequency of fleet replacement.</p>
                         
                        </div>
                    </div>
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