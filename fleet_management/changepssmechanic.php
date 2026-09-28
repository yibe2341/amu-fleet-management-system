<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
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
    <title>Change Password | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; */ /* Original black, replaced by navy */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
            --amu-danger-dark: #c82333; /* For button hover consistency */

            /* Navy Blue Theme Variables */
            --amu-navy-bg-header-footer: rgba(26, 35, 126, 0.85);
            --amu-navy-bg-panel: rgba(26, 35, 126, 0.75);
            --amu-navy-bg-input: rgba(0, 0, 0, 0.4); /* Darker input for contrast */
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
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Header Styles */
        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            padding: 10px 5%;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .logo-container {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 60px;
            max-width: 120px;
        }

        #templatemo_top_panel img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        #site_title {
            font-size: clamp(0.9rem, 3vw, 1.3rem);
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 10px;
            flex: 2;
            min-width: 180px;
        }

        #templatemo_menu {
            flex: 3;
            min-width: 200px;
            max-width: 100%;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }

        #templatemo_menu li {
            margin: 2px 0;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: clamp(0.7rem, 2vw, 0.85rem);
            display: inline-flex; /* For icon alignment */
            align-items: center; /* For icon alignment */
            gap: 5px; /* Space for icon */
            white-space: nowrap;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        /* Main Content */
        #templatemo_content_panel {
            flex: 1;
            padding: 20px 0; /* Increased padding */
            width: 100%;
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            /* display: flex; */ /* Parent of .templatemo_content_right */
            /* flex-direction: column; */ /* Original, not needed as left is removed */
            width: 95%;
            max-width: 800px; /* Adjusted for a single form column */
            margin: 0 auto;
            /* gap: 15px; */ /* Original, not needed */
        }

        /* Sidebar - #templatemo_content_left and its children are REMOVED */
        /*
        #templatemo_content_left { ... }
        #login_section { ... }
        #login_section_title { ... }
        #login_section img { ... }
        */

        /* Main Content Area */
        #templatemo_content_right {
            /* order: 2; */ /* Original, not needed */
            width: 100%; /* Takes full width */
        }

        .right_column_section {
            background-color: var(--amu-navy-bg-panel); /* MODIFIED */
            border-radius: 10px;
            padding: 20px 25px; /* Adjusted padding */
            margin-bottom: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        .right_column_section_title {
            font-size: clamp(1.2rem, 3vw, 1.6rem); /* Slightly larger title */
            font-weight: 600;
            margin-bottom: 20px; /* Increased margin */
            color: var(--amu-text); /* MODIFIED for contrast */
            text-align: center;
            position: relative;
            padding-bottom: 10px;
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

        /* Form Styles */
        .password-form {
            max-width: 550px; /* Consistent width with other forms */
            margin: 0 auto;
        }

        .form-instruction {
            color: var(--amu-text-secondary); /* MODIFIED: Softer color */
            text-align: center;
            margin-bottom: 25px; /* Increased margin */
            font-size: 1rem;
            font-style: italic;
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
            padding: 10px 12px; /* Consistent padding */
            border-radius: 6px; /* Softer radius */
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: var(--amu-navy-bg-input); /* MODIFIED */
            color: var(--amu-text);
            font-size: 1rem;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
             border-color: var(--amu-primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(46,125,50,0.3);
        }
        .form-group input[readonly] {
            background-color: rgba(0,0,0,0.3); /* Slightly different for readonly */
            cursor: not-allowed;
        }


        .form-note {
            color: var(--amu-text-secondary);
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .form-actions {
            text-align: center;
            margin-top: 30px; /* Increased margin */
            display: flex; /* For button alignment */
            gap: 15px; /* Space between buttons */
            justify-content: center; /* Center buttons */
        }

        .btn { /* Changed from input to button for consistency */
            padding: 10px 25px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            /* margin: 0 10px; /* Original, now handled by gap */
            display: inline-flex; /* For icon alignment */
            align-items: center; /* For icon alignment */
            gap: 8px; /* Space for icon */
        }
        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-submit {
            background-color: var(--amu-primary);
            color: white;
        }

        .btn-submit:hover {
            background-color: var(--amu-primary-dark);
        }

        .btn-reset {
            background-color: var(--amu-danger);
            color: white;
        }

        .btn-reset:hover {
            background-color: var(--amu-danger-dark); /* Using var */
        }

        /* Footer */
        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            padding: 15px 5%; /* Adjusted padding */
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: clamp(0.75rem, 2vw, 0.85rem); /* Adjusted */
            width: 100%;
            margin-top: auto; /* Push to bottom */
        }
        #templatemo_footer_section {
            color: rgba(255,255,255,0.7);
        }

        #templatemo_footer_section a {
            color: var(--amu-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary-dark);
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (min-width: 768px) {
            /* #templatemo_content_section {
                flex-direction: row; /* Original, not needed as left is removed */
            } */
            
            /* #templatemo_content_left rules are removed */
            
            #templatemo_content_right {
                /* flex: 1; */ /* Original, not strictly needed */
                /* order: 2; */ /* Original, not needed */
                /* padding-left: 20px; */ /* Original, if section needs padding */
            }

            #templatemo_top_panel img {
                height: 50px;
            }
            /* #login_section img styles removed */
        }

        @media (max-width: 767px) { /* Mobile specific form adjustments */
            .right_column_section_title {
                font-size: clamp(1.1rem, 4vw, 1.4rem);
            }
            .form-instruction {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            #templatemo_top_panel {
                flex-direction: column;
                align-items: center;
                gap: 10px;
                height: auto;
                padding-top: 15px;
                padding-bottom: 15px;
            }
            
            .logo-container {
                display: none;
            }
            
            #site_title {
                order: 1;
                width: 100%;
                margin-bottom: 10px;
            }
            
            #templatemo_menu {
                order: 2;
                width: 100%;
            }
            
            #templatemo_menu ul {
                justify-content: space-around;
            }
            
            .btn { /* Stack buttons on very small screens */
                width: 100%;
                margin: 5px 0;
            }
            
            .form-actions {
                flex-direction: column;
            }
            /* #login_section img styles removed */
        }
    </style>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Client-side session check
            fetch('check_login.php')
                .then(response => response.text())
                .then(data => {
                    if (data.trim().toLowerCase() === 'false') { // Robust check
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
                        // Reset to initial guidance when field is empty
                        newPasswordFeedbackDiv.innerHTML = 'Password must be at least 8 characters, including letters and numbers.';
                        newPasswordFeedbackDiv.style.color = 'var(--amu-text-secondary)'; // Assuming this var holds the default note color
                        return;
                    }

                    // 1. Minimum length check
                    if (password.length >= minLength) {
                        messages.push('<span style="color: var(--amu-success, #28a745);">✓ At least ' + minLength + ' characters</span>');
                    } else {
                        messages.push('<span style="color: var(--amu-danger, #dc3545);">✗ At least ' + minLength + ' characters</span>');
                    }

                    // 2. Contains a letter check
                    if (/[a-zA-Z]/.test(password)) {
                        messages.push('<span style="color: var(--amu-success, #28a745);">✓ Contains a letter (a-z, A-Z)</span>');
                    } else {
                        messages.push('<span style="color: var(--amu-danger, #dc3545);">✗ Contains a letter (a-z, A-Z)</span>');
                    }

                    // 3. Contains a number check
                    if (/[0-9]/.test(password)) {
                        messages.push('<span style="color: var(--amu-success, #28a745);">✓ Contains a number (0-9)</span>');
                    } else {
                        messages.push('<span style="color: var(--amu-danger, #dc3545);">✗ Contains a number (0-9)</span>');
                    }

                    newPasswordFeedbackDiv.innerHTML = messages.join('<br>');
                });
            }
        });


        // ValidateAlpha is not used if username is readonly, but kept for completeness
        function ValidateAlpha(evt) {
            var keyCode = (evt.which) ? evt.which : evt.keyCode;
            if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 122) && keyCode != 32 && keyCode != 8 && keyCode != 9) {
                // alert("Only letters are allowed!"); // Can be annoying for readonly field
                return false;
            }
            return true;
        }

        function checkform(form) {
            // Username is pre-filled and readonly, so validation for it is not strictly needed here.
            // if (form.UserName.value.trim() == "") {
            //     alert("Please enter your username"); // Or "Username is missing."
            //     form.UserName.focus();
            //     return false;
            // }
            if (form.Password.value == "") {
                alert("Please enter your current password.");
                form.Password.focus();
                return false;
            }

            const newPasswordValue = form.newPassword.value;
            const minPasswordLength = 8;

            if(newPasswordValue == '') {
                alert("Please enter a new password.");
                form.newPassword.focus();
                return false;
            }
            // New Password criteria checks with alerts
            if(newPasswordValue.length < minPasswordLength) {
                alert("New Password must be at least " + minPasswordLength + " characters long.");
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
                alert("Please confirm your new password.");
                form.ConfrimPassword.focus();
                return false;
            }
            else if(newPasswordValue != form.ConfrimPassword.value) {
                alert("New Passwords do not match.");
                form.ConfrimPassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div id="templatemo_top_panel">
        <div class="logo-container">
            <img src="wou arm.jpg.png" alt="AMU Logo">
        </div>
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="mechanic.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="Mmrequest-view.php"><i class="fas fa-tools"></i> view request</a></li>
                <li><a href="massage2.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="changepssmechanic.php" class="current"><i class="fas fa-key"></i> Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="logo-container" style="justify-content: flex-end;">
            <img src="wou arm.jpg.png" alt="AMU Logo">
        </div>
    </div>

    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <!-- #templatemo_content_left has been removed -->
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        <i class="fas fa-lock"></i> Change Your Password
                    </div>
                    <div class="right_column_section_body">
                        <p class="form-instruction">Please fill the form correctly to update your password.</p>
                        <form name="htmlform" method="post" action="changepassword1.php" onsubmit="return checkform(this);" class="password-form">
                            <div class="form-group">
                                <label for="UserName"><i class="fas fa-user"></i> Username</label>
                                <input type="text" name="UserName" id="UserName" maxlength="50" 
                                       value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                                       <!-- onkeypress="return ValidateAlpha(event)" removed as it's readonly -->
                            </div>
                            
                            <div class="form-group">
                                <label for="Password"><i class="fas fa-unlock-alt"></i> Current Password</label>
                                <input type="password" name="Password" id="Password" maxlength="50" placeholder="Enter current password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="newPassword"><i class="fas fa-key"></i> New Password</label>
                                <input type="password" name="newPassword" id="newPassword" maxlength="80" placeholder="Enter new password" required>
                                <span class="form-note">Password must be at least 8 characters long.</span>
                            </div>
                            
                            <div class="form-group">
                                <label for="ConfrimPassword"><i class="fas fa-check-circle"></i> Confirm New Password</label>
                                <input type="password" name="ConfrimPassword" id="ConfrimPassword" maxlength="30" placeholder="Confirm new password" required>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-submit"><i class="fas fa-save"></i> Submit Changes</button>
                                <button type="reset" class="btn btn-reset"><i class="fas fa-undo"></i> Clear Form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
        Copyright © <?php echo date("Y"); ?> <a href="#">AMU University</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>