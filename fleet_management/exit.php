<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// Database connection and form processing
$conn = new mysqli("localhost", "fleet", "11111111", "fleet"); // Ensure these are your correct credentials
$status_message = '';
$status_type = '';

// Check connection - good practice to add this
if ($conn->connect_error) {
    // Log error and display a generic message to the user, or handle appropriately
    error_log("Database connection failed: " . $conn->connect_error);
    // For a user-facing page, you might not want to die() here
    // but set a status message and prevent form processing.
    // For simplicity in this context, we'll proceed, but in production, handle this.
    $status_message = "A database connection error occurred. Please try again later.";
    $status_type = "error";
    // exit(); // Or prevent further database operations
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && !$conn->connect_error) { // Only process if DB connection is okay
    // Sanitize and validate input data
    $Car_id = $conn->real_escape_string($_POST['Car_id']);
    $Driver_Name = $conn->real_escape_string($_POST['Driver_Name']);
    $Start_time = $conn->real_escape_string($_POST['Start_time']);
    $Return_time = $conn->real_escape_string($_POST['Return_time']);
    $Date = date('d-m-Y'); // Current date
    $Reason = $conn->real_escape_string($_POST['Reason']);

    // Basic validation on server-side too
    if (empty($Car_id) || empty($Driver_Name) || empty($Start_time) || empty($Return_time) || empty($Reason)) {
        $status_message = "All fields are required.";
        $status_type = "error";
    } else {
        // Check if the request already exists (consider adding date to this check if appropriate)
        $query = "SELECT * FROM exit1 WHERE Car_id = ? AND Reason = ? AND Date = ?"; // Added Date to make it more specific
        $stmt_check = $conn->prepare($query); // Use different variable for check statement
        if ($stmt_check) {
            $stmt_check->bind_param("sss", $Car_id, $Reason, $Date);
            $stmt_check->execute();
            $result = $stmt_check->get_result();
            $count = $result->num_rows;
            $stmt_check->close(); // Close check statement

            if ($count != 0) {
                $status_message = "Sorry! A similar request for this car and reason on this date has already been sent.";
                $status_type = "error";
            } else {
                // Insert the new request
                $sql = "INSERT INTO exit1 (Car_id, Driver_Name, Start_time, Return_time, Date, Reason) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql); // Use different variable for insert statement
                if ($stmt_insert) {
                    $stmt_insert->bind_param("ssssss", $Car_id, $Driver_Name, $Start_time, $Return_time, $Date, $Reason);

                    if ($stmt_insert->execute()) {
                        $status_message = "Request sent successfully.";
                        $status_type = "success";
                        $_POST = array(); // Clear POST data to prevent repopulating form on refresh (if not redirecting)
                    } else {
                        $status_message = "Error sending request: " . $stmt_insert->error;
                        $status_type = "error";
                        error_log("Exit request insert error: " . $stmt_insert->error); // Log specific DB error
                    }
                    $stmt_insert->close(); // Close insert statement
                } else {
                    $status_message = "Error preparing statement for insert: " . $conn->error;
                    $status_type = "error";
                    error_log("Exit request prepare insert error: " . $conn->error);
                }
            }
        } else {
            $status_message = "Error preparing statement for check: " . $conn->error;
            $status_type = "error";
            error_log("Exit request prepare check error: " . $conn->error);
        }
    }
}

// Close the connection if it was successfully opened
if (!$conn->connect_error) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Exit Permission | AMU Fleet System</title>
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

            /* Navy Blue Theme Variables (will apply these as requested) */
            --navy-header-footer-bg: rgba(25, 25, 112, 0.9);
            --navy-container-bg: rgba(40, 50, 110, 0.85);
            --navy-menu-dropdown-bg: rgba(25, 25, 112, 0.95);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            /* overflow-x: hidden; -- Removed, can cause issues with sticky footers if not careful */
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            color: var(--amu-text);
            min-height: 100vh;
            line-height: 1.6;
            display: flex; /* Added for footer */
            flex-direction: column; /* Added for footer */
        }

        #templatemo_top_panel {
            background-color: var(--navy-header-footer-bg); /* UPDATED TO NAVY BLUE */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3); /* Softer shadow */
        }

        #templatemo_top_panel img {
            height: 50px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0; /* Prevent logo from shrinking too much */
        }

        #site_title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex-grow: 1; /* Allow title to take space */
            margin: 0 15px; /* Add some horizontal margin */
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 5px; /* Slightly reduced margin */
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 12px; /* Slightly adjusted padding */
            border-radius: 4px;
            transition: background-color 0.3s ease;
            font-size: 0.9rem;
            white-space: nowrap; /* Prevent menu items from wrapping */
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
            background-color: var(--navy-menu-dropdown-bg); /* UPDATED TO NAVY BLUE */
            border-radius: 0 0 4px 4px;
            width: 190px; /* Slightly wider for potentially longer submenu items */
            padding: 5px 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.25);
            z-index: 1001; /* Ensure dropdown is on top */
        }
         #templatemo_menu ul ul a {
            padding: 8px 15px; /* Ensure enough padding */
            display: block; /* Make submenu links take full width */
            white-space: normal; /* Allow submenu text to wrap if needed */
        }


        #templatemo_menu li:hover > ul {
            display: block;
        }

        #templatemo_content_panel {
            display: flex;
            justify-content: center; /* Center the single content block */
            align-items: flex-start; /* Align to top, can change to center if preferred */
            flex: 1; /* Takes remaining vertical space */
            min-height: calc(100vh - 160px); /* Header + Footer height */
            padding: 30px 15px; /* Padding around the content area */
            backdrop-filter: blur(2px); /* Apply blur to what's behind */
        }

        /* REMOVED: #templatemo_content_section and #templatemo_content_left styles */

        #templatemo_content_right { /* This will be the main form container */
            flex: 0 1 auto; /* Don't grow, shrink if needed, base on auto size */
            width: 100%;    /* Take full width of its parent (which is centered and padded) */
            max-width: 700px; /* Max width for the form container itself */
            /* padding: 0; */ /* Padding will be on .right_column_section */
        }

        .right_column_section {
            background-color: var(--navy-container-bg); /* UPDATED TO NAVY BLUE */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.15); /* Slightly more visible border */
            padding: 30px;
            margin-bottom: 20px; /* In case you add more sections later */
        }

        .right_column_section_title {
            font-size: 1.6rem; /* Slightly adjusted */
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--amu-primary);
            text-align: center;
            padding-bottom: 10px;
            position: relative;
        }
        .right_column_section_title::after { /* Underline for title */
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        .request-form {
            /* max-width: 600px; -- Removed, parent .right_column_section controls width */
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--amu-text);
        }
         .form-group label .fa-clock { /* Style for clock icon */
            margin-left: 5px;
            color: var(--amu-primary);
        }


        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--amu-text);
            font-size: 0.95rem;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--amu-primary);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.25);
        }
        /* Specific styling for time inputs if using type="time" */
        .form-group input[type="time"] {
            padding: 9px 12px; /* May need adjustment for vertical alignment */
            cursor: pointer;
        }


        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            text-align: center;
            margin-top: 30px;
            display: flex; /* For button alignment */
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s, transform 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit {
            background-color: var(--amu-primary);
            color: white;
        }
        .btn-submit:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-1px);
        }

        .btn-reset {
            background-color: var(--amu-danger);
            color: white;
            /* margin-left: 15px; -- Handled by gap */
        }
        .btn-reset:hover {
            background-color: #b22222; /* Darker red for reset hover */
            transform: translateY(-1px);
        }


        .status-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px; /* Slightly more rounded */
            background-color: rgba(0,0,0,0.3); /* Darker bg for message on navy */
            color: white;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-left: 4px solid var(--amu-primary); /* Default border color */
        }
        .status-message i {
            font-size: 1.2rem; /* Icon size */
        }
        
        .status-success {
            border-left-color: var(--amu-success);
            /* background-color: rgba(40, 167, 69, 0.2); */ /* Optional: subtle bg tint for success */
        }
        
        .status-error {
            border-left-color: var(--amu-danger);
            /* background-color: rgba(220, 53, 69, 0.2); */ /* Optional: subtle bg tint for error */
        }


        #templatemo_footer_panel {
            background-color: var(--navy-header-footer-bg); /* UPDATED TO NAVY BLUE */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* Ensures footer is at the bottom with flex body */
        }
        #templatemo_footer_section {
            color: rgba(255,255,255,0.75); /* Slightly brighter footer text */
        }
        #templatemo_footer_section a {
            color: #81c784; /* Lighter green for footer links */
            text-decoration: none;
        }
        #templatemo_footer_section a:hover {
            color: var(--amu-primary);
            text-decoration: underline;
        }


        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px 5%;
            }
            #templatemo_top_panel img {
                 margin-bottom: 10px;
            }
            #site_title {
                font-size: 1.2rem;
                margin-bottom: 10px;
            }
            #templatemo_menu ul {
                flex-direction: column; /* Stack menu items */
                align-items: center; /* Center them */
            }
            #templatemo_menu li {
                margin: 4px 0; /* Adjust margin for stacked items */
                width: 100%; /* Make list items full width for easier tapping */
            }
             #templatemo_menu a {
                display: block; /* Make links take full width of li */
                text-align: center; /* Center text in links */
            }
            #templatemo_menu ul ul { /* Submenu on mobile */
                position: static; /* No absolute positioning */
                width: 100%;
                box-shadow: none; /* Remove shadow for static submenu */
            }
            
            #templatemo_content_panel {
                padding: 20px 15px; /* Reduce padding on mobile */
            }
            #templatemo_content_right {
                /* padding: 0; -- padding is on .right_column_section */
                max-width: 100%; /* Allow form container to be full width */
            }
            .right_column_section {
                padding: 20px; /* Reduce padding inside form container */
            }
            .right_column_section_title {
                font-size: 1.4rem;
            }
            .form-actions {
                flex-direction: column; /* Stack buttons */
            }
            .btn {
                width: 100%; /* Full width buttons */
            }
        }
    </style>
    <script>
        // Client-side session check (Ensure check_login.php is robust)
        fetch('check_login.php')
            .then(response => {
                if (!response.ok) { // Check for network errors
                    throw new Error('Network response was not ok for check_login.php');
                }
                return response.text();
            })
            .then(data => {
                if (data.trim().toLowerCase() === 'false') { // Trim and case-insensitive compare
                    window.location.href = 'index.html';
                }
            })
            .catch(error => {
                console.error('Error checking login status:', error);
                // Potentially redirect to an error page or show a non-intrusive error
            });

        function validateForm() {
            const carId = document.forms["exitForm"]["Car_id"].value.trim();
            const driverName = document.forms["exitForm"]["Driver_Name"].value.trim();
            const startTime = document.forms["exitForm"]["Start_time"].value.trim();
            const returnTime = document.forms["exitForm"]["Return_time"].value.trim();
            const reason = document.forms["exitForm"]["Reason"].value.trim();

            if (carId === "") {
                alert("Please enter Car ID / Plate No.");
                document.forms["exitForm"]["Car_id"].focus();
                return false;
            }
            if (driverName === "") {
                alert("Please enter Driver Name.");
                document.forms["exitForm"]["Driver_Name"].focus();
                return false;
            }

            // HTML5 type="time" will provide value in "HH:mm" format
            if (startTime === "") {
                alert("Please enter Start Time.");
                document.forms["exitForm"]["Start_time"].focus();
                return false;
            }
            if (returnTime === "") {
                alert("Please enter Return Time.");
                document.forms["exitForm"]["Return_time"].focus();
                return false;
            }

            // Compare times (simple string comparison works for HH:mm format)
            if (startTime && returnTime && returnTime <= startTime) {
                alert("Return Time must be after Start Time.");
                document.forms["exitForm"]["Return_time"].focus();
                return false;
            }

            if (reason === "") {
                alert("Please enter the Reason for exit.");
                document.forms["exitForm"]["Reason"].focus();
                return false;
            }
            return true;
        }
         // Auto-hide status messages
        window.addEventListener('load', function() {
            const statusMessages = document.querySelectorAll('.status-message');
            if (statusMessages.length > 0) {
                setTimeout(() => {
                    statusMessages.forEach(msg => {
                        msg.style.transition = 'opacity 0.5s ease-out';
                        msg.style.opacity = '0';
                        setTimeout(() => msg.remove(), 500);
                    });
                }, 5000); // Hide after 5 seconds
            }
        });
    </script>
</head>
<body>
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="driver.php">Home</a></li>
                <li><a href="requestmaintenance1.php">Request Mainten</a></li>
                <li><a href="#">View</a>
                    <ul>
                        <li><a href="dviewschedule.php">View Schedule</a></li>
                        <li><a href="viewmessage.php">View Messages</a></li>
                        <li><a href="exit11.php">View Permission</a></li>
                    </ul>
                </li>
                <li><a href="exitrequest.php" class="current">Request Exit</a></li>
                <li><a href="changepssdriver.php">Change Pass</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <div id="templatemo_content_panel">
        <!-- NO #templatemo_content_section or #templatemo_content_left -->
            
        <div id="templatemo_content_right"> <!-- This is now the main centered form container -->
            <div class="right_column_section">
                <div class="right_column_section_title">
                    Request Exit Permission
                </div>
                <div class="right_column_section_body">
                    <?php if (!empty($status_message)): ?>
                        <div class="status-message status-<?php echo htmlspecialchars($status_type); ?>">
                             <i class="fas <?php echo $status_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                            <?php echo htmlspecialchars($status_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form name="exitForm" method="post" action="exitrequest.php" onsubmit="return validateForm()" class="request-form">
                        <div class="form-group">
                            <label for="Car_id">Car ID / Plate No.</label>
                            <input type="text" id="Car_id" name="Car_id" maxlength="50" required value="<?php echo htmlspecialchars($_POST['Car_id'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="Driver_Name">Driver Name</label>
                            <input type="text" id="Driver_Name" name="Driver_Name" maxlength="80" required value="<?php echo htmlspecialchars($_POST['Driver_Name'] ?? $_SESSION['username']); /* Pre-fill with username */ ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="Start_time">Start Time <i class="fas fa-clock"></i></label>
                            <input type="time" id="Start_time" name="Start_time" required value="<?php echo htmlspecialchars($_POST['Start_time'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="Return_time">Return Time <i class="fas fa-clock"></i></label>
                            <input type="time" id="Return_time" name="Return_time" required value="<?php echo htmlspecialchars($_POST['Return_time'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="Reason">Reason for Exit</label>
                            <textarea id="Reason" name="Reason" maxlength="1500" rows="4" required><?php echo htmlspecialchars($_POST['Reason'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-submit"><i class="fas fa-paper-plane"></i> Submit Request</button>
                            <button type="reset" class="btn btn-reset"><i class="fas fa-undo"></i> Clear Form</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>