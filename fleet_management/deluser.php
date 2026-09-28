<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Include the configuration file
include('config.php'); // Ensure this path is correct

// Check database connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error); // Log error
    die('Could not connect to the database. Please try again later.'); // User-friendly message
}

// Initialize variables
$success = false;
$error = '';
$userIdToDeleteDisplay = 'N/A'; // For displaying in messages

// Check if User_id is set in the GET request
if (isset($_GET['User_id'])) {
    $userIdFromGet = trim($_GET['User_id']); // Trim whitespace immediately
    $userIdToDeleteDisplay = htmlspecialchars($userIdFromGet); // For safe display

    if (empty($userIdFromGet)) {
        $error = "User ID cannot be empty.";
    } else {
        try {
            // Prepare the SQL query with parameter binding
            // Option A: If your database/column collation is case-insensitive, this is fine:
            // $stmt = $conn->prepare("DELETE FROM User_registration WHERE User_id = ?");

            // Option B: To explicitly handle potential case issues if User_id is stored with mixed case
            // and you want to match regardless of case (e.g., 'eju' should delete 'EJU')
            // This assumes your User_id column uses a collation that allows LOWER() or UPPER() to work as expected.
            // If User_id is always stored in a specific case (e.g., always uppercase), adjust accordingly.
            $stmt = $conn->prepare("DELETE FROM User_registration WHERE LOWER(User_id) = LOWER(?)");
            // Or if you know it's always stored as uppercase:
            // $stmt = $conn->prepare("DELETE FROM User_registration WHERE User_id = UPPER(?)");
            // Or if you are sure the case passed from view2.php is ALWAYS correct and DB is case-sensitive:
            // $stmt = $conn->prepare("DELETE FROM User_registration WHERE User_id = ?");


            if ($stmt) {
                // Bind the trimmed and potentially case-adjusted value
                $param_user_id = $userIdFromGet; // For direct match
                // If using LOWER() in query:
                // $param_user_id = strtolower($userIdFromGet);
                // If using UPPER() in query:
                // $param_user_id = strtoupper($userIdFromGet);

                $stmt->bind_param("s", $param_user_id); // Use "s" for string

                // Execute the query
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $success = true;
                    } else {
                        // More detailed check: Does the user exist at all (even if case was an issue)?
                        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM User_registration WHERE LOWER(User_id) = LOWER(?)");
                        if ($checkStmt) {
                            $lowerUserId = strtolower($userIdFromGet);
                            $checkStmt->bind_param("s", $lowerUserId);
                            $checkStmt->execute();
                            $checkStmt->bind_result($count);
                            $checkStmt->fetch();
                            $checkStmt->close();
                            if ($count > 0) {
                                $error = "Could not delete user " . $userIdToDeleteDisplay . ". Possible permission issue or other constraint. User exists.";
                            } else {
                                $error = "No user found with ID " . $userIdToDeleteDisplay . " or user already deleted.";
                            }
                        } else {
                             $error = "No user found with ID " . $userIdToDeleteDisplay . " or user already deleted. (Existence check failed)";
                        }
                    }
                } else {
                    $error = "Error deleting user: " . htmlspecialchars($stmt->error);
                }
                $stmt->close();
            } else {
                $error = "Failed to prepare statement: " . htmlspecialchars($conn->error);
            }
        } catch (Exception $e) {
            $error = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
} else {
    $error = "No user ID specified for deletion.";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Delete Account Status | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, account deletion, status">
    <meta name="description" content="AMU Fleet Management System - Delete User Account Status">
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
            --amu-navy-bg-heavy: rgba(0, 0, 128, 0.85); /* Using your navy variables */
            --amu-navy-bg-medium: rgba(0, 0, 128, 0.75);
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
            display: flex; /* For centering content */
            flex-direction: column; /* For header/footer layout */
            align-items: center; /* Center content horizontally */
            justify-content: space-between; /* Push footer down */
        }

        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-heavy);
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
            width: 100%; /* Make header full width */
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
            background-color: rgba(0,0,128,0.9); /* Darker navy for dropdown */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
            z-index: 1001;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-container {
            padding: 40px 20px; /* Add horizontal padding */
            flex-grow: 1; /* Allow content to take space */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            backdrop-filter: blur(2px);
        }

        .message-container {
            background-color: var(--amu-navy-bg-medium);
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .message-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff;
        }

        .success-message {
            color: var(--amu-success);
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .success-message i {
            margin-right: 8px;
        }

        .error-message {
            color: var(--amu-danger);
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .error-message i {
            margin-right: 8px;
        }

        .action-button {
            display: inline-block;
            background-color: var(--amu-primary); /* Using primary green */
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .action-button:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
        }

        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-heavy);
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%; /* Make footer full width */
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: var(--amu-primary); /* Consistent link color */
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            #templatemo_top_panel img { display: none; }
            #site_title { margin: 10px 0 15px; font-size: 1.2rem; }
            #templatemo_menu ul { flex-direction: column; align-items: center; }
            #templatemo_menu li { margin: 5px 0; width: 100%; }
            #templatemo_menu a { justify-content: center; }
            #templatemo_menu ul ul { position: static; width: 100%; }
            .content-container { padding: 30px 3%; }
            .message-container { padding: 30px 20px; }
            .message-title { font-size: 1.5rem; }
            .success-message, .error-message { font-size: 1rem; }
            .action-button { padding: 10px 20px; font-size: 0.9rem; }
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
                <li><a href="Admin.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#" class="current"><i class="fas fa-users-cog"></i> Account <i class="fas fa-caret-down"></i></a>
                    <ul>
                        <li><a href="createaccount.php"><i class="fas fa-user-plus"></i> Create account</a></li>
                        <li><a href="view.php"><i class="fas fa-user-edit"></i> Update account</a></li>
                        <li><a href="view2.php" class="current"><i class="fas fa-user-minus"></i> Delete account</a></li>
                    </ul>
                </li>
                <li><a href="aviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                <li><a href="upload1.php"><i class="fas fa-file-alt"></i> Report</a></li>
                <li><a href="changepssadmin.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-container">
        <div class="message-container">
            <h1 class="message-title">Account Deletion Status</h1>

            <?php if ($success): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> User account (ID: <?php echo $userIdToDeleteDisplay; ?>) successfully deleted!
                </div>
            <?php else: ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; // Error is already HTML-escaped or a safe string ?>
                </div>
            <?php endif; ?>
            <a href="view2.php" class="action-button"><i class="fas fa-list-ul"></i> Back to User List</a>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            Copyright © <?php echo date("Y"); ?> <a href="#">AMU University</a> | <a href="http://www.amu.edu.et" target="_blank">AMU Vehicle Management Office</a>
        </div>
    </div>
</body>
</html>