<?php
session_start();
require_once 'config.php';

// Initialize variables
$status_message = '';
$status_type = '';

// Clear any previous submission flags
unset($_SESSION['form_submitted']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize inputs
        $driver_id = $conn->real_escape_string(trim($_POST['Driver_id']));
        $driver_name = $conn->real_escape_string(trim($_POST['Driver_Name']));
        $mechanic_name = $conn->real_escape_string(trim($_POST['Mechanic_Name']));
        $plate_no = $conn->real_escape_string(trim($_POST['PlateNo']));
        $vehicle_type = $conn->real_escape_string(trim($_POST['Vehicle_Type']));
        $problem = $conn->real_escape_string(trim($_POST['Problem_of_vehicle']));

        // Basic validation
        if (empty($driver_id) || empty($driver_name) || empty($plate_no) || empty($problem)) {
            throw new Exception("All required fields must be filled");
        }

        // Check for existing active request with same plate number and similar problem
        $check_query = "SELECT * FROM request 
                       WHERE PlateNo = ? 
                       AND Problem_of_vehicle LIKE CONCAT('%', ?, '%')
                       AND status NOT IN ('Completed', 'Rejected')
                       AND driver_username = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("sss", $plate_no, $problem, $_SESSION['username']);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['status_message'] = "You already have a similar active request for this vehicle";
            $_SESSION['status_type'] = 'error';
        } else {
            // Insert new request
            $insert_query = "INSERT INTO request 
                           (Driver_id, Driver_Name, Mechanic_Name, PlateNo, 
                            Vehicle_Type, Problem_of_vehicle, Date, driver_username, status) 
                           VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, 'Pending')";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sssssss",
                $driver_id, $driver_name, $mechanic_name,
                $plate_no, $vehicle_type, $problem, $_SESSION['username']
            );

            if ($stmt->execute()) {
                $_SESSION['status_message'] = "Request submitted successfully!";
                $_SESSION['status_type'] = 'success';
                $_SESSION['form_submitted'] = true;
                
                // Clear POST data to prevent resubmission
                $_POST = array();
            } else {
                throw new Exception("Database error: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        $_SESSION['status_message'] = "Error: " . $e->getMessage();
        $_SESSION['status_type'] = 'error';
        error_log($e->getMessage());
    }
    
    // Redirect to prevent form resubmission
    header("Location: requestmaintenance1.php");
    exit();
}

// Retrieve status message if it exists
if (isset($_SESSION['status_message'])) {
    $status_message = $_SESSION['status_message'];
    $status_type = $_SESSION['status_type'];
    unset($_SESSION['status_message']);
    unset($_SESSION['status_type']);
}

// Set headers to prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Submit Maintenance Request | AMU Fleet Management</title>
    <meta name="keywords" content="AMU University, Fleet Management, Maintenance, Request" />
    <meta name="description" content="AMU University Fleet Management System - Maintenance Request" />
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
            --amu-warning: #ffc107;
            --amu-info: #17a2b8;

            /* Navy Blue Theme Variables ADDED/MODIFIED */
            --navy-header-footer-bg: rgba(25, 25, 112, 0.9);  /* Midnight Blue / Dark Navy */
            --navy-container-bg: rgba(40, 50, 110, 0.85); /* Slightly Lighter Navy for content containers */
            --navy-menu-dropdown-bg: rgba(25, 25, 112, 0.95); /* For dropdown menu consistency */
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
            display: flex;
            flex-direction: column;
        }

        #templatemo_top_panel {
            background-color: var(--navy-header-footer-bg); 
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
            background-color: var(--navy-menu-dropdown-bg); 
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        #templatemo_content_panel {
            padding: 40px 5%;
            min-height: calc(100vh - 160px);
            display: flex;
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            gap: 30px;
        }

        #templatemo_content_left {
            width: 300px;
            flex-shrink: 0;
        }

        #login_section {
            background-color: var(--navy-container-bg); 
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
            background-color: rgba(46, 125, 50, 0.3); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #login_section_middle {
            padding: 20px;
            text-align: center;
        }

        #login_section_middle img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.1);
            margin: 0 auto;
            display: block;
        }

        #templatemo_content_right {
            flex-grow: 1;
            background-color: var(--navy-container-bg); 
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 50px ;
        }

        .right_column_section_title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff; 
            position: relative;
            padding-bottom: 10px;
        }

        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); 
        }

        /* ADDED: Styles for the form reminder message */
        .form-reminder-message {
            padding: 10px 15px;
            margin-bottom: 25px; /* Space before other status messages or form */
            border-radius: 5px;
            background-color: rgba(255, 193, 7, 0.15); /* Light warning background */
            color: #f0ad4e; /* Warning text color - adjusted for better contrast on dark bg */
            border: 1px solid rgba(255, 193, 7, 0.4); 
            text-align: center;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .form-reminder-message i {
            font-size: 1.1em;
        }
        /* END OF ADDED Styles */


        .request-form {
            max-width: 600px;
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

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(0, 0, 0, 0.3); 
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--amu-primary);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3);
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-submit {
            background-color: var(--amu-primary);
            color: white;
        }

        .btn-submit:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
        }

        .btn-reset {
            background-color: var(--amu-danger);
            color: white;
        }

        .btn-reset:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .status-message {
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 5px;
            background-color: rgba(0,0,0,0.2); 
            color: white;
            text-align: center;
            border-left: 4px solid var(--amu-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .status-success {
            border-left-color: var(--amu-success);
        }

        .status-error {
            border-left-color: var(--amu-danger);
        }

        #templatemo_footer_panel {
            background-color: var(--navy-header-footer-bg); 
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: #4caf50;
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary);
            text-decoration: underline;
        }

        @media (max-width: 992px) {
            #templatemo_content_section {
                flex-direction: column;
            }
            
            #templatemo_content_left {
                width: 100%;
            }
            
            #login_section {
                display: flex;
                align-items: center;
            }
            
            #login_section_middle {
                padding: 20px;
            }
            
            #login_section_title {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
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
            }
            
            #templatemo_menu ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            #templatemo_menu li {
                margin: 5px;
            }
            
            #login_section {
                flex-direction: column;
            }
            
            #login_section_title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            #login_section_middle img {
                width: 150px;
                height: 150px;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
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
                <li><a href="driver.php">Home</a></li>
                <li><a href="requestmaintenance1.php" class="current">Mainten Request</a></li>
                <li><a href="#">View</a>
                    <ul>
                        <li><a href="dviewschedule.php">View Schedule</a></li>
                        <li><a href="viewmessage.php">View Messages</a></li>
                        <li><a href="view_my_requests.php">View My Requests</a></li> <!-- Corrected Link -->
                        <li><a href="exit11.php">View Permission</a></li>
                    </ul>
                </li>
                <li><a href="exitrequest.php">Request Exit</a></li>
                <li><a href="changepssdriver.php">Change Pass</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">DRIVER PORTAL</div>
                    <div id="login_section_middle">
                        <img src="Driverr.png" alt="Driver Profile">
                        <p style="margin-top: 15px; font-weight: 500;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    </div>
                </div>
            </div> 
            
            <div id="templatemo_content_right">
                <div class="right_column_section_title">
                    Submit Maintenance Request
                </div>

                <!-- ADDED: Visual reminder message -->
                <div class="form-reminder-message">
                    <i class="fas fa-exclamation-triangle"></i> Please fill this form seriously. Incorrect information can delay processing.
                </div>
                <!-- END OF ADDED: Visual reminder message -->
                
                <?php if (!empty($status_message)): ?>
                    <div class="status-message status-<?php echo $status_type; ?>">
                        <i class="fas <?php echo $status_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                        <?php echo htmlspecialchars($status_message); ?>
                    </div>
                <?php endif; ?>
                
                <form name="maintenance" method="post" action="requestmaintenance.php" class="request-form"> <!-- Changed action to requestmaintenance.php if that's your processing script -->
                    <div class="form-group">
                        <label for="Driver_id">Driver ID</label>
                        <input type="text" name="Driver_id" id="Driver_id" maxlength="50" required 
                               value="<?php echo htmlspecialchars($_POST['Driver_id'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="Driver_Name">Driver Name</label>
                        <input type="text" name="Driver_Name" id="Driver_Name" maxlength="80" required 
                               value="<?php echo htmlspecialchars($_POST['Driver_Name'] ?? ''); ?>"
                               onkeypress="return ValidateAlpha(event)">
                    </div>
                    
                    <div class="form-group">
                        <label for="Mechanic_Name">Mechanic Name</label>
                        <input type="text" name="Mechanic_Name" id="Mechanic_Name" maxlength="30" required 
                               value="<?php echo htmlspecialchars($_POST['Mechanic_Name'] ?? ''); ?>"
                               onkeypress="return ValidateAlpha(event)">
                    </div>
                    
                    <div class="form-group">
                        <label for="PlateNo">Plate Number</label>
                        <input type="text" name="PlateNo" id="PlateNo" maxlength="30" required 
                               value="<?php echo htmlspecialchars($_POST['PlateNo'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="Vehicle_Type">Vehicle Type</label>
                        <input type="text" name="Vehicle_Type" id="Vehicle_Type" maxlength="50" required 
                               value="<?php echo htmlspecialchars($_POST['Vehicle_Type'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="Problem_of_vehicle">Problem Description</label>
                        <textarea name="Problem_of_vehicle" id="Problem_of_vehicle" maxlength="1500" required><?php
                            echo htmlspecialchars($_POST['Problem_of_vehicle'] ?? '');
                        ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i> Submit Request
                        </button>
                        <button type="reset" class="btn btn-reset">
                            <i class="fas fa-undo"></i> Reset Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            Copyright © 2025 <a href="#">AMU University</a> | <a href="http://www.AMU.edu.et" target="_blank">AMU Vehicle Management Office</a>
        </div>
    </div>

    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data === 'false') {
                    window.location.href = 'index.html';
                }
            });

        function ValidateAlpha(evt) {
            var keyCode = (evt.which) ? evt.which : evt.keyCode;
            if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && keyCode != 8 && keyCode != 9) {
                alert("Only letters are allowed!");
                return false;
            }
            return true;
        }

        // Prevent form resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Auto-hide success messages after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.status-message');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // REMOVED: JavaScript alert on page load
        // document.addEventListener('DOMContentLoaded', function() {
        //     alert("please fill this form seriously");
        // });
    </script>
</body>
</html>
<?php
// Close the database connection
if (isset($conn) && $conn instanceof mysqli) { // Check if $conn is set and is a mysqli object
    $conn->close();
}
?>