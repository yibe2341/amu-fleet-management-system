<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// Handle delete action if requested
if (isset($_GET['view']) && $_GET['view'] == 'delete' && isset($_GET['Plate_no'])) {
    include('config.php');
    
    $Plate_no = $conn->real_escape_string($_GET['Plate_no']);
    $delete_query = "DELETE FROM schedule WHERE Plate_no = '$Plate_no'";
    
    if ($conn->query($delete_query)) {
        $message = "Record deleted successfully";
        $message_class = "success";
    } else {
        $message = "Error deleting record: " . $conn->error;
        $message_class = "error";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>AMU Fleet Management System - View Schedule</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32;
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
            background-color: var(--amu-new-bg); /* MODIFIED */
            padding: 0.8rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            min-width: 160px;
            padding: 0.5rem 0;
            display: none;
            z-index: 1000;
        }

        li:hover > .dropdown {
            display: block;
        }

        .dropdown a {
            padding: 0.8rem 1.2rem;
            text-align: left;
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 1.5rem 5%;
            width: 100%;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Schedule Container */
        .schedule-container {
            background-color: var(--amu-new-bg); /* MODIFIED */
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            overflow-x: auto;
        }

        .schedule-container h2 {
            color: #fff; /* MODIFIED for contrast */
            margin-bottom: 1rem;
            text-align: center;
            font-size: 1.4rem;
        }

        /* Message Styles */
        .message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            text-align: center;
        }

        .message.success {
            background-color: rgba(46, 125, 50, 0.3);
            border: 1px solid var(--amu-primary);
        }

        .message.error {
            background-color: rgba(211, 47, 47, 0.3);
            border: 1px solid #d32f2f;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        th, td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            /* background-color: rgba(0, 0, 0, 0.5); /* Original Table Header BG */
            background-color: var(--amu-primary); /* Using --amu-primary for consistency with other tables */
            color: white; /* Ensure contrast */
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Action Buttons */
        .action-btn {
            display: inline-block;
            margin: 0 0.2rem;
            color: var(--amu-primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            color: var(--amu-primary-dark);
            transform: scale(1.1);
        }

        .action-btn.delete {
            color: #d32f2f;
        }

        .action-btn.delete:hover {
            color: #b71c1c;
        }

        /* Button Styles */
        .btn-container {
            text-align: center;
            margin: 1.5rem 0;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--amu-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
        }

        /* Footer */
        footer {
            background-color: var(--amu-new-bg); /* MODIFIED */
            padding: 1rem 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
        }

        footer a {
            color: var(--amu-primary);
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
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .dropdown {
                position: static;
                display: none;
                width: 100%;
            }
            
            li:hover > .dropdown {
                display: block;
            }

            .logo {
                height: 40px;
            }
            
            .schedule-container {
                margin-top: 1rem;
            }
        }

        @media (max-width: 480px) {
            main {
                padding: 1rem;
            }

            .schedule-container {
                padding: 1rem;
            }

            th, td {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
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
                <li><a href="scheduler.php"><i class="fas "></i> Home</a></li>
                <li><a href="newsche.php"><i class="fas "></i> Schedule</a></li>
                <li>
                    <a href="#"><i class="fas"></i> View <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="sviewschedule.php" class="current"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="smessage.php"><i class="fas fa-envelope"></i> View Messages</a></li>
                    </ul>
                </li>
                <li><a href="searchvinfo1.php"><i class="fas"></i> Search Vehicle</a></li>
                <li><a href="changepss.php"><i class="fas "></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas"></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <div class="container">
            <div class="schedule-container">
                <h2>The Vehicle Schedule</h2>
                
                <?php if (isset($message)): ?>
                    <div class="message <?= $message_class ?>">
                        <?= $message ?>
                    </div>
                <?php endif; ?>
                
                <?php
                include('config.php'); // Original placement

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $query = "SELECT * FROM schedule";
                $result = $conn->query($query);

                if (!$result) {
                    die("Error: " . $conn->error);
                }

                if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['Driver_ID']) ?></td>
                                    <td><?= htmlspecialchars($row['Driver_Name']) ?></td>
                                    <td><?= htmlspecialchars($row['Driver_phone_no']) ?></td>
                                    <td><?= htmlspecialchars($row['Vehicle_type']) ?></td>
                                    <td><?= htmlspecialchars($row['Plate_no']) ?></td>
                                    <td><?= htmlspecialchars($row['Place_of_start']) ?></td>
                                    <td><?= htmlspecialchars($row['Place_of_arrive']) ?></td>
                                    <td><?= htmlspecialchars($row['Service_time']) ?></td>
                                    <td><?= htmlspecialchars($row['Date']) ?></td>
                                    <td><?= htmlspecialchars($row['Outgoing_time']) ?></td>
                                    <td><?= htmlspecialchars($row['Enterance_time']) ?></td>
                                    <td><?= htmlspecialchars($row['For']) ?></td>
                                    <td>
                                        <a href="edit3.php?Driver_ID=<?= urlencode($row['Driver_ID']) ?>" 
                                           class="action-btn" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="sviewschedule.php?Plate_no=<?= urlencode($row['Plate_no']) ?>&view=delete" 
                                           class="action-btn delete" 
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this schedule?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: var(--amu-text-secondary);">No scheduled vehicles found.</p>
                <?php endif; 
                
                $conn->close(); ?>
                
                <div class="btn-container">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print Schedule
                    </button>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>© Copyright © <?= date('Y') ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Vehicle Management Office</a></p>
    </footer>

    <script>
        // Client-side session check
        document.addEventListener('DOMContentLoaded', function() {
            fetch('check_login.php')
                .then(response => response.text())
                .then(data => {
                    if (data === 'false') {
                        window.location.href = 'index.html';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>