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
    <title>View Report | AMU Fleet Management</title>
    <meta name="keywords" content="AMU University, Fleet Management, Admin, Report" />
    <meta name="description" content="AMU University Fleet Management System - View Reports" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome for icons -->
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; -- Original Dark, replaced by navy in your provided code */
            --amu-navy-bg-heavy: rgba(0, 0, 128, 0.85); /* NAVY for header/footer - Using your navy variables */
            --amu-navy-bg-medium: rgba(0, 0, 128, 0.75); /* NAVY for content sections */
            --amu-navy-bg-light: rgba(0, 0, 128, 0.7);   /* NAVY for sidebar */
            --amu-navy-bg-lighter: rgba(0, 0, 128, 0.5);  /* NAVY for inputs */
            --amu-navy-dropdown: rgba(0, 0, 128, 0.9); /* NAVY for dropdowns */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-teal-green: #00897B;
            --amu-teal-green-dark: #00695C;
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

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            /* background-color: rgba(26, 35, 126, 0.85); /* MODIFIED: Header Navy Blue */ /* KEPT YOUR NAVY */
            background-color: var(--amu-navy-bg-heavy); /* USING YOUR VARIABLE */
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
            flex-grow: 1; 
            margin: 0 1rem; 
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap; 
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 5px; 
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 12px; 
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem; 
            white-space: nowrap; 
            display: flex; 
            align-items: center; 
            gap: 5px; 
        }
        #templatemo_menu a .fas { 
            font-size: 0.9em; 
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
            /* background-color: rgba(0, 0, 0, 0.9); */ /* Original black dropdown */
            background-color: var(--amu-navy-dropdown); /* USING YOUR NAVY VARIABLE */
            border-radius: 0 0 4px 4px;
            min-width: 180px; 
            padding: 5px 0;
            z-index: 1001; 
        }
         #templatemo_menu ul ul a {
            padding: 10px 15px; 
            font-size: 0.8rem;
        }


        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 30px 5%; 
            flex-grow: 1; 
            display: flex;
            justify-content: center; 
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px;
            gap: 30px;
        }

        /* ===== Left Column Styles ===== */
        #templatemo_content_left {
            width: 280px; 
            flex-shrink: 0;
        }

        #login_section {
            /* background-color: #1A237E; /* MODIFIED: Navy Blue */ /* KEPT YOUR NAVY */
            background-color: var(--amu-navy-bg-light); /* USING YOUR VARIABLE */
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
            background-color: rgba(46, 125, 50, 0.4); /* Kept your green overlay */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #login_section_middle {
            padding: 20px;
            text-align: center;
        }

        /* Original image style - to be replaced by icon container style */
        /* #login_section_middle img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.1);
        } */

        /* THIS IS THE ONLY NEW CSS ADDED - For the icon */
        .admin-icon-container {
            margin: 0 auto; 
            display: flex;
            justify-content: center;
            align-items: center;
            width: 150px; /* Matches original image width */
            height: 150px; /* Matches original image height */
            border-radius: 50%; 
            /* background-color: rgba(0,0,0,0.1); -- Optional: if a slight background is needed for the circle */
            border: 3px solid rgba(255, 255, 255, 0.1); /* Matches original image border */
        }
        .admin-icon-container .fas.fa-user-tie { /* Assuming fa-user-tie is the desired icon */
            font-size: 75px; /* Adjust icon size to fit well */
            color: var(--amu-primary); /* Or var(--amu-text) for white, matching your preference */
        }


        /* ===== Right Column Styles ===== */
        #templatemo_content_right {
            flex-grow: 1;
            /* background-color: #1A237E; /* MODIFIED: Navy Blue */ /* KEPT YOUR NAVY */
            background-color: var(--amu-navy-bg-medium); /* USING YOUR VARIABLE */
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
            color: var(--amu-primary); 
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

        /* Form Styles */
        .search-form {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px 20px; 
            background-color: var(--amu-navy-bg-lighter); 
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            flex-wrap: wrap; 
        }

        .form-group label {
            font-weight: 600;
            margin-right: 15px;
            min-width: 80px;
            color: var(--amu-text);
            flex-basis: 80px; 
        }

        .tcal { 
            padding: 12px 15px;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(0, 0, 0, 0.3); 
            color: white;
            font-size: 1rem;
            flex-grow: 1; 
            min-width: 180px; 
            transition: all 0.3s;
        }
        .tcal::placeholder {
            color: var(--amu-text-secondary);
            opacity: 0.7;
        }


        .tcal:focus {
            outline: none;
            border-color: var(--amu-primary);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3);
        }

        .submit-btn {
            background-color: var(--amu-teal-green); 
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            display: block;
            margin: 30px auto 0;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
        }

        .submit-btn:hover {
            background-color: var(--amu-teal-green-dark); 
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .text-center {
            text-align: center;
            color: var(--amu-text-secondary);
            margin-bottom: 20px;
            font-style: italic;
        }

        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            /* background-color: rgba(26, 35, 126, 0.85); /* MODIFIED: Footer Navy Blue */ /* KEPT YOUR NAVY */
            background-color: var(--amu-navy-bg-heavy); /* USING YOUR VARIABLE */
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

        /* ===== Responsive Adjustments (Kept as they were in your provided code) ===== */
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
                padding: 25px;
            }
            
            #login_section {
                display: flex;
                align-items: center;
                margin-bottom: 20px; 
            }
            
            #login_section_middle {
                padding: 0 20px; 
                flex-grow: 1;
                display: flex; /* Added for icon centering in this view */
                justify-content: center; /* Added for icon centering in this view */
                align-items: center; /* Added for icon centering in this view */
            }
            /* Responsive styling for the icon when in flex row with title */
             .admin-icon-container { 
                width: 120px; 
                height: 120px;
                margin-bottom: 0; /* No bottom margin when next to title */
            }
            .admin-icon-container .fas.fa-user-tie {
                font-size: 60px;
            }
            
            #login_section_title {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                padding: 20px; 
                flex-shrink: 0;
                margin-bottom: 0;
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
                flex-direction: column; 
                align-items: center;
            }
            
            #templatemo_menu li {
                margin: 5px 0; 
                width: 100%; 
            }
             #templatemo_menu a {
                justify-content: center; 
            }
            #templatemo_menu ul ul { 
                position: static;
                width: 100%;
                background-color: rgba(0,0,128,0.8); 
            }

            
            #login_section {
                flex-direction: column;
            }
            
            #login_section_title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                width: 100%; 
                 margin-bottom: 15px; 
            }
             /* Responsive styling for the icon when title is stacked above */
             #login_section_middle {
                 padding: 15px 20px; /* Ensure padding for stacked view */
             }
             .admin-icon-container {
                width: 100px; 
                height: 100px;
                 margin-bottom: 15px; /* Add margin when stacked */
            }
            .admin-icon-container .fas.fa-user-tie {
                font-size: 50px;
            }
            
            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .form-group label {
                margin-bottom: 8px;
                flex-basis: auto; 
            }
            
            .tcal {
                width: 100%;
            }
             .search-form { 
                padding: 20px 15px;
            }
            .right_column_section_title {
                font-size: 1.5rem;
            }
        }
    </style>
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
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo"> <!-- Added class for clarity -->
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="Admin.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#"><i class="fas fa-users-cog"></i> Account <i class="fas fa-caret-down"></i></a>
                    <ul>
                        <li><a href="createaccount.php"><i class="fas fa-user-plus"></i> Create account</a></li>
                        <li><a href="view.php"><i class="fas fa-user-edit"></i> Update account</a></li>
                        <li><a href="view2.php"><i class="fas fa-user-minus"></i> Delete account</a></li>
                    </ul>
                </li>
                <li><a href="aviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                <li><a href="upload1.php" class="current"><i class="fas fa-file-alt"></i> Report</a></li>
                <li><a href="changepssadmin.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo"> <!-- Added class for clarity -->
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">ADMIN PANEL</div>
                    <div id="login_section_middle">
                        <!-- THIS IS THE ONLY HTML CHANGE from your provided "View Report" page -->
                        <div class="admin-icon-container">
                            <i class="fas fa-user-tie"></i> <!-- Assuming you want fa-user-tie -->
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section_title">Generate Report by Date Range</div>
                <div class="text-center">Select a "From" and "To" date to generate the report.</div>
                
                <div class="search-form">
                    <form action="report1.php" method="post">
                        <div class="form-group">
                            <label for="dayfrom"><i class="fas fa-calendar-day"></i> From:</label>
                            <input name="dayfrom" id="dayfrom" type="text" class="tcal" required placeholder="DD-MM-YYYY">
                        </div>
                        
                        <div class="form-group">
                            <label for="dayto"><i class="fas fa-calendar-day"></i> To:</label>
                            <input name="dayto" id="dayto" type="text" class="tcal" required placeholder="DD-MM-YYYY">
                        </div>
                        
                        <button type="submit" name="Search" class="submit-btn">
                            <i class="fas fa-search"></i> Search Report
                        </button>
                    </form>
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

    <!-- Include Tigra Calendar scripts -->
    <!-- Make sure these paths are correct relative to your file location -->
    <script type="text/javascript" src="calendar/tigra_calendar/tcal.js"></script>
    <link rel="stylesheet" type="text/css" href="calendar/tigra_calendar/tcal.css" />
</body>
</html>