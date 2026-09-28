<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMU Fleet Management System - Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32; /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-danger: #dc3545; /* For btn-reset */
            --amu-new-bg: rgba(26, 35, 126, 0.85); /* Navy Blue */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--amu-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        header {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            padding: 0.8rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 15px rgba(0,0,0,0.4); 
        }

        .logo {
            height: 50px;
            width: auto;
        }

        .site-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            flex-grow: 1;
            margin: 0 1rem;
        }

        /* Navigation */
        nav ul {
            display: flex;
            list-style: none;
            gap: 0.5rem;
        }

        nav li {
            position: relative;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }

        nav a:hover, nav a.current {
            background-color: var(--amu-primary);
        }

        /* Dropdown */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 0 0 4px 4px;
            min-width: 180px; /* Consistent width */
            padding: 0.5rem 0;
            display: none;
            z-index: 1000; /* Original z-index */
        }

        li:hover > .dropdown {
            display: block;
        }

        .dropdown a {
            padding: 0.8rem 1.2rem;
            text-align: left;
            width: 100%; /* Ensure full width */
        }
         .dropdown a i { /* Space for icons in dropdown */
            margin-right: 0.5rem;
        }


        /* Main Content */
        main {
            flex: 1;
            padding: 1.5rem 5%;
            width: 100%;
            display: flex; /* To center content */
            justify-content: center;
            align-items: center; /* Vertically center if content is short */
        }

        .container {
            max-width: 600px; /* Max width for the password form container */
            width: 100%; /* Take available width */
            margin: 0 auto; /* Center if parent doesn't use flex for centering */
            backdrop-filter: blur(2px); /* Blur background behind content */
        }

        /* Change Password Section */
        .password-section {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 25px rgba(0,0,0,0.5); 
            border: 1px solid rgba(255,255,255,0.1); 
        }

        .password-section h2 {
            color: #fff; /* MODIFIED: White for better contrast on navy */
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.6rem; 
            position: relative;
            padding-bottom: 0.5rem;
        }
        .password-section h2::after { /* Underline for title */
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
            display: flex;
            flex-direction: column;
            gap: 1rem;
            /* background-color removed to make it transparent against .password-section */
            /* padding: 1.5rem; /* Padding is now on .password-section */
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-size: 1rem;
            color: var(--amu-text);
        }

        .form-group input {
            padding: 0.8rem;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(0, 0, 0, 0.5); 
            color: var(--amu-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-group input::placeholder { 
            color: var(--amu-text-secondary);
            opacity: 0.7;
        }


        .form-group input:focus {
            outline: none;
            border-color: var(--amu-primary);
            background-color: rgba(255, 255, 255, 0.2); 
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex; 
            align-items: center;
            gap: 0.5rem; 
        }

        .btn-primary {
            background-color: var(--amu-primary); 
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
        }

        .btn-reset { /* MODIFIED: Consistent red reset button */
            background-color: var(--amu-danger);
            color: white;
        }
        .btn-reset:hover { /* MODIFIED */
            background-color: #c82333; /* Darker red */
            transform: translateY(-2px);
        }


        /* Footer */
        footer {
            background-color: var(--amu-new-bg); /* MODIFIED */
            padding: 1rem 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
            margin-top: auto; 
        }

        footer a {
            color: var(--amu-primary); 
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: var(--amu-primary-dark);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
            }
            
            .site-title {
                margin: 0.5rem 0;
                font-size: 1.2rem;
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                flex-direction: column;
                align-items: center;
            }
             nav li {
                margin: 0.3rem 0;
            }
            
            .dropdown {
                position: static;
                display: none;
                width: 100%;
                background-color: rgba(0,0,0,0.7);
            }
            
            li:hover > .dropdown, li:focus-within > .dropdown {
                display: block;
            }

            .logo {
                height: 40px;
                margin-bottom: 0.5rem;
            }
            header img.logo:last-of-type {
                display: none;
            }

            main {
                padding: 1rem;
                align-items: flex-start; 
            }
            .password-section {
                padding: 1.5rem;
            }
            .password-section h2 {
                font-size: 1.4rem;
            }
        }
         @media (max-width: 576px) {
            .btn {
                width: 100%; 
            }
            .form-actions {
                flex-direction: column; 
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
        <h1 class="site-title">AMU FLEET MANAGEMENT SYSTEM</h1>
        <nav>
            <ul>
                <li><a href="police.php"><i class="fas fa-home"></i> Home</a></li>
                <li>
                    <!-- <a href="#"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a> -->
                    <!-- <ul class="dropdown">
                        <li><a href="viewpoliceschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="exit2.php"><i class="fas fa-sign-out-alt"></i> View Exit Permissions</a></li>
                    </ul> -->
                </li>
                <li><a href="changepsspolice.php" class="current"><i class="fas fa-key"></i> Change Password</a></li> <!-- Assuming current page -->
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <div class="container">
            <div class="password-section">
                <h2><i class="fas fa-key"></i> Change Password</h2>
                <form name="htmlform" method="post" action="changepassword1.php" onsubmit="return checkform(this)" class="password-form">
                    <div class="form-group">
                        <label for="UserName"><i class="fas fa-user"></i> Username <span style="color:var(--amu-danger)">*</span></label>
                        <input type="text" name="UserName" id="UserName" maxlength="50" placeholder="Enter your username" onkeypress="return ValidateAlpha(event)" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="Password"><i class="fas fa-lock"></i> Current Password <span style="color:var(--amu-danger)">*</span></label>
                        <input type="password" name="Password" id="Password" maxlength="50" placeholder="Enter your current password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="newPassword"><i class="fas fa-lock"></i> New Password <span style="color:var(--amu-danger)">*</span></label>
                        <input type="password" name="newPassword" id="newPassword" maxlength="80" placeholder="Enter new password (min 8 characters)" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ConfrimPassword"><i class="fas fa-lock"></i> Confirm New Password <span style="color:var(--amu-danger)">*</span></label>
                        <input type="password" name="ConfrimPassword" id="ConfrimPassword" maxlength="30" placeholder="Confirm your new password" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-check"></i> Submit</button>
                        <button type="reset" class="btn btn-reset"><i class="fas fa-undo"></i> Clear</button> <!-- MODIFIED to btn-reset -->
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>© Copyright © <?php echo date('Y'); ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a></p>
    </footer>

       <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Client-side session check (optional)
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
            const minPasswordLengthGlobal = 8; // To match your placeholder and existing checkform length

            if (newPasswordField && newPasswordFeedbackDiv) {
                // Set initial message based on the minPasswordLengthGlobal
                newPasswordFeedbackDiv.innerHTML = 'Password must be at least ' + minPasswordLengthGlobal + ' characters, including letters and numbers.';

                newPasswordField.addEventListener('input', function() {
                    const password = this.value;
                    let messages = [];

                    if (password.length === 0) {
                        // Reset to initial guidance when field is empty
                        newPasswordFeedbackDiv.innerHTML = 'Password must be at least ' + minPasswordLengthGlobal + ' characters, including letters and numbers.';
                        newPasswordFeedbackDiv.style.color = 'var(--amu-text-secondary)'; // Reset color
                        return;
                    }

                    // 1. Minimum length check
                    if (password.length >= minPasswordLengthGlobal) {
                        messages.push('<span style="color: var(--amu-success, #28a745);">✓ At least ' + minPasswordLengthGlobal + ' characters</span>');
                    } else {
                        messages.push('<span style="color: var(--amu-danger, #dc3545);">✗ At least ' + minPasswordLengthGlobal + ' characters</span>');
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

        // Form validation function (updated)
        function checkform(form) {
            if (form.UserName.value.trim()=="") {
                alert("Please fill UserName");
                form.UserName.focus();
                return false;
            }
            if (form.Password.value=="") {
                alert("Please fill Current Password"); // Updated from "oldPassword" for clarity
                form.Password.focus();
                return false;
            }

            const newPasswordValue = form.newPassword.value;
            const minPasswordLength = 8; // Consistent with placeholder and feedback

            if(newPasswordValue=='') {
                alert("Please enter new password.");
                form.newPassword.focus();
                return false;
            }
            // New Password criteria checks with alerts
            if(newPasswordValue.length < minPasswordLength) {
                // Your original message: "Password is too short!! it must contains atlist 8 character"
                // Corrected slightly for grammar:
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

            if(form.ConfrimPassword.value=='') {
                alert("Please confirm password.");
                form.ConfrimPassword.focus();
                return false;
            }
            else if(newPasswordValue != form.ConfrimPassword.value) {
                alert("Password does not match.");
                form.ConfrimPassword.value = ""; // Clear confirm password for re-entry
                form.ConfrimPassword.focus(); // Focus on confirm password
                return false;
            }
            return true;
        }

        // Alpha validation function (kept from original)
        function ValidateAlpha(evt) {
            var keyCode = (evt.which) ? evt.which : evt.keyCode;
            if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && keyCode != 8 && keyCode != 9) {
                alert("Only letters and spaces are allowed for username!"); // Added alert
                return false;
            }
            return true;
        }
    </script>
</body>
</html>