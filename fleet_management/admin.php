<?php
session_start(); // Assuming session is used, though not explicitly checked in this HTML snippet

// Check if the user is logged in (Example, actual check might be more complex)
// if (!isset($_SESSION['username'])) {
//     header("Location: index.html");
//     exit();
// }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Admin Panel | AMU Fleet Management</title>
    <meta name="keywords" content="AMU University, Fleet Management, Admin, Report, Account" />
    <meta name="description" content="AMU University Fleet Management System - Admin Panel" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; -- Original Dark, now using navy */
            --amu-navy-bg-heavy: rgba(0, 0, 128, 0.85); 
            --amu-navy-bg-medium: rgba(0, 0, 128, 0.75); 
            --amu-navy-bg-light: rgba(0, 0, 128, 0.7);   
            --amu-navy-dropdown: rgba(0, 0, 128, 0.9); 
            --amu-navy-bg-lighter: rgba(0, 0, 128, 0.5); /* For .post_body */
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
            display: flex;
            flex-direction: column;
        }

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-heavy); /* NAVY BLUE */
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
            /* flex-grow: 1; -- Reverted */
            /* margin: 0 1rem; -- Reverted */
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            /* flex-wrap: wrap; -- Reverted */
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 8px; /* Reverted */
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px; /* Reverted */
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem; /* Reverted */
            /* white-space: nowrap; -- Reverted */
            /* display: flex; -- Reverted */
            /* align-items: center; -- Reverted */
            /* gap: 6px; -- Reverted */
        }
        /* Icon specific styles from navbar icons are removed */


        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--amu-navy-dropdown); /* NAVY BLUE */
            border-radius: 0 0 4px 4px;
            width: 180px; /* Reverted */
            padding: 5px 0;
            z-index: 1001; 
            /* box-shadow: 0 3px 8px rgba(0,0,0,0.2); -- Reverted */
        }
         #templatemo_menu ul ul a { 
            padding: 8px 15px; /* Reverted */
            font-size: 0.9rem; /* Reverted - assuming dropdown items matched main menu item font size */
            /* width: 100%; -- Reverted */
            /* box-sizing: border-box; -- Reverted */
        }


        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 40px 5%; /* Reverted */
            min-height: calc(100vh - 160px); 
            display: flex;
            /* justify-content: center; -- Reverted */
            backdrop-filter: blur(2px);
            /* flex-grow: 1; -- Reverted, body handles flex */
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px; 
            margin: 0 auto; /* Reverted to auto margin for centering section */
            gap: 30px;
        }

        /* ===== Left Column Styles ===== */
        #templatemo_content_left {
            width: 300px; /* Reverted */
            flex-shrink: 0;
        }

        #login_section {
            background-color: var(--amu-navy-bg-light); /* NAVY BLUE */
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
            padding: 20px; /* Reverted */
            text-align: center;
            color: #fff;
            background-color: rgba(46, 125, 50, 0.3); /* Original green overlay */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #login_section_middle {
            padding: 20px; /* Reverted */
            text-align: center;
        }

        /* MODIFIED: Admin Icon styling */
        .admin-icon-container {
            margin: 0 auto; /* Center the icon container */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 150px; /* Original image width */
            height: 150px; /* Original image height */
            border-radius: 50%; /* To make it circular like the image */
            /* background-color: rgba(0,0,0,0.1); -- Optional: if a slight background is needed */
            border: 3px solid rgba(255, 255, 255, 0.1); /* Original image border */
        }
        .admin-icon-container .fas.fa-user-tie {
            font-size: 75px; /* Adjust icon size to fit well within 150x150px */
            color: var(--amu-primary); /* Green icon, or var(--amu-text) for white */
        }
        /* #login_section_middle img styling removed */


        /* ===== Right Column Styles ===== */
        #templatemo_content_right {
            flex-grow: 1;
            background-color: var(--amu-navy-bg-medium); /* NAVY BLUE */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
        }

        .right_column_section_title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px; /* Reverted */
            color: #fff; /* Reverted */
            position: relative;
            padding-bottom: 10px;
            /* text-align: center; -- Reverted (was default left) */
        }

        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0; /* Reverted */
            /* transform: translateX(-50%); -- Reverted */
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        .post_body {
            background-color: var(--amu-navy-bg-medium); /* Original green-tinted background */
            border-radius: 10px; 
            padding: 25px;
            color: white; 
            line-height: 1.7;
            font-size: 1.1rem; 
        }
         .post_body p {
            margin-bottom: 1em; 
        }
        .post_body p:last-child {
            margin-bottom: 0;
        }

        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-heavy); /* NAVY BLUE */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: #4caf50; /* Original footer link color */
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary); 
            text-decoration: underline;
        }

        /* ===== Responsive Adjustments (Reverted to state before navbar focus) ===== */
        @media (max-width: 992px) {
             #templatemo_content_panel { 
                padding: 30px 3%;
            }
            #templatemo_content_section {
                flex-direction: column;
                /* align-items: center; -- Reverted */
            }
            
            #templatemo_content_left {
                width: 100%;
                /* max-width: 500px; -- Reverted */
            }
             #templatemo_content_right { 
                 /* width: 100%; -- Reverted */
                 /* max-width: 700px; -- Reverted */
                padding: 25px;
            }
            
            #login_section {
                display: flex;
                align-items: center;
                margin-bottom: 20px; 
            }
            
            #login_section_middle {
                padding: 20px; /* Reverted */
                /* flex-grow: 1; -- Reverted */
                /* display: flex; -- Reverted */
                /* justify-content: center; -- Reverted */
                /* align-items: center; -- Reverted */
            }
             /* Icon specific responsive sizing removed for now to match original image space */

            
            #login_section_title {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                padding: 20px; 
                flex-shrink: 0;
                margin-bottom: 0; /* Reverted */
                /* font-size: 1.3rem; -- Reverted */
            }
        }

        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column; /* Reverted */
                height: auto; 
                padding: 15px; 
            }
             #templatemo_top_panel img { /* Reverted */
                display: none;
            }
            
            #site_title {
                margin: 10px 0 15px; /* Reverted */
                /* font-size: 1.2rem; -- Reverted */
            }
            
            #templatemo_menu ul {
                 flex-wrap: wrap; /* Reverted */
                 justify-content: center; /* Reverted */
                /* flex-direction: column; -- Reverted */
                /* align-items: center; -- Reverted */
            }
            
            #templatemo_menu li {
                margin: 5px; /* Reverted */
                /* width: 100%; -- Reverted */
                /* max-width: 250px; -- Reverted */
            }
             /* Menu 'a' justify content reverted */
            /* Menu ul ul styles reverted */
            
            #login_section {
                flex-direction: column; 
            }
            
            #login_section_title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                width: 100%; 
                 margin-bottom: 0; /* Reverted to original state, it was 0 */
                 /* font-size: 1.4rem; -- Reverted */
            }
            /* Admin icon container responsive styles reverted to default */
            /* .admin-icon-container {
                width: 100px; 
                height: 100px;
                 margin-bottom: 15px; 
            }
            .admin-icon-container .fas.fa-user-tie {
                font-size: 50px;
            } */

            /* .right_column_section_title font size reverted */
            /* .post_body styles reverted */
        }
        /* @media (max-width: 600px) styles for header elements removed to revert */

    </style>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo"> <!-- Kept class for clarity if needed later -->
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu"> 
            <ul>
                <li><a href="Admin.php" class="current">Home</a></li>
                <li><a href="#">Account</a>
                    <ul>
                        <li><a href="createaccount.php">Create account</a></li> 
                        <li><a href="view.php">Update account</a></li> 
                        <li><a href="view2.php">Delete account</a></li> 
                    </ul>
                </li>
                <li><a href="aviewschedule.php">View Schedule</a></li> 
                <li><a href="upload1.php">Report</a></li>
                <li><a href="changepssadmin.php">Change Password</a></li> 
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo"> <!-- Kept class for clarity -->
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">ADMIN PANEL</div>
                    <div id="login_section_middle">
                        <!-- Replaced img with Font Awesome icon container -->
                        <div class="admin-icon-container">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section_title">Welcome to AMU Fleet Management</div>
                <div class="post_body">
                    <p>Arba Minch University, located in Arba Minch, Ethiopia, is a prominent institution of higher education. Established in 2004, it offers a range of undergraduate and postgraduate programs across various fields, including engineering, natural sciences, social sciences, business, and health sciences.</p>
                    <p>As an administrator, you have access to the fleet management system where you can manage accounts, view schedules, generate reports, and change system settings.</p>
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
    <script>
        // Client-side session check (optional)
        /*
        document.addEventListener('DOMContentLoaded', function() {
            fetch('check_login.php')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(data => {
                    if (data.trim().toLowerCase() === 'false') {
                        window.location.href = 'index.html';
                    }
                })
                .catch(error => console.error('Error checking login status:', error));
        });
        */
    </script>
</body>
</html>