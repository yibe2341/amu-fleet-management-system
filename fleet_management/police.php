<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// NEW: Include database configuration and fetch notification data
include('config.php'); // Ensure this establishes $conn

$today_schedule_count = 0;
$recent_schedules = [];
$today_permission_count = 0;
$recent_permissions = [];
$notification_error = "";

if ($conn) {
    // Fetch count of today's schedules
    $query_schedule_count = "SELECT COUNT(*) as count FROM schedule WHERE Date = CURDATE()";
    $result_schedule_count = $conn->query($query_schedule_count);
    if ($result_schedule_count) {
        $today_schedule_count = $result_schedule_count->fetch_assoc()['count'];
    } else {
        $notification_error .= "Error fetching schedule count: " . $conn->error . "<br>";
    }

    // Fetch recent schedules for today (e.g., latest 3)
    $query_recent_schedules = "SELECT Driver_Name, Vehicle_type, Plate_no, Outgoing_time, Place_of_arrive FROM schedule WHERE Date = CURDATE() ORDER BY Outgoing_time ASC LIMIT 3";
    $result_recent_schedules = $conn->query($query_recent_schedules);
    if ($result_recent_schedules) {
        while ($row = $result_recent_schedules->fetch_assoc()) {
            $recent_schedules[] = $row;
        }
    } else {
        $notification_error .= "Error fetching recent schedules: " . $conn->error . "<br>";
    }

    // Fetch count of today's permissions
    $query_permission_count = "SELECT COUNT(*) as count FROM permission WHERE Date = CURDATE()";
    $result_permission_count = $conn->query($query_permission_count);
    if ($result_permission_count) {
        $today_permission_count = $result_permission_count->fetch_assoc()['count'];
    } else {
        $notification_error .= "Error fetching permission count: " . $conn->error . "<br>";
    }

    // Fetch recent permissions for today (e.g., latest 3)
    // Assuming 'id' is an auto-incrementing primary key for ordering recent entries
    $query_recent_permissions = "SELECT Plate_no, Start_time, Return_time, Permission FROM permission WHERE Date = CURDATE() ORDER BY id DESC LIMIT 3";
    $result_recent_permissions = $conn->query($query_recent_permissions);
    if ($result_recent_permissions) {
        while ($row = $result_recent_permissions->fetch_assoc()) {
            $recent_permissions[] = $row;
        }
    } else {
        $notification_error .= "Error fetching recent permissions: " . $conn->error . "<br>";
    }

    // $conn->close(); // Close connection later, after all HTML is generated or at the end of the script
} else {
    $notification_error = "Database connection failed in config.php.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMU Fleet Management System - Police Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32; /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-secondary: #1565c0;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-card-bg: rgba(26, 35, 126, 0.85); /* MODIFIED: Navy Blue for cards */
            --amu-card-border: rgba(255, 255, 255, 0.1);
            --amu-header-footer-bg: rgba(26, 35, 126, 0.85); /* MODIFIED: Navy Blue for header/footer */
            /* NEW: Notification colors */
            --amu-notification-bg: rgba(46, 125, 50, 0.15); /* Subtle green for notification items */
            --amu-notification-border: rgba(46, 125, 50, 0.3);
            --amu-notification-badge-bg: #ff5252; /* Red for badge */
            --amu-notification-badge-text: white;
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
            backdrop-filter: blur(2px);
        }

        /* Header Styles */
        header {
            background-color: var(--amu-header-footer-bg); 
            padding: 0.8rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 1000;
        }

        .logo {
            height: 50px;
            width: auto;
            filter: drop-shadow(0 0 5px rgba(46, 125, 50, 0.5));
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
            text-shadow: 0 0 10px rgba(46, 125, 50, 0.7);
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
            position: relative; /* For badge positioning */
        }

        nav a:hover, nav a.current {
            background-color: var(--amu-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* NEW: Notification Badge Style for Nav Links */
        .notification-badge {
            background-color: var(--amu-notification-badge-bg);
            color: var(--amu-notification-badge-text);
            border-radius: 50%;
            padding: 0.1em 0.4em;
            font-size: 0.7rem;
            position: absolute;
            top: 0px;
            right: 0px;
            transform: translate(50%, -50%);
            min-width: 18px; /* Ensure circle shape for single digit */
            height: 18px; /* Ensure circle shape for single digit */
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
        }


        /* Dropdown */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.95); 
            border-radius: 0 0 8px 8px;
            min-width: 200px;
            padding: 0.5rem 0;
            display: none;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--amu-card-border);
            border-top: none;
        }

        li:hover > .dropdown {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown a {
            padding: 0.8rem 1.2rem;
            text-align: left;
            transition: all 0.2s ease;
        }
        .dropdown a i { 
            margin-right: 0.5rem;
        }


        .dropdown a:hover {
            background-color: var(--amu-primary-dark);
            padding-left: 1.5rem;
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 2rem 5%;
            width: 100%;
            display: flex;
            gap: 2rem;
        }

        /* Left Panel */
        .left-panel {
            width: 300px;
            background-color: var(--amu-card-bg); 
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid var(--amu-card-border);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            height: fit-content;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .profile-icon {
            font-size: 6rem;
            color: var(--amu-primary);
            margin-bottom: 1rem;
            text-shadow: 0 0 15px rgba(46, 125, 50, 0.5);
        }

        .profile-section h3 {
            color: #fff; 
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .profile-section p {
            color: var(--amu-text-secondary);
            font-size: 0.9rem;
        }

        .quick-stats { 
            margin-top: 2rem;
        }
        
        /* Right Panel */
        .right-panel {
            flex: 1;
            background-color: var(--amu-card-bg); 
            border-radius: 10px;
            padding: 2rem;
            border: 1px solid var(--amu-card-border);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
        }

        .section-title {
            color: #fff; 
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid var(--amu-card-border);
            font-size: 1.8rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100px;
            height: 3px;
            background-color: var(--amu-primary);
        }

        .welcome-content {
            line-height: 1.7;
        }

        .welcome-content p {
            margin-bottom: 1.2rem;
            font-size: 1rem;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .dashboard-card {
            background-color: rgba(0, 0, 0, 0.2); 
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid var(--amu-card-border);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none; 
            color: inherit; 
            position: relative; /* For badge positioning */
        }
        
        /* NEW: Badge style for dashboard cards */
        .dashboard-card .notification-badge {
            top: 10px; /* Adjust as needed */
            right: 10px; /* Adjust as needed */
            transform: none; /* Override nav badge positioning */
        }


        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            background-color: rgba(46, 125, 50, 0.2); 
        }

        .card-icon {
            font-size: 2rem;
            color: var(--amu-primary);
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: white;
        }

        .card-description {
            font-size: 0.9rem;
            color: var(--amu-text-secondary);
        }

        /* NEW: Notification Section Styles */
        .notifications-section {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--amu-card-border);
        }
        .notifications-section h3 {
            font-size: 1.4rem;
            color: #fff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        .notifications-section h3 i {
            margin-right: 0.7rem;
            color: var(--amu-primary);
        }
        .notification-list {
            list-style: none;
            padding: 0;
        }
        .notification-item {
            background-color: var(--amu-notification-bg);
            border: 1px solid var(--amu-notification-border);
            padding: 0.8rem 1rem;
            border-radius: 6px;
            margin-bottom: 0.8rem;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }
        .notification-item:hover {
            background-color: rgba(46, 125, 50, 0.25);
        }
        .notification-item strong {
            color: var(--amu-primary);
        }
        .notification-item .time-info {
            font-size: 0.8rem;
            color: var(--amu-text-secondary);
            display: block;
            margin-top: 0.3rem;
        }
        .no-notifications {
            color: var(--amu-text-secondary);
            text-align: center;
            padding: 1rem;
        }
        .notification-error-msg {
            color: #ff5252; /* Red for error */
            background-color: rgba(255, 82, 82, 0.1);
            border: 1px solid rgba(255, 82, 82, 0.3);
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }


        /* Footer */
        footer {
            background-color: var(--amu-header-footer-bg); 
            padding: 1.2rem 5%;
            text-align: center;
            border-top: 1px solid var(--amu-card-border);
            font-size: 0.85rem;
            margin-top: auto; 
        }

        footer a {
            color: var(--amu-primary);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        footer a:hover {
            color: var(--amu-primary-dark);
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            main {
                flex-direction: column;
            }
            
            .left-panel {
                width: 100%;
                margin-bottom: 2rem; 
            }

            .dashboard-cards {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
            }
            
            .site-title {
                margin: 0.8rem 0;
                font-size: 1.1rem;
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
                border-radius: 0 0 8px 8px;
                box-shadow: none;
                border: none;
                border-top: 1px solid var(--amu-card-border);
            }
            
            li:hover > .dropdown, li:focus-within > .dropdown {
                display: block;
            }

            .logo {
                height: 40px;
                /* display: none;  MODIFIED: Keep one logo for mobile */
                margin-bottom: 0.5rem;
            }
            header img.logo:last-of-type { 
                display: none;
            }
            .notification-badge { /* Adjust badge for mobile nav */
                font-size: 0.65rem;
                padding: 0.05em 0.35em;
                min-width: 16px;
                height: 16px;
            }


            .right-panel, .left-panel { 
                padding: 1.5rem;
            }
            main {
                padding: 1.5rem; 
            }
        }

        @media (max-width: 480px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            nav a {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .profile-section h3 {
                font-size: 1.3rem;
            }
            .notifications-section h3 {
                font-size: 1.3rem;
            }
            .notification-item {
                font-size: 0.85rem;
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
                <li><a href="police.php" class="current"><i class="fas fa-home"></i> Home</a></li>
                <li>
                    <!-- MODIFIED: Placeholder for potential future 'View' dropdown if uncommented -->
                    <!-- <a href="#"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a> -->
                    <!-- <ul class="dropdown">
                        <li>
                            <a href="viewpoliceschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule 
                                <?php // if ($today_schedule_count > 0): ?>
                                    <span class="notification-badge"><?php // echo $today_schedule_count; ?></span>
                                <?php // endif; ?>
                            </a>
                        </li>
                        <li>
                            <a href="exit2.php"><i class="fas fa-sign-out-alt"></i> View Exit Permissions
                                <?php // if ($today_permission_count > 0): ?>
                                    <span class="notification-badge"><?php // echo $today_permission_count; ?></span>
                                <?php // endif; ?>
                            </a>
                        </li>
                    </ul> -->
                </li>
                <li><a href="changepsspolice.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <div class="left-panel">
            <div class="profile-section">
                <div class="profile-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3>Police Dashboard</h3>
                <p>AMU Fleet Management System</p>
            </div>

            <div class="quick-stats">
                <!-- .stat-item divs removed -->
            </div>
        </div>

        <div class="right-panel">
            <h2 class="section-title">Welcome to the Police Dashboard</h2>
            
            <div class="welcome-content">
                <p>As part of AMU's security team, this dashboard provides you with tools to monitor and manage vehicle movements within the university premises. You can view scheduled vehicle movements, approve exit requests, and ensure compliance with university transportation policies.</p>
        

                <div class="dashboard-cards">
                    <a href="viewpoliceschedule.php" class="dashboard-card">
                        <div class="card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="card-title">View Schedule</h3>
                        <p class="card-description">Check all scheduled vehicle movements for today and upcoming days.</p>
                        <?php if ($today_schedule_count > 0): ?>
                            <span class="notification-badge"><?php echo $today_schedule_count; ?></span>
                        <?php endif; ?>
                    </a>

                    <a href="exit2.php" class="dashboard-card">
                        <div class="card-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <h3 class="card-title">Exit Permissions</h3>
                        <p class="card-description">Review vehicle exit permissions and logs.</p>
                         <?php if ($today_permission_count > 0): ?>
                            <span class="notification-badge"><?php echo $today_permission_count; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- NEW: Notifications Section -->
            <div class="notifications-section">
                <h3><i class="fas fa-bell"></i>Recent Activity (Today)</h3>
                <?php if (!empty($notification_error)): ?>
                    <div class="notification-error-msg"><?php echo $notification_error; ?></div>
                <?php endif; ?>

                <?php if (empty($recent_schedules) && empty($recent_permissions) && empty($notification_error)): ?>
                    <p class="no-notifications">No new schedules or exit permissions recorded for today.</p>
                <?php else: ?>
                    <ul class="notification-list">
                        <?php if (!empty($recent_schedules)): ?>
                            <?php foreach ($recent_schedules as $schedule): ?>
                                <li class="notification-item">
                                    <strong>New Schedule:</strong> <?php echo htmlspecialchars($schedule['Driver_Name']); ?> - <?php echo htmlspecialchars($schedule['Vehicle_type']); ?> (<?php echo htmlspecialchars($schedule['Plate_no']); ?>) to <?php echo htmlspecialchars($schedule['Place_of_arrive']); ?>.
                                    <span class="time-info">Outgoing: <?php echo htmlspecialchars($schedule['Outgoing_time']); ?></span>
                                </li>
                            <?php endforeach; ?>
                             <?php if ($today_schedule_count > count($recent_schedules)): ?>
                                <li class="notification-item">
                                    <a href="viewpoliceschedule.php" style="color: var(--amu-primary); text-decoration: none;">... and <?php echo ($today_schedule_count - count($recent_schedules)); ?> more schedules today. View all »</a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (!empty($recent_permissions)): ?>
                            <?php foreach ($recent_permissions as $permission): ?>
                                <li class="notification-item">
                                    <strong>Exit Permission Logged:</strong> Plate No <?php echo htmlspecialchars($permission['Plate_no']); ?> - <?php echo htmlspecialchars($permission['Permission']); ?>.
                                    <span class="time-info">Start: <?php echo htmlspecialchars($permission['Start_time']); ?>, Return: <?php echo htmlspecialchars($permission['Return_time']); ?></span>
                                </li>
                            <?php endforeach; ?>
                            <?php if ($today_permission_count > count($recent_permissions)): ?>
                                <li class="notification-item">
                                     <a href="exit2.php" style="color: var(--amu-primary); text-decoration: none;">... and <?php echo ($today_permission_count - count($recent_permissions)); ?> more permissions today. View all »</a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <footer>
        <p>Copyright © <?php echo date('Y'); ?> <a href="#">AMU University</a> | <a href="http://www.amu.edu.et" target="_parent">AMU Security & Fleet Management</a></p>
    </footer>

    <?php
        // NEW: Close database connection if it was opened
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
    ?>
    <script>
        // Client-side session check (optional) - kept original commented out script
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

            // Add active class to current page link
            const currentPage = window.location.pathname.split('/').pop();
            if (currentPage === "police.php" || currentPage === "") { 
                 document.querySelector('nav a[href="police.php"]').classList.add('current');
            } else {
                document.querySelectorAll('nav a').forEach(link => {
                    if (link.getAttribute('href') === currentPage) {
                        link.classList.add('current');
                    }
                });
            }
        });
        */
    </script>
</body>
</html>