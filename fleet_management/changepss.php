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
            --amu-danger: #dc3545; /* Added for btn-reset */
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
            background-color: var(--amu-new-bg); 
            padding: 0.8rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 15px rgba(0,0,0,0.4); 
            position: relative;
            z-index: 1000;
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
            min-width: 180px; 
            padding: 0.5rem 0;
            display: none;
            z-index: 1001; 
        }

        li:hover > .dropdown {
            display: block;
        }

        .dropdown a {
            padding: 0.8rem 1.2rem;
            text-align: left;
            width: 100%; 
        }
        .dropdown a i {
            margin-right: 0.5rem; 
        }

        /* Main Content */
        main {
            flex: 1; 
            padding: 1.5rem 5%;
            width: 100%;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            backdrop-filter: blur(2px); 
        }

        /* Right Panel (Now the main content block) */
        .right-panel {
            flex-grow: 0; 
            width: 100%; 
            max-width: 700px; 
            background-color: var(--amu-new-bg); 
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 25px rgba(0,0,0,0.5); 
            border: 1px solid rgba(255,255,255,0.1); 
        }

        .section-title {
            color: #fff; 
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); 
            text-align: center; 
            font-size: 1.6rem; 
            position: relative;
        }
        .section-title::after { 
            content: '';
            position: absolute;
            bottom: -1px; 
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary); 
        }

        /* Password Form */
        .password-form {
            /* background-color: rgba(0, 0, 0, 0.2); /* MODIFIED: Removed for transparency */
            padding: 1.5rem; /* Kept padding for inner spacing if needed, or adjust */
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--amu-text);
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            background-color: rgba(0, 0, 0, 0.5); /* Inputs have their own dark bg */
            color: var(--amu-text);
            font-size: 1rem;
        }
        input::placeholder {
            color: var(--amu-text-secondary);
            opacity: 0.7;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--amu-primary);
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Buttons */
        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
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

        .btn-secondary { /* Kept original btn-secondary style, now using btn-reset for clear */
            background-color: #333; 
            color: white;
        }
        .btn-secondary:hover {
            background-color: #444;
        }

        .btn-reset { /* ADDED for red clear button */
            background-color: var(--amu-danger);
            color: white;
        }
        .btn-reset:hover { /* ADDED */
            background-color: #c82333; /* Darker red for hover */
            transform: translateY(-2px);
        }


        /* Footer */
        footer {
            background-color: var(--amu-new-bg); 
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
            .right-panel {
                padding: 1rem; 
            }
            .section-title {
                font-size: 1.4rem;
            }
        }
        @media (max-width: 576px) {
            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
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
                <li><a href="scheduler.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="newsche.php"><i class="fas fa-calendar-plus"></i> Schedule</a></li>
                <li>
                    <a href="#"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="sviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="smessage.php"><i class="fas fa-envelope"></i> View Messages</a></li>
                    </ul>
                </li>
                <li><a href="searchvinfo1.php"><i class="fas fa-search"></i> Search Vehicle</a></li>
                <li><a href="changepss.php" class="current"><i class="fas fa-key"></i> Change Password</a></li> 
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <!-- Left Panel Removed -->
        <div class="right-panel">
            <h2 class="section-title">Change Your Password</h2>
            
            <div class="password-form">
                <form name="htmlform" method="post" action="changepassword1.php" onsubmit="return checkform(this)">
                    <div class="form-group">
                        <label for="UserName">Username <span style="color:var(--amu-danger)">*</span></label>
                        <input type="text" name="UserName" id="UserName" maxlength="50" placeholder="Enter your username" onkeypress="return ValidateAlpha(event)" required value="scheduler">
                    </div>
                    
                    <div class="form-group">
                        <label for="Password">Current Password <span style="color:var(--amu-danger)">*</span></label>
                        <input type="password" name="Password" id="Password" maxlength="50" placeholder="Enter current password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="newPassword">New Password <span style="color:var(--amu-danger)">*</span></label>
                        <input type="password" name="newPassword" id="newPassword" maxlength="80" placeholder="Enter new password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ConfrimPassword">Confirm New Password <span style="color:var(--amu-danger)">*</span></label>
                        <input type="password" name="ConfrimPassword" id="ConfrimPassword" maxlength="30" placeholder="Confirm new password" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-reset"> <!-- MODIFIED class -->
                            <i class="fas fa-undo"></i> Clear
                        </button>
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
            // Client-side session check (optional if check_login.php is implemented)
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
            const minPasswordLengthGlobal = 6; // Set this to match your checkform logic

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

        function checkform(form) {
            if (form.UserName.value.trim() === "") {
                alert("Please enter your username.");
                form.UserName.focus();
                return false;
            }
            if (form.Password.value === "") {
                alert("Please enter your current password.");
                form.Password.focus();
                return false;
            }

            const newPasswordValue = form.newPassword.value;
            const minPasswordLength = 6; // Matching your existing check

            if (newPasswordValue === "") {
                alert("Please enter your new password.");
                form.newPassword.focus();
                return false;
            }
            // New Password criteria checks with alerts
            if (newPasswordValue.length < minPasswordLength) {
                alert("New password must be at least " + minPasswordLength + " characters long.");
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

            if (form.ConfrimPassword.value === "") {
                alert("Please confirm your new password.");
                form.ConfrimPassword.focus();
                return false;
            }
            if (newPasswordValue !== form.ConfrimPassword.value) {
                alert("New passwords do not match. Please re-enter.");
                form.ConfrimPassword.value = "";
                form.ConfrimPassword.focus(); // Focus on confirm password for correction
                return false;
            }
            return true;
        }

        function ValidateAlpha(evt) {
            var keyCode = (evt.which) ? evt.which : evt.keyCode;
            if (!((keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 123) || keyCode == 32 || keyCode == 8 || keyCode == 9)) {
                // evt.preventDefault(); // Your original had this commented out
                alert("Only letters and spaces are allowed for username!"); // Added alert to match behavior of other validations
                return false;
            }
            return true;
        }
    </script>
</body>
</html>