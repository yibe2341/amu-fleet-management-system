<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include('config.php');

// Initialize variables
$success = false;
$error = null;

// Check if m_id is set in the URL
if (isset($_GET['m_id'])) {
    $m_id = $_GET['m_id'];

    // Validate m_id (ensure it's a number)
    if (!is_numeric($m_id)) {
        $error = "Invalid Message ID";
    } else {
        // Prepare the DELETE query using a prepared statement
        $query = "DELETE FROM message WHERE m_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $m_id);
            
            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = "Error deleting message: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Error preparing query: " . $conn->error;
        }
    }
} else {
    $error = "No Message ID provided";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Message | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
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
        }

        #templatemo_top_panel {
            background-color: rgba(0, 0, 0, 0.85);
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
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 8px;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
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
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-panel {
            display: flex;
            min-height: calc(100vh - 160px);
            padding: 20px 0;
        }

        .content-left {
            width: 250px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-section {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }

        .login-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-primary);
        }

        .login-section img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            border: 3px solid var(--amu-primary);
        }

        .content-right {
            flex: 1;
            padding: 20px 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .message-container {
            background-color: rgba(0, 0, 0, 0.75);
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            max-width: 600px;
            text-align: center;
        }

        .message-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .success {
            color: var(--amu-success);
        }

        .error {
            color: var(--amu-danger);
        }

        .message-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .message-text {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--amu-primary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #templatemo_footer_panel {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .content-panel {
                flex-direction: column;
            }
            
            .content-left {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .content-right {
                padding: 15px;
            }
            
            .message-container {
                padding: 30px;
            }
        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data === 'false') {
                    window.location.href = 'index.html';
                }
            });
    </script>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="manager.php">Home</a></li>
                <li><a href="#">Vehicle</a>
                    <ul>
                        <li><a href="vehicle-register.html">Register vehicle</a></li>
                        <li><a href="view1.php">Update vehicle</a></li>
                        <li><a href="searchvinfo.html">Search vehicles</a></li>
                    </ul>
                </li>
                <li><a href="#" class="current">View</a>
                    <ul>
                        <li><a href="mviewschedule.php">View schedule</a></li>
                        <li><a href="exitrequest1.php">View exit request</a></li>
                        <li><a href="mrequest-view.php">View maintenance request</a></li>
                        <li><a href="mmessage.php" class="current">View message</a></li>
                        <li><a href="comment12.php">View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php">Fuel</a></li>
                <li><a href="upload.php">Report</a></li>
                <li><a href="index.html">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-panel">
        <div class="content-left">
            <div class="login-section">
                <div class="login-section-title">MANAGER PORTAL</div>
                <div style="font-size: 100px; color: #2e7d32; margin: 20px 0;">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>
        
        <div class="content-right">
            <div class="message-container">
                <?php if ($success): ?>
                    <div class="message-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="message-title">Success!</h2>
                    <p class="message-text">The message has been successfully deleted.</p>
                    <a href="mmessage.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Messages
                    </a>
                <?php elseif ($error): ?>
                    <div class="message-icon error">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h2 class="message-title">Error</h2>
                    <p class="message-text"><?php echo htmlspecialchars($error); ?></p>
                    <a href="mmessage.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Messages
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            &copy; All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>