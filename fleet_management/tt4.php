<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration file
include('config.php');

// Initialize variables
$success = false;
$error = null;
$first_name = null; // Keep this for potential future use or clarity, though not directly displayed

// Check if first_name is set in the URL
if (isset($_GET['first_name'])) {
    $first_name_param = $_GET['first_name']; // Use a different variable for the parameter

    // Validate first_name
    if (empty($first_name_param)) {
        $error = "First Name is required for deletion.";
    } else {
        // Prepare the DELETE query using a prepared statement
        // IMPORTANT: Deleting by ONLY first_name is highly risky if names are not unique.
        // It's much safer to delete by a unique ID (e.g., comment_id).
        // If you also have 'last_name' from the previous page's link, you should use it too:
        // $query = "DELETE FROM contact WHERE first_name = ? AND last_name = ?";
        // And then bind both: $stmt->bind_param("ss", $first_name_param, $last_name_param);

        $query = "DELETE FROM contact WHERE first_name = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $first_name_param);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $success = true;
                } else {
                    // No rows affected could mean the record was already deleted or didn't exist
                    $error = "Comment not found or already deleted.";
                }
            } else {
                $error = "Error deleting comment: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Error preparing query: " . $conn->error;
        }
    }
} else {
    $error = "No identifier provided for comment deletion.";
}

// Only close connection if it was successfully opened in config.php
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Comment | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; -- Original Dark */
            --amu-navy-base: #000080; /* Solid Navy for reference */
            --amu-navy-transparent-heavy: rgba(0, 0, 128, 0.85);
            --amu-navy-transparent-medium: rgba(0, 0, 128, 0.75);
            --amu-navy-transparent-light: rgba(0, 0, 128, 0.5);
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
            display: flex; /* For sticky footer */
            flex-direction: column; /* For sticky footer */
        }

        #templatemo_top_panel {
            background-color: var(--amu-navy-transparent-heavy); /* Changed to navy */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4); /* Shadow kept black */
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
            background-color: rgba(0, 0, 128, 0.9); /* Changed to navy */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-panel {
            flex-grow: 1; /* For sticky footer */
            display: flex;
            justify-content: center; /* Center .content-right */
            align-items: center; /* Center .content-right vertically */
            padding: 40px 20px; /* Add some padding around the centered content */
        }

        /* .content-left and .login-section styles removed */

        .content-right {
            /* flex: 1; -- No longer needed as parent .content-panel handles centering */
            /* padding: 20px 40px; -- Padding handled by .content-panel */
            width: 100%; /* Ensure it can take up space */
            max-width: 600px; /* Max width for the message container */
            display: flex; /* To use align-items on the message-container itself if needed */
            justify-content: center;
            align-items: center;
        }

        .message-container {
            background-color: var(--amu-navy-transparent-medium); /* Changed to navy */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); /* Shadow kept black */
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            width: 100%; /* Take full width of .content-right */
            text-align: center;
        }

        .message-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .message-icon.success { /* Added .success to icon div */
            color: var(--amu-success);
        }

        .message-icon.error { /* Added .error to icon div */
            color: var(--amu-danger);
        }

        .message-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            /* Color will be inherited or can be set specifically if needed */
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
        .back-btn i {
            margin-right: 8px;
        }

        #templatemo_footer_panel {
            background-color: var(--amu-navy-transparent-heavy); /* Changed to navy */
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
        }
        #templatemo_footer_section a:hover {
            text-decoration: underline;
        }


        @media (max-width: 768px) {
            /* .content-panel flex-direction might not be needed if only one child */
            .content-right {
                padding: 20px; /* Adjust padding for smaller screens */
            }
            
            .message-container {
                padding: 30px 20px; /* Adjust padding */
            }
            .message-title {
                font-size: 1.5rem;
            }
            .message-text {
                font-size: 1rem;
            }
            .back-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
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
                        <li><a href="comment12.php">View comment</a></li> <!-- Link back to comments list -->
                    </ul>
                </li>
                <li><a href="fuel.php">Fuel</a></li>
                <li><a href="upload.php">Report</a></li>
                <li><a href="changepssmanager.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-panel">
        <!-- Left content panel removed -->
        
        <div class="content-right">
            <div class="message-container">
                <?php if ($success): ?>
                    <div class="message-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="message-title success">Success!</h2>
                    <p class="message-text">The comment has been successfully deleted.</p>
                    <a href="comment12.php?deleted=true" class="back-btn"> <!-- Pass 'deleted=true' back -->
                        <i class="fas fa-arrow-left"></i> Back to Comments
                    </a>
                <?php elseif ($error): ?>
                    <div class="message-icon error">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h2 class="message-title error">Error</h2>
                    <p class="message-text"><?php echo htmlspecialchars($error); ?></p>
                    <a href="comment12.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Comments
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © <?php echo date("Y"); ?> All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>