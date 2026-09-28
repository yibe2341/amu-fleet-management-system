<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Change Password | AMU Fleet System</title>
    <meta name="keywords" content="AMU University, Fleet Management, Change Password" />
    <meta name="description" content="AMU University Fleet Management System - Change Password" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
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
            display: flex; /* Added for footer */
            flex-direction: column; /* Added for footer */
        }

        #templatemo_top_panel {
            background-color: rgba(26, 35, 126, 0.85); /* MODIFIED: Header Navy Blue */
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
            max-width: 120px;
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
            margin: 0 20px;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: nowrap;
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 8px;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            display: block;
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
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
            z-index: 1001;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        #templatemo_content_panel {
            padding: 40px 5%;
            flex: 1; /* Allow content to take available space */
            display: flex;
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px; /* You might want to adjust this if the form looks too wide */
            margin: 0 auto;
            /* gap: 30px; Removed as there's only one child now */
        }

        /*
        #templatemo_content_left {
            width: 300px;
            flex-shrink: 0;
        }

        #login_section {
            background-color: #1A237E;
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
        #login_section_middle a {
            color: var(--amu-text);
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            padding: 5px 0;
        }
        #login_section_middle a:hover {
            text-decoration: underline;
            color: var(--amu-primary);
        }


        .manager-icon {
            font-size: 100px;
            color: var(--amu-primary);
            margin: 20px 0;
        }
        */

        #templatemo_content_right {
            flex-grow: 1; /* This will now make the form container take the full width of #templatemo_content_section */
            background-color: #1A237E; /* MODIFIED: Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff; /* White for better contrast on Navy */
            position: relative;
            padding-bottom: 10px;
            text-align: center;
            width: 100%; /* Ensure title takes full width */
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        .form-container {
            max-width: 600px;
            width: 100%; /* Take available width */
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 1rem;
            color: var(--amu-text);
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(0, 0, 0, 0.5); /* Darker input for contrast */
            color: var(--amu-text);
            font-size: 1rem;
        }

        .form-input:focus {
            border-color: var(--amu-primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3);
        }

        .form-button { /* General styling for buttons, specific colors below */
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 1rem;
            display: inline-flex; /* For icon alignment */
            align-items: center; /* For icon alignment */
            gap: 8px; /* Space between icon and text */
        }
        .form-button:hover {
             transform: translateY(-2px);
        }


        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        /* Specific button colors */
        button[type="submit"].form-button {
            background-color: #00897B; /* Teal Green */
            color: white;
        }
        button[type="submit"].form-button:hover {
            background-color: #00695C; /* Darker Teal Green */
        }

        .reset-button { /* Assuming this class is used for the reset button */
            background-color: #FF6F00; /* Bright Orange */
            color: white;
        }
        .reset-button:hover {
             background-color: #E65100; /* Darker Bright Orange */
        }


        .instruction-text {
            color: var(--amu-text-secondary);
            text-align: center;
            margin-bottom: 30px;
            font-style: italic;
        }

        #templatemo_footer_panel {
            background-color: rgba(26, 35, 126, 0.85); /* MODIFIED: Footer Navy Blue */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto; /* Stick footer to bottom */
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
            #templatemo_content_panel {
                padding: 30px 3%;
            }
            #templatemo_content_section {
                flex-direction: column; /* This will stack them if #templatemo_content_left was present */
                align-items: center;
            }
            
            /*
            #templatemo_content_left {
                width: 100%;
                max-width: 400px;
                margin-bottom: 20px;
            }
            */

             #templatemo_content_right {
                width: 100%;
                padding: 25px;
            }
            
            /*
            #login_section {
                display: flex;
                align-items: center;
            }
            
            #login_section_middle {
                padding: 20px;
                flex-grow: 1;
            }
            
            #login_section_title {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                padding: 20px;
                flex-shrink: 0;
            }
            */
            #templatemo_menu ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            #templatemo_menu li {
                margin: 5px;
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
                font-size: 1.2rem;
            }
            
            #templatemo_menu ul {
                flex-direction: column;
                align-items: center;
            }
            
            #templatemo_menu li {
                margin: 5px 0;
            }
            
            /*
            #login_section {
                flex-direction: column;
            }
            
            #login_section_title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                width: 100%;
            }
            
            .manager-icon {
                font-size: 80px;
            }
            */
            .form-title {
                font-size: 1.5rem;
            }
            .form-container {
                padding: 20px;
            }
            .button-group {
                flex-direction: column;
            }
            .form-button, .reset-button {
                width: 100%;
            }
            .reset-button {
                margin-top: 10px;
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
                <li><a href="manager.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#"><i class="fas fa-car"></i> Vehicle</a>
                    <ul>
                        <li><a href="vehicle-register.html"><i class="fas fa-plus-circle"></i> Register vehicle</a></li>
                        <li><a href="view1.php"><i class="fas fa-edit"></i> Update vehicle</a></li>
                        <li><a href="searchvinfo.html"><i class="fas fa-search"></i> Search vehicles</a></li>
                    </ul>
                </li>
                <li><a href="#"><i class="fas fa-eye"></i> View</a>
                    <ul>
                        <li><a href="mviewschedule.php"><i class="fas fa-calendar-alt"></i> View schedule</a></li>
                        <li><a href="exitrequest1.php"><i class="fas fa-door-open"></i> View exit request</a></li>
                        <li><a href="mrequest-view.php"><i class="fas fa-tools"></i> View maintenance</a></li>
                        <li><a href="mmessage.php"><i class="fas fa-envelope"></i> View message</a></li>
                        <li><a href="comment12.php"><i class="fas fa-comments"></i> View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php"><i class="fas fa-gas-pump"></i> Fuel</a></li>
                <li><a href="upload.php" ><i class="fas fa-file-alt"></i> Report</a></li>
                <li><a href="changepssmanager.php" class="current"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <!-- Removed templatemo_content_left section -->
            
            <div id="templatemo_content_right">
                <h1 class="form-title"><i class="fas fa-lock"></i> Change Password</h1>
                <p class="instruction-text">Please fill the form correctly before submitting.</p>
                
                <div class="form-container">
                    <form name="htmlform" method="post" action="changepassword1.php" onsubmit="return checkform(this)">
                        <div class="form-group">
                            <label for="UserName" class="form-label">Username</label>
                            <input type="text" name="UserName" id="UserName" maxlength="50" class="form-input" placeholder="Enter your username" onkeypress="return ValidateAlpha(event)" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="Password" class="form-label">Current Password</label>
                            <input type="password" name="Password" id="Password" maxlength="50" class="form-input" placeholder="Enter your current password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" name="newPassword" id="newPassword" maxlength="80" class="form-input" placeholder="Enter new password (min 8 characters)" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ConfrimPassword" class="form-label">Confirm New Password</label>
                            <input type="password" name="ConfrimPassword" id="ConfrimPassword" maxlength="30" class="form-input" placeholder="Confirm your new password" required>
                        </div>
                        
                        <div class="button-group">
                            <button type="submit" name="submit" class="form-button"><i class="fas fa-save"></i> Submit</button>
                            <button type="reset" class="form-button reset-button"><i class="fas fa-undo"></i> Clear</button>
                        </div>
                    </form>
                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Client-side session check (optional - kept from your original)
            /*
            fetch('check_login.php')
                .then(response => response.text())
                .then(data => {
                    if (data.trim().toLowerCase() === 'false') {
                        window.location.href = 'index.html';
                    }
                })
                .catch(error => console.error('Error checking login status:', error));
            */

            // Live feedback for New Password field
            const newPasswordField = document.getElementById('newPassword');
            const newPasswordFeedbackDiv = document.getElementById('newPasswordFeedback');

            if (newPasswordField && newPasswordFeedbackDiv) {
                newPasswordField.addEventListener('input', function() {
                    const password = this.value;
                    let messages = [];
                    const minLength = 8;

                    if (password.length === 0) {
                        // Reset to initial guidance when field is empty
                        newPasswordFeedbackDiv.innerHTML = 'Password must be at least 8 characters, including letters and numbers.';
                        // You can set a default color if needed, e.g.,
                        // newPasswordFeedbackDiv.style.color = 'var(--amu-text-secondary)';
                        return;
                    }

                    // 1. Minimum length check
                    if (password.length >= minLength) {
                        messages.push('<span style="color: #4CAF50;">✓ At least ' + minLength + ' characters</span>'); // Green for met
                    } else {
                        messages.push('<span style="color: #f44336;">✗ At least ' + minLength + ' characters</span>'); // Red for unmet
                    }

                    // 2. Contains a letter check
                    if (/[a-zA-Z]/.test(password)) {
                        messages.push('<span style="color: #4CAF50;">✓ Contains a letter (a-z, A-Z)</span>');
                    } else {
                        messages.push('<span style="color: #f44336;">✗ Contains a letter (a-z, A-Z)</span>');
                    }

                    // 3. Contains a number check
                    if (/[0-9]/.test(password)) {
                        messages.push('<span style="color: #4CAF50;">✓ Contains a number (0-9)</span>');
                    } else {
                        messages.push('<span style="color: #f44336;">✗ Contains a number (0-9)</span>');
                    }

                    newPasswordFeedbackDiv.innerHTML = messages.join('<br>');
                });
            }
        });

        function checkform(form) {
            if (form.UserName.value.trim() == "") {
                alert("Please fill UserName");
                form.UserName.focus();
                return false;
            }
            if (form.Password.value == "") {
                alert("Please fill current Password");
                form.Password.focus();
                return false;
            }

            const newPasswordValue = form.newPassword.value;
            const minPasswordLength = 8;

            if(newPasswordValue == '') {
                alert("Please enter new password.");
                form.newPassword.focus();
                return false;
            }
            // New Password criteria checks with alerts
            if(newPasswordValue.length < minPasswordLength) {
                alert("New Password is too short! It must contain at least " + minPasswordLength + " characters.");
                form.newPassword.focus();
                return false;
            }
            if (!/[a-zA-Z]/.test(newPasswordValue)) { // Check for at least one letter
                alert("New Password must contain at least one letter (a-z or A-Z).");
                form.newPassword.focus();
                return false;
            }
            if (!/[0-9]/.test(newPasswordValue)) { // Check for at least one number
                alert("New Password must contain at least one number (0-9).");
                form.newPassword.focus();
                return false;
            }
            // End of New Password criteria checks

            if(form.ConfrimPassword.value == '') {
                alert("Please confirm new password.");
                form.ConfrimPassword.focus();
                return false;
            } else if(newPasswordValue != form.ConfrimPassword.value) {
                alert("New Password does not match confirmation.");
                form.ConfrimPassword.focus();
                return false;
            }
            return true;
        }

        function ValidateAlpha(evt) {
            var keyCode = (evt.which) ? evt.which : evt.keyCode;
            if (!((keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || keyCode == 32 || keyCode == 8 || keyCode == 9)) {
                // alert("Only letters are allowed for username!"); // Kept commented as per your original
                return false;
            }
            return true;
        }
    </script>
</body>
</html>