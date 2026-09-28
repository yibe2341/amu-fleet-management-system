<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
// Note: The actual password change logic is expected in changepassword1.php
// This page (changepssdriver.php) is for displaying the form.
// No database connection is needed directly on this page if it only shows the form.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | AMU Fleet System</title>
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
            margin: 0;
            padding: 0;
        }

        body {
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            color: var(--amu-text);
            min-height: 100vh;
            line-height: 1.6;
            display: flex;
            flex-direction: column; /* For sticky footer */
        }

        #templatemo_top_panel {
            background-color: var(--navy-header-footer-bg); /* NAVY BLUE */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        #templatemo_top_panel img {
            height: 50px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
        }

        #site_title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex-grow: 1;
            margin: 0 15px;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 5px;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            font-size: 0.9rem;
            white-space: nowrap;
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
            background-color: var(--navy-menu-dropdown-bg); /* NAVY BLUE */
            border-radius: 0 0 4px 4px;
            width: 190px;
            padding: 5px 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.25);
            z-index: 1001;
        }
        #templatemo_menu ul ul a {
            padding: 8px 15px;
            display: block;
            white-space: normal;
        }


        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* Main content panel to center the form */
        #templatemo_content_panel {
            display: flex;
            justify-content: center; /* Center the form container horizontally */
            align-items: center;    /* Center the form container vertically */
            flex: 1;                /* Take up remaining vertical space */
            padding: 30px 15px;     /* Padding around the form container */
            backdrop-filter: blur(2px);
        }
        
        /* This div is commented out in HTML, but if used, it would also be centered */
        /* #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px; 
            margin: 0 auto;
        } */

        /* The left panel is commented out in your HTML, so these styles won't apply unless uncommented */
        /* #templatemo_content_left {
            width: 250px;
            background-color: var(--navy-container-bg); 
            padding: 20px;
            border-radius: 8px;
        } */

        /* #login_section {
            text-align: center;
        }
        #login_section_title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-primary);
        }
        #login_section img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px auto;
            border: 3px solid var(--amu-primary);
        } */


        /* This div is commented out in your HTML, if it were the main wrapper for the form: */
        /* #templatemo_content_right {
            flex: 1;
        } */

        .right_column_section { /* This is the main form container now */
            background-color: var(--navy-container-bg); /* NAVY BLUE */
            border-radius: 10px;
            padding: 30px 40px; /* Increased padding for better spacing */
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.15);
            width: 100%;
            max-width: 550px; /* Max width for the change password form */
        }

        .right_column_section_title {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--amu-primary);
            text-align: center;
            padding-bottom: 10px;
            position: relative;
        }
        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        .password-form {
            /* max-width: 500px; -- controlled by .right_column_section */
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

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid var(--navy-input-border);
            background-color: var(--navy-input-bg);
            color: var(--amu-text);
            font-size: 0.95rem;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: var(--amu-primary);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.25);
        }


        .form-note {
            color: var(--amu-text-secondary);
            font-size: 0.85rem; /* Slightly smaller */
            margin-top: 5px;
            display: block; /* Ensure it takes its own line */
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
            /* margin-left: 15px; -- handled by gap */
        }
         .btn-reset:hover {
            background-color: #b22222;
            transform: translateY(-1px);
        }

        /* Status message styling (if you add PHP to display messages on this page) */
        .status-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: white;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .status-message.status-success {
            background-color: rgba(40, 167, 69, 0.25); /* Success green tint */
            border-left: 4px solid var(--amu-success);
        }
        .status-message.status-error {
            background-color: rgba(220, 53, 69, 0.25); /* Danger red tint */
            border-left: 4px solid var(--amu-danger);
        }
         .status-message i {
            font-size: 1.2rem;
        }


        #templatemo_footer_panel {
            background-color: var(--navy-header-footer-bg); /* NAVY BLUE */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* For sticky footer */
        }
        #templatemo_footer_section {
            color: rgba(255,255,255,0.75);
        }
        #templatemo_footer_section a {
            color: #81c784;
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
                flex-direction: column;
                align-items: center;
            }
            #templatemo_menu li {
                margin: 4px 0;
                width: 100%;
            }
             #templatemo_menu a {
                display: block;
                text-align: center;
            }
            #templatemo_menu ul ul {
                position: static;
                width: 100%;
                box-shadow: none;
            }
            
            #templatemo_content_panel {
                padding: 20px 15px;
                align-items: stretch; /* Allow form to take height if content is short */
            }
            .right_column_section {
                padding: 20px;
                max-width: 100%;
                margin: auto 0; /* Center vertically if align-items:center on parent */
            }
            .right_column_section_title {
                font-size: 1.4rem;
            }
            .form-actions {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
   <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Client-side session check
        fetch('check_login.php')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok.');
                return response.text();
            })
            .then(data => {
                if (data.trim().toLowerCase() === 'false') {
                    window.location.href = 'index.html';
                }
            })
            .catch(error => console.error('Error checking login status:', error));

        // Live feedback for New Password field
        const newPasswordField = document.getElementById('newPassword');
        const newPasswordFeedbackDiv = document.getElementById('newPasswordFeedback');

        if (newPasswordField && newPasswordFeedbackDiv) {
            newPasswordField.addEventListener('input', function() {
                const password = this.value;
                let messages = [];
                const minLength = 8;

                if (password.length === 0) {
                    newPasswordFeedbackDiv.innerHTML = 'Password must be at least 8 characters, including letters and numbers.';
                    newPasswordFeedbackDiv.style.color = 'var(--amu-text-secondary)'; // Reset to default color
                    return;
                }

                // 1. Minimum length
                if (password.length >= minLength) {
                    messages.push('<span style="color: var(--amu-success, #28a745);">✓ At least ' + minLength + ' characters</span>');
                } else {
                    messages.push('<span style="color: var(--amu-danger, #dc3545);">✗ At least ' + minLength + ' characters</span>');
                }

                // 2. Contains a letter
                if (/[a-zA-Z]/.test(password)) {
                    messages.push('<span style="color: var(--amu-success, #28a745);">✓ Contains a letter (a-z, A-Z)</span>');
                } else {
                    messages.push('<span style="color: var(--amu-danger, #dc3545);">✗ Contains a letter (a-z, A-Z)</span>');
                }

                // 3. Contains a number
                if (/[0-9]/.test(password)) {
                    messages.push('<span style="color: var(--amu-success, #28a745);">✓ Contains a number (0-9)</span>');
                } else {
                    messages.push('<span style="color: var(--amu-danger, #dc3545);">✗ Contains a number (0-9)</span>');
                }

                newPasswordFeedbackDiv.innerHTML = messages.join('<br>');
            });
        }

        // Add auto-hide for status messages if you add PHP to display them on this page
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


    function ValidateAlpha(evt) {
        var keyCode = (evt.which) ? evt.which : evt.keyCode;
        // Allow letters, space, backspace, tab
        if (!((keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || keyCode == 32 || keyCode == 8 || keyCode == 9)) {
            alert("Only letters and spaces are allowed for username!");
            return false;
        }
        return true;
    }

    function checkform(form) {
        const userName = form.UserName.value.trim();
        const currentPassword = form.Password.value; // Don't trim passwords
        const newPassword = form.newPassword.value;
        const confirmPassword = form.ConfrimPassword.value;
        const minPasswordLength = 8;

        if (userName === "") {
            alert("Please fill Username");
            form.UserName.focus();
            return false;
        }
        if (currentPassword === "") {
            alert("Please fill Current Password");
            form.Password.focus();
            return false;
        }

        // New Password Validations
        if (newPassword === '') {
            alert("Please enter a New Password.");
            form.newPassword.focus();
            return false;
        }
        if (newPassword.length < minPasswordLength) {
            alert("New Password is too short! It must contain at least " + minPasswordLength + " characters.");
            form.newPassword.focus();
            return false;
        }
        if (!/[a-zA-Z]/.test(newPassword)) { // Check for at least one letter
            alert("New Password must contain at least one letter (a-z or A-Z).");
            form.newPassword.focus();
            return false;
        }
        if (!/[0-9]/.test(newPassword)) { // Check for at least one number
            alert("New Password must contain at least one number (0-9).");
            form.newPassword.focus();
            return false;
        }
        // End of New Password Validations

        if (confirmPassword === '') {
            alert("Please confirm your New Password.");
            form.ConfrimPassword.focus();
            return false;
        }
        else if (newPassword !== confirmPassword) {
            alert("New Password and Confirm Password do not match.");
            form.ConfrimPassword.value = ""; // Clear confirm password field
            form.ConfrimPassword.focus();
            return false;
        }
        return true;
    }
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
                <li><a href="exitrequest.php">Request Exit</a></li>
                <li><a href="changepssdriver.php" class="current">Change Pass</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <div id="templatemo_content_panel">
        <!-- The left panel is commented out in your HTML -->
        <!-- If #templatemo_content_right was meant to be the direct child for centering, it should be here -->
        <!-- <div id="templatemo_content_right"> -->
            <div class="right_column_section"> <!-- This is the actual form container -->
                <div class="right_column_section_title">
                    Change Password
                </div>
                <div class="right_column_section_body">
                    <!-- If changepassword1.php redirects back with status messages, display them here -->
                    <?php
                        if (isset($_SESSION['change_pass_status_message'])) {
                            $message = $_SESSION['change_pass_status_message'];
                            $type = $_SESSION['change_pass_status_type'] ?? 'info';
                            echo "<div class='status-message status-{$type}'><i class='fas ".($type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle')."'></i> {$message}</div>";
                            unset($_SESSION['change_pass_status_message']);
                            unset($_SESSION['change_pass_status_type']);
                        }
                    ?>
                    <form name="htmlform" method="post" action="changepassword1.php" onsubmit="return checkform(this);" class="password-form">
                        <div class="form-group">
                            <label for="UserName">Username</label>
                            <input type="text" id="UserName" name="UserName" maxlength="50" placeholder="Enter your username" onkeypress="return ValidateAlpha(event);" required 
                                   value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); /* Pre-fill username */ ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="Password">Current Password</label>
                            <input type="password" id="Password" name="Password" maxlength="50" placeholder="Enter current password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" id="newPassword" name="newPassword" maxlength="80" placeholder="Enter new password" required>
                            <p class="form-note">Password must be at least 8 characters long.</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="ConfrimPassword">Confirm New Password</label>
                            <input type="password" id="ConfrimPassword" name="ConfrimPassword" maxlength="80" placeholder="Confirm new password" required>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-submit"><i class="fas fa-key"></i> Change Password</button>
                            <button type="reset" class="btn btn-reset"><i class="fas fa-undo"></i> Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        <!-- </div> --> <!-- Closing for #templatemo_content_right if it was used -->
    </div>

    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>