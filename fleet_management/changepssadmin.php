<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
// include('config.php'); // Not needed at the top for this display page logic
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Change Password | AMU Fleet Management</title>
    <meta name="keywords" content="AMU University, Fleet Management, Admin, Password" />
    <meta name="description" content="AMU University Fleet Management System - Change Admin Password" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Added Font Awesome for icons on buttons -->
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-danger: #dc3545; /* For red buttons */
            --amu-new-bg: rgba(26, 35, 126, 0.85); /* Navy Blue */
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
            display: flex; /* For footer positioning */
            flex-direction: column; /* For footer positioning */
        }

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
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
            flex-grow: 1; /* Allows title to take available space */
            margin: 0 15px; /* Adds some spacing around the title */
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap; /* Allow menu items to wrap */
            justify-content: center; /* Center items if they wrap */
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 5px; /* Reduced margin for tighter fit if wrapping */
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 10px; /* Adjusted padding */
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem; /* Slightly smaller font for more items */
            white-space: nowrap;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary); /* Original color */
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.9); /* Submenu background */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
            z-index: 1001; /* Ensure dropdown is above */
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* ===== Main Content Styles ===== */
        #templatemo_content_panel {
            padding: 40px 5%;
            min-height: calc(100vh - 160px); /* Header and Footer height */
            display: flex; /* To center content_section */
            justify-content: center; /* Center content_section */
            align-items: center; /* Vertically center content if it's short */
            backdrop-filter: blur(2px); /* Blur background behind content */
            flex: 1; /* Allow panel to grow */
        }

        #templatemo_content_section {
            display: flex; /* This will now primarily manage .content-right */
            width: 100%;
            max-width: 700px; /* Max width for the form container */
            /* margin: 0 auto; /* Centering handled by parent */
            /* gap: 30px; /* No longer needed as left column is gone */
        }

        /* ===== Left Column Styles Removed ===== */
        /* #templatemo_content_left, #login_section, etc. styles are removed */


        /* ===== Right Column Styles (Now the main content block) ===== */
        #templatemo_content_right {
            flex-grow: 1; /* Take up all available space in content_section */
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* Optional: can be here or on parent */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            width: 100%; /* Ensure it uses full width of its container */
        }

        .right_column_section_title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff; /* MODIFIED: White for better contrast */
            position: relative;
            padding-bottom: 10px;
            text-align: center; /* Center the title */
        }

        .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%; /* Center the underline */
            transform: translateX(-50%); /* Center the underline */
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); /* Original color */
        }

        /* Form Styles */
        .password-form {
            /* background-color: rgba(255, 255, 255, 0.05); /* Removed as parent is navy blue */
            border-radius: 10px; /* Kept if you want inner form slightly rounded */
            padding: 25px;
            color: white;
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(0, 0, 0, 0.3); /* Original dark input background */
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .form-group input::placeholder { /* Style placeholder */
            color: var(--amu-text-secondary);
            opacity: 0.7;
        }


        .form-group input:focus {
            outline: none;
            border-color: var(--amu-primary); /* Original color */
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3); /* Original shadow */
        }

        .form-note {
            text-align: center;
            color: var(--amu-text-secondary);
            margin-top: 20px;
            font-style: italic;
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
            display: inline-flex; /* For icon alignment */
            align-items: center;
            gap: 0.5rem; /* Space between icon and text */
        }

        .btn-primary {
            background-color: var(--amu-primary); /* Original color */
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
        }

        .btn-reset { /* This is the style for the "Clear" button now */
            background-color: var(--amu-danger); /* Red color */
            color: white;
        }

        .btn-reset:hover {
            background-color: #c82333; /* Darker red */
            transform: translateY(-2px);
        }

        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            /* margin-top: auto; /* Ensured by flex on body */
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: #4caf50; /* Original color */
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary); /* Original color */
            text-decoration: underline;
        }

        /* ===== Responsive Adjustments ===== */
        @media (max-width: 992px) {
            /* #templatemo_content_section { flex-direction: column; } /* No longer needed as left is gone */
            /* #templatemo_content_left { width: 100%; } /* Removed */
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
                flex-wrap: wrap;
                justify-content: center;
                flex-direction: column; /* Stack menu on small screens */
                align-items: center;
            }
            
            #templatemo_menu li {
                margin: 5px 0; /* Vertical margin */
            }
            #templatemo_content_panel {
                padding: 20px; /* Adjust padding */
                align-items: flex-start; /* Align form to top on mobile */
            }
            #templatemo_content_right {
                 padding: 20px; /* Adjust padding for smaller screens */
            }

            .right_column_section_title {
                font-size: 1.6rem;
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
   <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Live feedback for New Password field
        const newPasswordField = document.getElementById('newPassword');
        const newPasswordCriteriaDiv = document.getElementById('newPasswordCriteria');

        if (newPasswordField && newPasswordCriteriaDiv) {
            // Set initial message if you want JS to control it fully
            // newPasswordCriteriaDiv.innerHTML = 'Password must be at least 8 characters, including letters and numbers.';
            // newPasswordCriteriaDiv.style.color = 'var(--amu-text-secondary)';


            newPasswordField.addEventListener('input', function() {
                const password = this.value;
                let messages = [];
                
                const minLength = 8;

                if (password.length === 0) {
                    newPasswordCriteriaDiv.innerHTML = 'Password must be at least 8 characters, including letters and numbers.';
                    newPasswordCriteriaDiv.style.color = 'var(--amu-text-secondary)'; // Reset to default color
                    return;
                }

                // 1. Minimum length
                if (password.length >= minLength) {
                    messages.push('<span style="color: #4CAF50;">✓ At least ' + minLength + ' characters</span>'); // Green tick
                } else {
                    messages.push('<span style="color: #f44336;">✗ At least ' + minLength + ' characters</span>'); // Red cross
                }

                // 2. Contains a letter
                if (/[a-zA-Z]/.test(password)) {
                    messages.push('<span style="color: #4CAF50;">✓ Contains a letter (a-z, A-Z)</span>');
                } else {
                    messages.push('<span style="color: #f44336;">✗ Contains a letter (a-z, A-Z)</span>');
                }

                // 3. Contains a number
                if (/[0-9]/.test(password)) {
                    messages.push('<span style="color: #4CAF50;">✓ Contains a number (0-9)</span>');
                } else {
                    messages.push('<span style="color: #f44336;">✗ Contains a number (0-9)</span>');
                }

                newPasswordCriteriaDiv.innerHTML = messages.join('<br>');
                // No need to change overall color here as individual lines are colored
            });
        }

        // Existing optional session check or other DOMContentLoaded logic can go here
        /*
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') {
                    window.location.href = 'index.html';
                }
            });
        */
    });
        
    // Form validation (updated for new password criteria)
    function checkform(form) {
        if (form.UserName.value.trim() === "") {
            alert("Please enter your username");
            form.UserName.focus();
            return false;
        }
        
        if (form.Password.value === "") {
            alert("Please enter your current password");
            form.Password.focus();
            return false;
        }
        
        const newPasswordValue = form.newPassword.value;
        const minPasswordLength = 8;

        if (newPasswordValue === "") {
            alert("Please enter your new password");
            form.newPassword.focus();
            return false;
        }
        
        // New Password criteria checks on submit
        if (newPasswordValue.length < minPasswordLength) {
            alert("New password must be at least " + minPasswordLength + " characters long.");
            form.newPassword.focus();
            return false;
        }
        if (!/[a-zA-Z]/.test(newPasswordValue)) {
            alert("New password must contain at least one letter.");
            form.newPassword.focus();
            return false;
        }
        if (!/[0-9]/.test(newPasswordValue)) {
            alert("New password must contain at least one number.");
            form.newPassword.focus();
            return false;
        }
        // End of New Password criteria checks

        if (form.ConfrimPassword.value === "") { // Note: HTML has "ConfrimPassword"
            alert("Please confirm your new password");
            form.ConfrimPassword.focus();
            return false;
        }
        
        if (newPasswordValue !== form.ConfrimPassword.value) {
            alert("New password and confirmation password do not match");
            form.ConfrimPassword.value = ""; 
            form.ConfrimPassword.focus(); 
            return false;
        }
        
        return true;
    }
    
    // Allow only alphabetic characters for username
    function ValidateAlpha(evt) {
        var keyCode = (evt.which) ? evt.which : evt.keyCode;
        // Allow letters, space, backspace (8), tab (9)
        if (!((keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 123) || keyCode == 32 || keyCode == 8 || keyCode == 9)) {
            // alert("Only letters and spaces are allowed!"); // Kept original comment
            // evt.preventDefault(); // Prevent non-alpha characters - not in original, return false should suffice for onkeypress
            return false;
        }
        return true;
    }
</script>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="Admin.php">Home</a></li>
                <li><a href="#">Account</a>
                    <ul>
                        <li><a href="createaccount.php">Create account</a></li>
                        <li><a href="view.php">Update account</a></li>
                        <li><a href="view2.php">Delete account</a></li>
                    </ul>
                </li>
                <li><a href="aviewschedule.php">View Schedule</a></li>
                <li><a href="upload1.php">Report</a></li>
                <li><a href="changepssadmin.php" class="current">Change Password</a></li> <!-- Assuming current page -->
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <!-- Left Column Removed -->
            <div id="templatemo_content_right">
                <div class="right_column_section_title">Change Password</div>
                <div class="password-form">
                    <p class="form-note">Please fill the form correctly before submitting</p>
                    
                    <form name="htmlform" method="post" action="changepassword1.php" onsubmit="return checkform(this)">
                        <div class="form-group">
                            <label for="UserName"><i class="fas fa-user"></i> Username <span style="color:var(--amu-danger)">*</span></label>
                            <input type="text" name="UserName" id="UserName" maxlength="50" class="form-control" placeholder="Enter your username" onkeypress="return ValidateAlpha(event)" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="Password"><i class="fas fa-lock"></i> Current Password <span style="color:var(--amu-danger)">*</span></label>
                            <input type="password" name="Password" id="Password" maxlength="50" class="form-control" placeholder="Enter your current password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="newPassword"><i class="fas fa-lock"></i> New Password <span style="color:var(--amu-danger)">*</span></label>
                            <input type="password" name="newPassword" id="newPassword" maxlength="80" class="form-control" placeholder="Enter your new password (min 8 chars)" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ConfrimPassword"><i class="fas fa-lock"></i> Confirm New Password <span style="color:var(--amu-danger)">*</span></label>
                            <input type="password" name="ConfrimPassword" id="ConfrimPassword" maxlength="30" class="form-control" placeholder="Confirm your new password" required>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                            <button type="reset" class="btn btn-reset"><i class="fas fa-undo"></i> Clear</button> <!-- Matched "Clear" button style -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            Copyright © <?php echo date('Y'); ?> <a href="#">AMU University</a> | <a href="http://www.AMU.edu.et" target="_blank">AMU Vehicle Management Office</a>
        </div>
    </div>
</body>
</html>