<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Retrieve status message if it exists
$status_message = $_SESSION['status_message'] ?? '';
$status_type = $_SESSION['status_type'] ?? '';
unset($_SESSION['status_message']);
unset($_SESSION['status_type']);
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
            /* --amu-dark: #121212; */ /* Original dark, replaced by navy below */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;

            /* Navy Blue Theme Variables */
            --navy-header-footer-bg: rgba(25, 25, 112, 0.9);  /* Dark Navy for header/footer */
            --navy-container-bg: rgba(40, 50, 110, 0.85); /* Lighter Navy for main content containers */
            --navy-menu-dropdown-bg: rgba(25, 25, 112, 0.95); /* Consistent with header for dropdown */
            --navy-input-bg: rgba(255, 255, 255, 0.1); /* Light background for inputs on navy */
            --navy-input-border: rgba(255, 255, 255, 0.2);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
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
            background-color: var(--navy-header-footer-bg); /* NAVY BLUE */
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
            padding: 8px 15px; /* Original padding */
            border-radius: 4px; /* Original radius */
            transition: background-color 0.3s ease; /* Simplified transition */
            font-size: 0.9rem;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary); /* AMU Green */
        }

        #templatemo_menu ul ul { /* Submenu */
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--navy-menu-dropdown-bg); /* NAVY BLUE for dropdown */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2); /* Added subtle shadow */
            z-index: 1001;
        }
        #templatemo_menu ul ul a { /* Submenu links */
            padding: 8px 15px;
            color: #fff;
        }
        #templatemo_menu ul ul a:hover {
             background-color: var(--amu-primary);
        }


        #templatemo_menu li:hover > ul {
            display: block;
        }

        #templatemo_content_panel {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 160px); /* Header + Footer height */
            padding: 20px 5%; /* Consistent padding with header/footer */
            backdrop-filter: blur(2px); /* Subtle blur for background elements */
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            gap: 20px; /* Gap between left and right columns */
        }

        #templatemo_content_left {
            width: 250px;
            background-color: var(--navy-container-bg); /* NAVY BLUE */
            padding: 20px;
            border-radius: 8px; /* Consistent border-radius */
            box-shadow: 0 3px 10px rgba(0,0,0,0.2); /* Softer shadow */
            flex-shrink: 0; /* Prevent shrinking */
            height: fit-content; /* Adjust height to content */
        }

        #login_section {
            /* background-color: rgba(0, 0, 0, 0.5); */ /* Removed this extra background */
            border-radius: 0; /* Login section doesn't need its own radius if parent has it */
            overflow: hidden;
            text-align: center;
            padding: 0; /* Padding managed by parent or specific elements */
            margin-bottom: 0; /* Margin managed by parent */
        }

        #login_section_title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-primary); /* AMU Green title */
        }

        #login_section img {
            width: 150px; /* Slightly smaller for better fit */
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px auto; /* Center and add bottom margin */
            display: block;
            border: 3px solid var(--amu-primary);
        }
        #login_section p { /* For the welcome message */
            color: var(--amu-text-secondary);
            font-weight: 500;
        }


        #templatemo_content_right {
            flex: 1; /* Take remaining space */
            /* padding: 20px 30px; */ /* Padding is on the .right_column_section now */
        }

        .right_column_section {
            background-color: var(--navy-container-bg); /* NAVY BLUE */
            border-radius: 8px; /* Consistent border-radius */
            box-shadow: 0 3px 10px rgba(0,0,0,0.2); /* Softer shadow */
            /* backdrop-filter: blur(8px); */ /* Can be inherited or reapplied if stronger effect desired */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            margin-bottom: 20px; /* If there were multiple right sections */
        }

        .right_column_section_title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--amu-primary);
            text-align: center;
            padding-bottom: 10px; /* Space for the underline */
            position: relative;
        }
        /* Underline for the title */
        .right_column_section_title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
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
            max-width: 600px; /* Constrain form width for readability */
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
        /* Style for icon that might be next to label (if you add one manually) */
        .form-group label .fa-clock {
            margin-left: 5px;
            color: var(--amu-primary);
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 12px; /* Adjusted padding */
            border-radius: 4px;
            border: 1px solid var(--navy-input-border);
            background-color: var(--navy-input-bg);
            color: var(--amu-text);
            font-size: 0.95rem; /* Slightly smaller font for inputs */
        }
         .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--amu-primary);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.25); /* Subtle focus glow */
        }
        /* Specific styling for time inputs if needed, though browser defaults are often good */
        .form-group input[type="time"] {
            padding: 9px 12px; /* Align padding with other inputs */
            cursor: pointer;
        }


        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            text-align: center;
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
            display: inline-flex; /* For icon alignment */
            align-items: center;
            gap: 8px; /* Space between icon and text */
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
        }
        .btn-reset:hover {
            background-color: #c82333; /* Darker red */
            transform: translateY(-1px);
        }

        /* Status message styles */
        .status-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: rgba(0,0,0,0.25); /* Slightly transparent black for messages on navy */
            color: white;
            text-align: center;
            border-left: 4px solid var(--amu-primary);
            display: flex; /* For icon alignment */
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .status-message i {
            font-size: 1.2em;
        }
        
        .status-success {
            border-left-color: var(--amu-success);
        }
        
        .status-error {
            border-left-color: var(--amu-danger);
        }

        /* Clock styles */
        #clockbox {
            font: 1rem 'Segoe UI', Arial, sans-serif; /* Adjusted font */
            color: var(--amu-text-secondary);
            text-align: center;
            margin: 0 auto 20px auto; /* Center clock and add bottom margin */
            padding: 8px 12px;
            background-color: rgba(0,0,0,0.15); /* Subtle bg for clock on navy */
            border-radius: 4px;
            max-width: 300px; /* Constrain clock width */
        }

        #templatemo_footer_panel {
            background-color: var(--navy-header-footer-bg); /* NAVY BLUE */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* Pushes footer to bottom */
            width: 100%;
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0 auto;
            max-width: 1200px;
        }
        #templatemo_footer_section a {
            color: #66bb6a; /* Lighter green for footer links on navy */
            text-decoration: none;
        }
        #templatemo_footer_section a:hover {
             color: var(--amu-primary);
             text-decoration: underline;
        }

        @media (max-width: 992px) { /* Tablet */
            #templatemo_content_section {
                flex-direction: column;
                align-items: center; /* Center left column when stacked */
            }
            #templatemo_content_left {
                width: 100%;
                max-width: 450px; /* Max width for left panel on tablet */
                margin-bottom: 20px; /* Space when stacked */
                height: auto; /* Allow height to adjust */
            }
            #templatemo_content_right {
                width: 100%;
            }
             #templatemo_menu ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            #templatemo_menu li {
                margin: 5px;
            }
        }


        @media (max-width: 768px) { /* Mobile */
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            
            #templatemo_top_panel img {
                 margin-bottom: 10px; /* Space below logo */
            }
             #templatemo_top_panel img:last-of-type { /* Hide second logo on mobile if one is present */
                display: none;
            }
            
            #site_title {
                margin: 5px 0 10px 0; /* Adjusted margin */
                font-size: 1.2rem;
            }
            
            #templatemo_menu ul {
                flex-direction: column; /* Stack menu items */
                align-items: center;
            }
            #templatemo_menu li {
                margin: 5px 0;
                width: 100%;
            }
            #templatemo_menu a {
                text-align: center; /* Center text in full-width links */
                padding: 10px;
            }
            #templatemo_menu ul ul { /* Submenu on mobile */
                position: static;
                width: 100%;
                box-shadow: none;
            }
            
            #templatemo_content_panel {
                padding: 20px 15px; /* Adjust padding for mobile */
            }
            #templatemo_content_left {
                 max-width: none; /* Full width on mobile */
            }

            .right_column_section {
                padding: 20px;
            }
            .right_column_section_title {
                font-size: 1.3rem;
            }
            .form-actions {
                flex-direction: column; /* Stack buttons */
            }
            .btn {
                width: 100%;
            }
        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php') // Make sure check_login.php exists and is correctly configured
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok for check_login.php');
                }
                return response.text();
            })
            .then(data => {
                if (data.trim().toLowerCase() === 'false') { // Robust comparison
                    window.location.href = 'index.html';
                }
            })
            .catch(error => {
                console.error('Error checking login status:', error);
                // Optionally, handle this error, e.g., by redirecting or showing a message
                // window.location.href = 'login_error.html';
            });

        // Form validation
        function checkform(form) {
            if (form.Car_id.value.trim() === "") { // Use trim() for robust empty check
                alert("Please enter Car ID");
                form.Car_id.focus();
                return false;
            }
            if (form.Driver_Name.value.trim() === "") {
                alert("Please enter Driver Name");
                form.Driver_Name.focus();
                return false;
            }
            
            if (form.Start_time.value.trim() === "") {
                alert("Please enter Start Time");
                form.Start_time.focus();
                return false;
            }
            // No need for regex check if type="time", browser handles format.

            if (form.Return_time.value.trim() === "") {
                alert("Please enter Return Time");
                form.Return_time.focus();
                return false;
            }
            // No need for regex check if type="time", browser handles format.

            // Optional: Validate return time is after start time
            if (form.Start_time.value && form.Return_time.value) {
                // HTML5 time input value is in "HH:mm" format
                const startTime = form.Start_time.value;
                const returnTime = form.Return_time.value;

                if (returnTime <= startTime) { // Direct string comparison works for HH:mm
                    alert("Return time must be after start time.");
                    form.Return_time.focus();
                    return false;
                }
            }


            if (form.Reason.value.trim() === "") {
                alert("Please enter Reason for exit");
                form.Reason.focus();
                return false;
            }
            return true;
        }

        // Live clock
        function GetClock() {
            var d = new Date();
            var nhour = d.getHours(), 
                nmin = d.getMinutes(), 
                nsec = d.getSeconds(),
                ap;

            // Convert to 12-hour format and determine AM/PM
            if (nhour == 0) { ap = " AM"; nhour = 12; }
            else if (nhour < 12) { ap = " AM"; }
            else if (nhour == 12) { ap = " PM"; }
            else if (nhour > 12) { ap = " PM"; nhour = nhour - 12; }

            if (nmin <= 9) nmin = "0" + nmin;
            if (nsec <= 9) nsec = "0" + nsec;
            
            var clock = document.getElementById("clockbox");
            if (clock) { // Check if element exists
                 clock.innerHTML = "" + d.toLocaleDateString() + " | " + nhour + ":" + nmin + ":" + nsec + ap;
            }
        }
        
        window.onload = function() {
            GetClock();
            setInterval(GetClock, 1000);

            // Auto-hide status messages after a delay
            const statusMessages = document.querySelectorAll('.status-message');
            if (statusMessages.length > 0) {
                setTimeout(() => {
                    statusMessages.forEach(msg => {
                        msg.style.transition = 'opacity 0.5s ease-out';
                        msg.style.opacity = '0';
                        setTimeout(() => msg.remove(), 500); // Remove from DOM after fade
                    });
                }, 7000); // 7 seconds delay
            }
        };
    </script>
</head>
<body>
    <!-- Header Section -->
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

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">Driver Portal</div>
                    <img src="Driverr.png" alt="Driver">
                    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                </div>
                <!-- You could add other left-panel content here if needed -->
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        Request Exit Permission
                    </div>
                    <div class="right_column_section_body">

                        <!-- ADDED: Visual reminder message -->
                        <div class="form-reminder-message">
                            <i class="fas fa-exclamation-triangle"></i> Please fill this form seriously. Accurate information is crucial.
                        </div>
                        <!-- END OF ADDED: Visual reminder message -->

                        <?php if (!empty($status_message)): ?>
                            <div class="status-message status-<?php echo htmlspecialchars($status_type); ?>">
                                <i class="fas <?php echo $status_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                                <?php echo htmlspecialchars($status_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div id="clockbox"></div>
                        
                        <form name="exitForm" method="post" action="exit.php" onsubmit="return checkform(this);" class="request-form">
                            <div class="form-group">
                                <label for="Car_id">Car ID / Plate No.</label>
                                <input type="text" name="Car_id" id="Car_id" maxlength="50" required
                                       value="<?php echo htmlspecialchars($_SESSION['form_data']['Car_id'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="Driver_Name">Driver Name</label>
                                <input type="text" name="Driver_Name" id="Driver_Name" maxlength="80" required
                                       value="<?php echo htmlspecialchars($_SESSION['form_data']['Driver_Name'] ?? $_SESSION['username']); /* Pre-fill with username */ ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="Start_time">Start Time <i class="fas fa-clock"></i></label>
                                <input type="time" name="Start_time" id="Start_time" required
                                       value="<?php echo htmlspecialchars($_SESSION['form_data']['Start_time'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="Return_time">Return Time <i class="fas fa-clock"></i></label>
                                <input type="time" name="Return_time" id="Return_time" required
                                       value="<?php echo htmlspecialchars($_SESSION['form_data']['Return_time'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="Reason">Reason for Exit</label>
                                <textarea name="Reason" id="Reason" maxlength="1500" rows="4" required><?php
                                    echo htmlspecialchars($_SESSION['form_data']['Reason'] ?? '');
                                ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-submit"><i class="fas fa-paper-plane"></i> Submit Request</button>
                                <button type="reset" class="btn btn-reset"><i class="fas fa-undo"></i> Clear Form</button>
                            </div>
                        </form>
                        <?php unset($_SESSION['form_data']); // Clear form data after displaying ?>
                    </div>
                </div>
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