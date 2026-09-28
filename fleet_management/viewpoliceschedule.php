<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// Include the database configuration file
include("config.php"); // Ensure this doesn't output anything
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMU Fleet Management System - View Schedule</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32; /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-new-bg: rgba(26, 35, 126, 0.85); /* Navy Blue */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--amu-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        header {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            padding: 0.8rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 15px rgba(0,0,0,0.4); /* Added for consistency */
        }

        .logo {
            height: 50px;
            width: auto;
        }

        .site-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            flex-grow: 1;
            margin: 0 1rem;
        }

        /* Navigation */
        nav ul {
            display: flex;
            list-style: none;
            gap: 0.5rem;
        }

        nav li {
            position: relative;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }

        nav a:hover, nav a.current {
            background-color: var(--amu-primary);
        }

        /* Dropdown */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 0 0 4px 4px;
            min-width: 180px; /* Consistent width */
            padding: 0.5rem 0;
            display: none;
            z-index: 1000; /* Original z-index */
        }

        li:hover > .dropdown {
            display: block;
        }

        .dropdown a {
            padding: 0.8rem 1.2rem;
            text-align: left;
            width: 100%; /* Ensure full width */
        }
        .dropdown a i { /* Space for icons in dropdown */
            margin-right: 0.5rem;
        }


        /* Main Content */
        main {
            flex: 1;
            padding: 1.5rem 5%;
            width: 100%;
            display: flex; /* To center content */
            justify-content: center;
            align-items: flex-start; /* Align content to top */
        }

        .container {
            max-width: 1200px; /* Max width for content area */
            width: 100%; /* Take available width */
            margin: 0 auto; /* Center if parent doesn't use flex for centering */
            backdrop-filter: blur(2px); /* Blur background behind content */
        }

        /* Schedule Section */
        .schedule-section {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0; /* Consistent margin */
            overflow-x: auto; /* For table responsiveness */
            box-shadow: 0 5px 25px rgba(0,0,0,0.5); /* Consistent shadow */
            border: 1px solid rgba(255,255,255,0.1); /* Consistent border */
        }

        .schedule-section h2 {
            color: #fff; /* MODIFIED: White for better contrast on navy */
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.6rem; /* Slightly larger title */
            position: relative;
            padding-bottom: 0.5rem;
        }
        .schedule-section h2::after { /* Underline for title */
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary); /* Original green */
        }


        /* Table Styles */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        .schedule-table th {
            background-color: var(--amu-primary); /* Original green */
            color: white;
            padding: 0.8rem;
            text-align: left;
            position: sticky; /* For sticky headers */
            top: 0;
            z-index: 1; /* Ensure headers are above table content */
        }

        .schedule-table td {
            padding: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .schedule-table tr:hover td { /* Applied hover to td */
            background-color: rgba(255, 255, 255, 0.08);
        }
        .schedule-table tr:nth-child(even) td { /* Subtle striping */
            background-color: rgba(255, 255, 255, 0.03);
        }
         .schedule-table tr:nth-child(even):hover td {
            background-color: rgba(255, 255, 255, 0.1);
        }


        /* Footer */
        footer {
            background-color: var(--amu-new-bg); /* MODIFIED */
            padding: 1rem 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
            margin-top: auto; /* Pushes footer to bottom */
        }

        footer a {
            color: var(--amu-primary); /* Original green */
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: var(--amu-primary-dark);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
            }
            
            .site-title {
                margin: 0.5rem 0;
                font-size: 1.2rem;
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                flex-direction: column;
                align-items: center;
            }
             nav li {
                margin: 0.3rem 0;
            }
            
            .dropdown {
                position: static;
                display: none;
                width: 100%;
                background-color: rgba(0,0,0,0.7);
            }
            
            li:hover > .dropdown, li:focus-within > .dropdown {
                display: block;
            }

            .logo {
                height: 40px;
                margin-bottom: 0.5rem;
            }
            header img.logo:last-of-type {
                display: none;
            }
            
            main {
                padding: 1rem;
            }
            .schedule-section {
                margin-top: 1rem; /* Original */
                padding: 1rem;
            }
             .schedule-section h2 {
                font-size: 1.4rem;
            }

            .schedule-table {
                /* display: block; /* Redundant if parent .schedule-section has overflow-x: auto */
                /* overflow-x: auto; /* Handled by parent */
            }
            .schedule-table th, .schedule-table td {
                 white-space: nowrap; /* Prevent text wrapping for better scroll */
            }

        }
         @media (max-width: 480px) {
            th, td { /* Further reduce padding for very small screens */
                padding: 0.5rem;
                font-size: 0.8rem; /* Slightly smaller font for table */
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
        <h1 class="site-title">AMU FLEET MANAGEMENT SYSTEM</h1>
        <nav>
            <ul>
                <li><a href="police.php"><i class="fas fa-home"></i> Home</a></li>
                <li>
                    <!-- <a href="#"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a> -->
                    <!-- <ul class="dropdown">
                        <li><a href="viewpoliceschedule.php" class="current"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="exit2.php"><i class="fas fa-sign-out-alt"></i> View Exit</a></li>
                    </ul> -->
                </li>
                <li><a href="changepsspolice.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <div class="container">
            <div class="schedule-section">
                <h2>Vehicle Schedule</h2>
                
                <?php
                // Check connection again, in case it was closed or not established by config.php
                if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
                    // Attempt to include config again or establish connection
                    // This is a fallback, ideally config.php should always establish $conn
                    if (file_exists('config.php')) {
                        include('config.php');
                    }
                    if (!isset($conn) || $conn->connect_error) {
                        die("<p style='text-align: center; color: var(--amu-danger);'>Connection failed: " . (isset($conn) ? $conn->connect_error : "Unknown error") . "</p>");
                    }
                }


                // Get results from database
                $query = "SELECT Driver_ID, Driver_Name, Driver_phone_no, Vehicle_type, Plate_no, Place_of_start, Place_of_arrive, Service_time, Date, Outgoing_time, Enterance_time, `For` FROM schedule"; // Added backticks for `For`
                $result = $conn->query($query);

                if (!$result) {
                    echo "<p style='text-align: center; color: var(--amu-danger);'>Error loading schedule: " . htmlspecialchars($conn->error) . "</p>";
                } elseif ($result->num_rows > 0) {
                    echo '<table class="schedule-table">';
                    echo '<thead>
                            <tr> 
                                <th>Driver ID</th> 
                                <th>Driver Name</th> 
                                <th>Phone No</th>
                                <th>Vehicle Type</th> 
                                <th>Plate No</th> 
                                <th>Start Place</th>
                                <th>Arrival Place</th> 
                                <th>Service Time</th> 
                                <th>Date</th> 
                                <th>Outgoing Time</th> 
                                <th>Entrance Time</th>
                                <th>For</th>
                            </tr>
                          </thead>
                          <tbody>';

                    // Loop through results
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['Driver_ID']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Driver_Name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Driver_phone_no']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Vehicle_type']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Plate_no']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Place_of_start']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Place_of_arrive']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Service_time']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Outgoing_time']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['Enterance_time']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['For']) . '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody></table>';
                } else {
                    echo '<p style="text-align: center; color: var(--amu-text-secondary);">No scheduled vehicles found.</p>';
                }

                // Close the database connection
                if (isset($conn) && $conn instanceof mysqli) {
                    $conn->close();
                }
                ?>
            </div>
        </div>
    </main>

    <footer>
        <p>© Copyright © <?php echo date('Y'); ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a></p>
    </footer>

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
</body>
</html>