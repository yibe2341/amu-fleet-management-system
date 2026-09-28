<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// Include the configuration file
include('config.php');

// Check if m_id is set in the GET request
if (isset($_GET['m_id'])) {
    // Sanitize the input to prevent SQL injection
    $m_id = $conn->real_escape_string($_GET['m_id']);

    // Prepare the SQL query
    $sql = "DELETE FROM message WHERE m_id='$m_id'";

    // Execute the query
    if ($conn->query($sql)) {
        $success_message = "1 message successfully deleted!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMU Fleet Management System - Delete Message</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
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
            background-color: rgba(0, 0, 0, 0.85);
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .message-container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        /* Message Styles */
        .message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        .message.success {
            background-color: rgba(46, 125, 50, 0.3);
            border: 1px solid var(--amu-primary);
        }

        .message.error {
            background-color: rgba(211, 47, 47, 0.3);
            border: 1px solid #d32f2f;
        }

        /* Buttons */
        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            display: inline-block;
            text-decoration: none;
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
            background-color: rgba(0, 0, 0, 0.85);
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

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
        }
    </style>
</head>
<body>
    <header>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
        <h1 class="site-title">AMU FLEET MANAGEMENT SYSTEM</h1>
        <nav>
            <ul>
                <li><a href="scheduler.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="newsche.php"><i class="fas fa-calendar-plus"></i> Schedule</a></li>
                <li>
                    <a href="#"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="sviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="smessage.php"><i class="fas fa-envelope"></i> View Messages</a></li>
                    </ul>
                </li>
                <li><a href="searchvinfo1.php"><i class="fas fa-search"></i> Search Vehicle</a></li>
                <li><a href="changepss.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <div class="message-container">
            <?php if (isset($success_message)): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
                <a href="smessage.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Messages
                </a>
            <?php elseif (isset($error_message)): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
                <a href="smessage.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Messages
                </a>
            <?php else: ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i> No message specified for deletion.
                </div>
                <a href="smessage.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Messages
                </a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; Copyright © <?php echo date('Y'); ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a></p>
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