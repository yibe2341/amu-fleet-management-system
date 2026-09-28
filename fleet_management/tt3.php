<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include('config.php');

// Initialize variables to avoid undefined notices
$success = null;
$error = null;

// Check if Driver_id is set in the URL
if (isset($_GET['Driver_id'])) {
    $Driver_id = $_GET['Driver_id'];

    // Validate Driver_id
    if (empty($Driver_id)) {
        $error = "Driver ID is required";
    } else {
        // Sanitize the Driver_id
        $Driver_id = $conn->real_escape_string($Driver_id);

        // Prepare the DELETE query using a prepared statement
        $query = "DELETE FROM request WHERE Driver_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $Driver_id);
            
            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = "Error deleting request: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Error preparing query: " . $conn->error;
        }
    }
} else {
    $error = "No Driver ID provided";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Request | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-dark-navy: #000080; /* Navy Blue */
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
            background-color: rgba(0, 0, 128, 0.85); /* Navy Blue with opacity */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4); /* Shadow kept black for depth */
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
            background-color: rgba(0, 0, 128, 0.9); /* Navy Blue with opacity */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-panel {
            display: flex; /* This will allow content-right to expand */
            min-height: calc(100vh - 160px); /* Header and Footer height */
            padding: 20px 0; /* Vertical padding */
        }

        /* .content-left and .login-section styles removed as the element is removed */

        .content-right {
            flex: 1; /* Takes up all available space in the flex container */
            padding: 20px 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-y: auto; /* In case content is too tall */
        }

        .message-container {
            background-color: rgba(0, 0, 128, 0.75); /* Navy Blue with opacity */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); /* Shadow kept black for depth */
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%; /* Ensure it doesn't get too small on very wide screens */
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
            background-color: rgba(0, 0, 128, 0.85); /* Navy Blue with opacity */
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
                flex-direction: column; /* Stack panels if needed on smaller screens, though only one panel remains */
            }
            
            /* .content-left related media query removed */
            
            .content-right {
                padding: 20px; /* Adjust padding for smaller screens */
            }
            
            .message-container {
                padding: 30px;
            }
        }
    </style>
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
                        <li><a href="mmessage.php">View message</a></li>
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
        <!-- Left content panel removed -->
        
        <div class="content-right">
            <div class="message-container">
                <?php if (isset($success) && $success): ?>
                    <div class="message-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="message-title">Success!</h2>
                    <p class="message-text">The maintenance request has been successfully deleted.</p>
                    <a href="mrequest-view.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Requests
                    </a>
                <?php elseif (isset($error)): ?>
                    <div class="message-icon error">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h2 class="message-title">Error</h2>
                    <p class="message-text"><?php echo htmlspecialchars($error); ?></p>
                    <a href="mrequest-view.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Requests
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>