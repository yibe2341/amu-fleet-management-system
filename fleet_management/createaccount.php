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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Create Account | AMU Fleet Management</title>
    <meta name="keywords" content="fleet management, AMU, account creation, admin">
    <meta name="description" content="AMU Fleet Management System - Create New Account">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome for icons -->
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; -- Original Dark, replaced by navy */
            --amu-navy-bg-heavy: rgba(0, 0, 128, 0.85); /* NAVY for header/footer */
            --amu-navy-bg-medium: rgba(0, 0, 128, 0.75); /* NAVY for content sections */
            --amu-navy-bg-light: rgba(0, 0, 128, 0.7);   /* NAVY for sidebar */
            --amu-navy-bg-lighter: rgba(0, 0, 128, 0.1); /* NAVY for inputs (very light overlay on navy) */
            --amu-navy-dropdown: rgba(0, 0, 128, 0.9); /* NAVY for dropdowns */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-teal-green: #00897B; /* For Submit button */
            --amu-teal-green-dark: #00695C; /* For Submit button hover */
            --amu-orange-bright: #FF6F00; /* For Clear button */
            --amu-orange-bright-dark: #E65100; /* For Clear button hover */
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

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-heavy); /* NAVY */
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
            flex-grow: 1; /* Re-added for original title behavior */
            margin: 0 1rem; /* Re-added for original title behavior */
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            /* flex-wrap: wrap; -- Reverted */
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 8px; /* Reverted */
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px; /* Reverted */
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem; /* Reverted */
            /* white-space: nowrap; -- Reverted */
            /* display: flex; -- Reverted */
            /* align-items: center; -- Reverted */
            /* gap: 5px; -- Reverted */
        }
        /* Icon styling for navbar removed */


        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--amu-navy-dropdown); /* NAVY */
            border-radius: 0 0 4px 4px;
            width: 180px; /* Reverted */
            padding: 5px 0;
            z-index: 1001;
             /* box-shadow: none; Reverted */
        }
         #templatemo_menu ul ul a {
            padding: 8px 15px; /* Reverted */
            font-size: 0.9rem; /* Reverted */
            /* width: 100%; -- Reverted */
            /* box-sizing: border-box; -- Reverted */
        }


        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 40px 5%; /* Reverted */
            min-height: calc(100vh - 160px);
            display: flex;
            /* justify-content: center; -- Reverted */
            backdrop-filter: blur(2px);
            /* flex-grow: 1; -- Reverted, body handles flex */
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto; /* Reverted */
            gap: 30px;
        }

        /* ===== Left Column Styles ===== */
        #templatemo_content_left {
            width: 300px; /* Reverted */
            flex-shrink: 0;
        }

        #login_section {
            background-color: var(--amu-navy-bg-light); /* NAVY */
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
            background-color: rgba(46, 125, 50, 0.3); /* Original green overlay */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #login_section_middle {
            padding: 20px;
            text-align: center;
        }

        /* MODIFIED: Admin Icon styling */
        .admin-icon-container {
            margin: 0 auto; 
            display: flex;
            justify-content: center;
            align-items: center;
            width: 150px; 
            height: 150px; 
            border-radius: 50%; 
            border: 3px solid rgba(255, 255, 255, 0.1); /* Original image border */
        }
        .admin-icon-container .fas.fa-user-tie {
            font-size: 75px; 
            color: var(--amu-primary); /* Green icon */
        }
        /* #login_section_middle img { -- Style for original image if it were still here
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.1);
        } */


        /* ===== Right Column Styles ===== */
        #templatemo_content_right {
            flex-grow: 1;
            background-color: var(--amu-navy-bg-medium); /* NAVY */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
        }

        .right_column_section_title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: #fff; /* Reverted */
            position: relative;
            padding-bottom: 10px;
            text-align: left; /* Reverted */
        }

        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0; /* Reverted */
            /* transform: translateX(-50%); -- Reverted */
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        .registration-form {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9); /* Original text color */
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            background-color: var(--amu-navy-bg-lighter); /* NAVY - Light overlay for input */
            color: #fff; /* White text for input */
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--amu-primary);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3);
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
        }

        .form-group select option {
        color: black; 
        background-color: white; 
       }

        .form-actions {
            grid-column: 1 / -1; 
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .form-button {
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            /* display: flex; -- Reverted */
            /* align-items: center; -- Reverted */
            /* justify-content: center; -- Reverted */
            /* gap: 8px; -- Reverted */
        }
        
        button[type="submit"].form-button {
            background-color: var(--amu-teal-green); 
        }
        button[type="submit"].form-button:hover {
            background-color: var(--amu-teal-green-dark); 
            transform: translateY(-2px);
        }

        button[type="reset"].form-button {
            background-color: var(--amu-orange-bright); 
        }
        button[type="reset"].form-button:hover {
            background-color: var(--amu-orange-bright-dark); 
            transform: translateY(-2px);
        }


        .password-strength {
            margin-top: 5px;
            font-size: 0.85rem;
            color: var(--amu-text-secondary);
        }

        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-heavy); 
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: #4caf50; /* Original footer link color */
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary);
            text-decoration: underline;
        }

        /* ===== Responsive Adjustments (Reverted to state before navbar/icon focus) ===== */
        @media (max-width: 992px) {
            #templatemo_content_panel {
                padding: 30px 3%;
            }
            #templatemo_content_section {
                flex-direction: column;
                /* align-items: center; -- Reverted */
            }
            
            #templatemo_content_left {
                width: 100%;
                /* max-width: 500px; -- Reverted */
            }
             #templatemo_content_right {
                 /* width: 100%; -- Reverted */
                 /* max-width: 700px; -- Reverted */
                padding: 25px;
            }
            
            #login_section {
                display: flex;
                align-items: center;
                margin-bottom: 20px;
            }
            
            #login_section_middle {
                padding: 20px; /* Reverted */
                /* flex-grow: 1; -- Reverted */
                /* display: flex; -- Reverted */
                /* justify-content: center; -- Reverted */
                /* align-items: center; -- Reverted */
            }
            /* Icon specific responsive styles reverted */
            
            #login_section_title {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                padding: 20px; 
                flex-shrink: 0;
                margin-bottom: 0; /* Reverted */
                /* font-size: 1.3rem; -- Reverted */
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
                /* font-size: 1.2rem; -- Reverted */
            }
            
            #templatemo_menu ul {
                flex-wrap: wrap; 
                justify-content: center;
                /* flex-direction: column; -- Reverted */
                /* align-items: center; -- Reverted */
            }
            
            #templatemo_menu li {
                margin: 5px; /* Reverted */
                /* width: 100%; -- Reverted */
                /* max-width: 250px; -- Reverted */
            }
            /* Menu 'a' justify content reverted */
            /* Menu ul ul styles reverted */
            
            #login_section {
                flex-direction: column;
            }
            
            #login_section_title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                width: 100%; 
                 margin-bottom: 0; /* Reverted */
                 /* font-size: 1.4rem; -- Reverted */
            }
            /* Admin icon container responsive styles reverted */
            
            .registration-form {
                grid-template-columns: 1fr; 
            }
            .form-actions {
                flex-direction: column; 
            }
            .form-button {
                width: 100%; 
            }
            /* .right_column_section_title font-size reverted */
        }
        /* @media (max-width: 600px) styles removed to revert to previous state */
    </style>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="Admin.php">Home</a></li>
                <li><a href="#" class="current">Account</a>
                    <ul>
                        <li><a href="createaccount.php" class="current">Create account</a></li>
                        <li><a href="view.php">Update account</a></li>
                        <li><a href="view2.php">Delete account</a></li>
                    </ul>
                </li>
                <li><a href="aviewschedule.php">View Schedule</a></li>
                <li><a href="upload1.php">Report</a></li>
                <li><a href="changepssadmin.php">Change Password</a></li>
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
                    <div id="login_section_title">ADMIN PANEL</div>
                    <div id="login_section_middle">
                        <!-- THIS IS THE ONLY HTML CHANGE from the original createaccount.php with navy theme -->
                        <div class="admin-icon-container">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section_title">Employee Registration</div>
                <form name="registration" method="post" action="employee-register.php" onsubmit="return checkform(this)" class="registration-form">
                    <div class="form-group">
                        <label for="User_id" class="form-label">User ID</label>
                        <input type="text" name="User_id" id="User_id" class="form-input" maxlength="50" required placeholder="e.g., EMP001">
                    </div>
                    
                    <div class="form-group">
                        <label for="First_Name" class="form-label">First Name</label>
                        <input type="text" name="First_Name" id="First_Name" class="form-input" maxlength="80" onkeypress="return ValidateAlpha(event)" required placeholder="Enter first name">
                    </div>
                    
                    <div class="form-group">
                        <label for="Last_Name" class="form-label">Last Name</label>
                        <input type="text" name="Last_Name" id="Last_Name" class="form-input" maxlength="30" onkeypress="return ValidateAlpha(event)" required placeholder="Enter last name">
                    </div>
                    
                    <div class="form-group">
                        <label for="Sex" class="form-label">Sex</label>
                        <select name="Sex" id="Sex" class="form-input" required>
                            <option value="">---Select Sex---</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="Role" class="form-label">Role</label>
                        <select name="Role" id="Role" class="form-input" required>
                            <option value="">--Select Role--</option>
                            <option value="Admin">Admin</option>
                            <option value="Driver">Driver</option>
                            <option value="Manager">Manager</option>
                            <option value="User">User</option>
                            <option value="Mechanic">Mechanic</option>
                            <option value="Scheduler">Scheduler</option>
                            <option value="Police">Police</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="Email" class="form-label">Email</label>
                        <input type="email" name="Email" id="Email" class="form-input" maxlength="50" required placeholder="example@domain.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="Mobile_No" class="form-label">Mobile No</label>
                        <input type="text" name="Mobile_No" id="Mobile_No" class="form-input" maxlength="10" onkeypress="return isNumberKey(event)" required placeholder="09XXXXXXXX">
                        <div class="password-strength">Format: 09XXXXXXXX</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="UserName" class="form-label">Username</label>
                        <input type="text" name="UserName" id="UserName" class="form-input" maxlength="30" onkeypress="return ValidateAlpha(event)" required placeholder="Choose a username">
                    </div>
                    
                    <div class="form-group">
                        <label for="Password" class="form-label">Password</label>
                        <input type="password" name="Password" id="Password" class="form-input" maxlength="30" required placeholder="Enter password">
                        <div class="password-strength">Minimum 8 characters</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="ConfirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" name="ConfirmPassword" id="ConfirmPassword" class="form-input" maxlength="30" required placeholder="Confirm password">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="submit" class="form-button">Submit</button>
                        <button type="reset" name="reset" class="form-button">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            <p>© All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a></p>
        </div>
    </div>

            <script type="text/javascript">
        function checkform(form) {
            // User ID validation
            if (form.User_id.value.trim() == "") {
                alert("Please fill user ID");
                form.User_id.focus();
                return false;
            }

            // Name validation
            if (form.First_Name.value.trim() == "") {
                alert("Please fill first name");
                form.First_Name.focus();
                return false;
            }
            if (form.Last_Name.value.trim() == "") {
                alert("Please fill last name");
                form.Last_Name.focus();
                return false;
            }

            // Selection validation
            if (form.Sex.value == "") {
                alert("Please select Sex");
                form.Sex.focus();
                return false;
            }
            if (form.Role.value == "") {
                alert("Please select Role");
                form.Role.focus();
                return false;
            }

            // Email validation
            if (form.Email.value.trim() == "") {
                alert("Please fill email");
                form.Email.focus();
                return false;
            } else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form.Email.value.trim())) {
                alert("Please enter a valid email address");
                form.Email.focus();
                return false;
            }

            // Phone number validation
            if (form.Mobile_No.value.trim() == "") {
                alert("Please enter phone number");
                form.Mobile_No.focus();
                return false;
            }

            var str = form.Mobile_No.value.trim();
            var valid = "0123456789";
            for (var i = 0; i < str.length; i++) {
                if (valid.indexOf(str.charAt(i)) == -1) {
                    alert("Please enter phone number with only numbers");
                    form.Mobile_No.value = "";
                    form.Mobile_No.focus();
                    return false;
                }
            }

            if (str.length != 10) {
                alert("Please enter a 10-digit phone number");
                form.Mobile_No.focus();
                return false;
            }

            if (str.charAt(0) != "0") {
                alert("Phone number should start with 0");
                form.Mobile_No.focus();
                return false;
            }

            if (str.charAt(1) != "9") {
                alert("Phone number should start with 09");
                form.Mobile_No.focus();
                return false;
            }

            // Username validation
            if (form.UserName.value.trim() == "") {
                alert("Please fill user name");
                form.UserName.focus();
                return false;
            }

            // Password validation
            if (form.Password.value == '') {
                alert("Please enter password");
                form.Password.focus();
                return false;
            } else if (form.Password.value.length < 8) {
                alert("Password is too short! It must contain at least 8 characters");
                form.Password.focus();
                return false;
            } else {
                const passwordValue = form.Password.value;
                const hasLetter = /[a-zA-Z]/.test(passwordValue);
                const hasNumber = /[0-9]/.test(passwordValue);

                if (!hasLetter || !hasNumber) {
                    let message = "Password is long enough, but must also contain";
                    if (!hasLetter && !hasNumber) {
                        message += " both letters and numbers.";
                    } else if (!hasLetter) {
                        message += " at least one letter.";
                    } else { // !hasNumber
                        message += " at least one number.";
                    }
                    alert(message);
                    form.Password.focus();
                    return false;
                }
            }

            if (form.ConfirmPassword.value == '') {
                alert("Please confirm password");
                form.ConfirmPassword.focus();
                return false;
            } else if (form.Password.value != form.ConfirmPassword.value) {
                alert("Password does not match");
                form.ConfirmPassword.focus();
                return false;
            }

            return true;
        }

        function ValidateAlpha(evt) {
            var keyCode = (evt.which) ? evt.which : evt.keyCode;
            if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && keyCode != 8 && keyCode != 9) {
                alert("Only letters and spaces are allowed!");
                return false;
            }
            return true;
        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 8 && charCode != 9 ) {
                alert("Only numbers are allowed!");
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // *** NEW: Function to show a timed notification message ***
            function showTimedNotification(message, type = 'success', duration = 2000) {
                // Remove any existing notification to prevent stacking
                const existingNotification = document.getElementById('custom-timed-notification');
                if (existingNotification) {
                    existingNotification.remove();
                }

                const notification = document.createElement('div');
                notification.id = 'custom-timed-notification';
                notification.textContent = message;

                // Styling for the notification
                notification.style.position = 'fixed';
                notification.style.top = '20px'; // Position from the top
                notification.style.left = '50%'; // Center horizontally
                notification.style.transform = 'translateX(-50%)'; // Adjust for centering
                notification.style.padding = '15px 25px';
                notification.style.color = 'white';
                notification.style.borderRadius = '8px';
                notification.style.boxShadow = '0 5px 15px rgba(0,0,0,0.3)';
                notification.style.zIndex = '10000'; // Ensure it's on top
                notification.style.textAlign = 'center';
                notification.style.fontSize = '1rem'; // Adjust as needed
                notification.style.opacity = '0'; // Start transparent for fade-in
                notification.style.transition = 'opacity 0.3s ease-in-out'; // Smooth fade

                if (type === 'success') {
                    // Use the green color from your CSS variables, with a fallback
                    notification.style.backgroundColor = 'var(--amu-primary, #2e7d32)';
                } else if (type === 'error') {
                    // Use an error color, e.g., your orange, with a fallback
                    notification.style.backgroundColor = 'var(--amu-orange-bright, #FF6F00)';
                } else {
                    notification.style.backgroundColor = '#333'; // Default dark
                }

                document.body.appendChild(notification);

                // Trigger reflow to enable transition for opacity
                void notification.offsetWidth;
                notification.style.opacity = '1'; // Fade in

                // Set timeout to fade out and then remove the notification
                setTimeout(() => {
                    notification.style.opacity = '0'; // Fade out
                    setTimeout(() => { // Wait for fade-out transition to complete
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 300); // This duration should match the opacity transition duration
                }, duration - 300); // Subtract transition time from total duration
            }
            // *** END OF NEW TIMED NOTIFICATION FUNCTION ***

            // Check for account creation status from URL query parameters
            const urlParams = new URLSearchParams(window.location.search);
            const creationStatus = urlParams.get('account_creation_status');

            if (creationStatus === 'success') {
                // *** MODIFIED: Call the new timed notification function ***
                showTimedNotification('Account successfully created!', 'success', 2000); // Message, type, duration in ms

                // Optional: Clean the URL to prevent the message on refresh/back navigation
                if (window.history.replaceState) {
                    const cleanURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({ path: cleanURL }, '', cleanURL);
                }
            } else if (creationStatus === 'error') {
                const errorMessage = urlParams.get('error_message') || 'An unspecified error occurred during account creation.';
                // For errors, you might still prefer a standard alert, or use the timed notification
                // showTimedNotification('Error: ' + decodeURIComponent(errorMessage), 'error', 3000);
                alert('Error creating account: ' + decodeURIComponent(errorMessage));

                if (window.history.replaceState) {
                    const cleanURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({ path: cleanURL }, '', cleanURL);
                }
            }

            const passwordField = document.getElementById('Password');
            if (passwordField) {
                passwordField.addEventListener('input', function() {
                    var strengthTextElement = this.nextElementSibling;
                    if (strengthTextElement && strengthTextElement.classList.contains('password-strength')) {
                        var password = this.value;
                        let baseMessage = '';
                        let finalMessage = '';
                        let color = 'var(--amu-text-secondary)';

                        if (password.length === 0) {
                            baseMessage = 'Minimum 8 characters';
                        } else if (password.length < 8) {
                            baseMessage = 'Too short';
                            color = 'red';
                        } else {
                            if (password.length < 12) {
                                baseMessage = 'Moderate';
                                color = 'orange';
                            } else {
                                baseMessage = 'Strong';
                                color = 'green';
                            }
                            const hasLetter = /[a-zA-Z]/.test(password);
                            const hasNumber = /[0-9]/.test(password);
                            let issues = [];
                            if (!hasLetter) issues.push("letters");
                            if (!hasNumber) issues.push("numbers");

                            if (issues.length > 0) {
                                finalMessage = baseMessage + ` (but needs ${issues.join(' & ')})`;
                                if (color === 'green') color = 'orange';
                                else if (color !== 'red') color = 'orange';
                            } else {
                                finalMessage = baseMessage;
                            }
                        }
                        strengthTextElement.textContent = finalMessage || baseMessage;
                        strengthTextElement.style.color = color;
                    }
                });

                passwordField.addEventListener('blur', function() {
                    var password = this.value;
                    if (password.length >= 8) {
                        const hasLetter = /[a-zA-Z]/.test(password);
                        const hasNumber = /[0-9]/.test(password);
                        let alertMessageParts = [];
                        if (!hasLetter) alertMessageParts.push("at least one letter");
                        if (!hasNumber) alertMessageParts.push("at least one number");

                        if (alertMessageParts.length > 0) {
                            let strengthDescription = "sufficient";
                            if (password.length < 12) strengthDescription = "moderate";
                            else strengthDescription = "strong";
                            alert("Password is " + strengthDescription + " in length, but must also contain " + alertMessageParts.join(" and ") + ".");
                        }
                    }
                });
            }

            // Session check
            fetch('check_login.php')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(data => {
                    if (data.trim().toLowerCase() === 'false') {
                        window.location.href = 'index.html';
                    }
                })
                .catch(error => console.error('Error checking login status:', error));
        });
    </script>
</body>
</html>