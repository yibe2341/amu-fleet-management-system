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
    <title>User Home | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;

            /* New Navy Blue Variables */
            --amu-navy-bg-header-footer: rgba(26, 35, 126, 0.85);
            --amu-navy-bg-panel: rgba(26, 35, 126, 0.75);
            --amu-navy-bg-box: rgba(26, 35, 126, 0.65);
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

        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-header-footer);
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
            display: inline-flex; /* For icon */
            align-items: center; /* For icon */
            gap: 6px; /* For icon */
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        #templatemo_content_panel {
            display: flex;
            flex: 1;
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
            width: 240px; /* MODIFIED: Reduced width */
            flex-shrink: 0;
            background-color: var(--amu-navy-bg-panel);
            padding: 20px; /* MODIFIED: Slightly reduced padding */
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        #login_section {
            background-color: var(--amu-navy-bg-box);
            border-radius: 8px;
            padding: 20px; /* MODIFIED: Slightly reduced padding */
            margin-bottom: 0;
            text-align: center;
        }

        #login_section_title {
            font-size: 1.2rem; /* MODIFIED: Slightly smaller title */
            font-weight: 600;
            margin-bottom: 15px; /* MODIFIED: Reduced margin */
            color: var(--amu-text);
            border-bottom: 1px solid var(--amu-primary);
            padding-bottom: 8px; /* MODIFIED: Reduced padding */
        }

        .user-icon-container {
            margin: 20px 0; /* MODIFIED: Reduced margin */
            text-align: center;
        }
        .user-icon-container .fas {
            font-size: 80px; /* MODIFIED: Reduced icon size */
            color: var(--amu-primary);
        }

        #login_section_middle p {
            font-size: 0.9rem; /* MODIFIED: Slightly smaller text */
            color: var(--amu-text-secondary);
            margin-top: 10px; /* MODIFIED: Reduced margin */
        }


        #templatemo_content_right {
            flex: 1;
            padding: 0;
        }

        .right_column_section {
            background-color: var(--amu-navy-bg-panel);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        .right_column_section_title {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--amu-text);
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

        .post_body {
            font-size: 1rem;
            line-height: 1.7;
            text-align: justify;
            color: var(--amu-text-secondary);
        }

        .post_body p {
            margin-bottom: 1.2em;
        }

        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-header-footer);
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
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
            #templatemo_content_left { /* Keep width responsive but with a max-width */
                width: 100%;
                max-width: 400px; /* Can adjust this for tablet view of left panel */
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
            /* On mobile, #templatemo_content_left will already be 100% width due to above rule */
        }

    </style>
    <script>
        // Client-side session check (optional, if check_login.php is set up)
        /*
        fetch('check_login.php')
            .then(response => response.json()) // Assuming check_login.php returns JSON like {"loggedIn": true}
            .then(data => {
                if (!data.loggedIn) {
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
                <li><a href="user.php" class="current"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="userviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                <li><a href="changepssuser.php"><i class="fas fa-key"></i> Change Password</a></li> <!-- Changed to .php -->
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">User Portal</div>
                    <div class="user-icon-container">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div id="login_section_middle">
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        Welcome to Your Dashboard
                    </div>
                    <div class="right_column_section_body">
                        <div class="post_body">
                           <p>Arba Minch University is located in Arba Minch town in the Southern Nations, Nationalities, and Peoples' Region (SNNPR) of Ethiopia. It was established in 2004 and has grown to become one of the leading higher education institutions in the region. The University has multiple campuses, including the Main Campus, Nech Sar Campus, Chamo Campus, and Abaya Campus. These campuses house various academic units such as the College of Natural and Computational Sciences, College of Agricultural Sciences, College of Social Sciences and Humanities, Institute of Technology, College of Medicine and Health Sciences, College of Business and Economics, School of Law, and the Institute of Water Technology.</p>                            
                           <p>This fleet management system is designed to help you view vehicle schedules and manage your account effectively. Please use the navigation menu above to access different features of the system.</p>
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