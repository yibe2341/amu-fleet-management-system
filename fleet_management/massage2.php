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
    <title>Message Form | AMU Fleet System</title>
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
            max-width: 800px; /* Adjusted max-width for a single form */
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
        .message-form {
            max-width: 650px; /* Slightly wider form */
            margin: 0 auto;
        }

        fieldset {
            border: 1px solid var(--amu-primary-dark); /* Darker border for fieldset */
            border-radius: 8px;
            padding: 20px 25px; /* Increased padding */
            margin-bottom: 15px;
            background-color: rgba(0,0,0,0.1); /* Subtle background for fieldset */
        }

        legend {
            font-weight: bold;
            color: var(--amu-primary);
            padding: 0 10px;
            font-size: 1.2rem; /* Slightly larger legend */
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-table td {
            padding: 10px 0; /* Increased padding */
            vertical-align: top;
        }

        .form-table tr td:first-child { /* Label column */
            width: 25%; /* Adjusted width */
            text-align: right;
            padding-right: 15px; /* Increased padding */
            font-weight: 500;
            color: var(--amu-text-secondary); /* Softer color for labels */
        }

        input[type="text"],
        input[type="tel"],
        textarea,
        select {
            width: 100%;
            padding: 10px 12px; /* Increased padding */
            border-radius: 6px; /* Softer radius */
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: var(--amu-navy-bg-input); /* MODIFIED */
            color: var(--amu-text);
            font-size: 0.95rem; /* Slightly larger font */
        }
        input[type="text"]:focus,
        input[type="tel"]:focus,
        textarea:focus,
        select:focus {
            border-color: var(--amu-primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(46,125,50,0.3);
        }


        textarea {
            min-height: 120px; /* Increased min-height */
            resize: vertical;
        }

        .form-actions {
            text-align: center;
            margin-top: 25px; /* Increased margin */
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        input[type="submit"],
        input[type="reset"] {
            padding: 10px 25px; /* Adjusted padding */
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            /* margin: 0 5px; /* Original, now handled by gap */
            font-size: 0.95rem;
            display: inline-flex; /* For icon alignment */
            align-items: center; /* For icon alignment */
            gap: 8px; /* Space for icon */
        }
        input[type="submit"]:hover,
        input[type="reset"]:hover {
            transform: translateY(-2px);
        }


        input[type="submit"] {
            background-color: var(--amu-primary);
            color: white;
        }

        input[type="submit"]:hover {
            background-color: var(--amu-primary-dark);
        }

        input[type="reset"] {
            background-color: var(--amu-danger);
            color: white;
        }

        input[type="reset"]:hover {
            background-color: var(--amu-danger-dark); /* Using var for consistency */
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
            .form-table tr {
                display: flex;
                flex-direction: column;
                margin-bottom: 10px;
            }
            .form-table tr td:first-child {
                width: 100%;
                text-align: left;
                padding-right: 0;
                padding-bottom: 5px;
            }
            .form-table tr td {
                width: 100%;
            }
             .right_column_section_title {
                font-size: clamp(1.1rem, 4vw, 1.4rem);
            }
             legend {
                font-size: 1.05rem;
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
            
            /* #login_section img styles removed */
        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') { // Robust check
                    window.location.href = 'index.html';
                }
            })
            .catch(error => console.error('Error checking login status:', error));

        function checkform(form) {
            // Phone number validation (pnumber)
            var pnumberValue = form.pnumber.value.trim();
            if(pnumberValue === "") {
                alert("Please enter phone number.");
                form.pnumber.focus();
                return false;
            }
            
            var validChars = /^[0-9+]+$/; // Regex for numbers and +
            if(!validChars.test(pnumberValue)) {
                alert("Please enter only numbers and optionally '+' for phone number.");
                form.pnumber.value = ""; // Clear invalid input
                form.pnumber.focus();
                return false;
            }

            // Specific Ethiopian phone number format (09xxxxxxxx)
            if(!/^09\d{8}$/.test(pnumberValue)) {
                 alert("Phone number must be 10 digits and start with '09'.");
                 form.pnumber.focus();
                 return false;
            }
            
            // Basic check for 'To' field
            if (form.too.value === "") {
                alert("Please select a recipient for 'To'.");
                form.too.focus();
                return false;
            }

            // Basic check for message
            if (form.message.value.trim() === "") {
                alert("Please write a message.");
                form.message.focus();
                return false;
            }

            return true;
        }

        // isNumberKey is still used for onKeyPress, but the main validation is in checkform
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode; // Use evt.keyCode for older browsers
            // Allow numbers, backspace, delete, tab, arrows, home, end
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 43) { // Allow + (charCode 43)
                // alert("Only numbers and '+' are allowed for phone number!"); // Alerting on keypress can be annoying
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
                <li><a href="massage2.php" class="current"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="changepssmechanic.php"><i class="fas fa-key"></i> Password</a></li>
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
                        <i class="fas fa-paper-plane"></i> Send Message
                    </div>
                    <div class="right_column_section_body">
                        <form action="massage.php" method="post" name="form3" onsubmit="return checkform(this);" class="message-form">
                            <fieldset>
                                <legend><i class="fas fa-pen-alt"></i> Write Your Message Here!</legend>
                                <table class="form-table">
                                    <tr>
                                        <td><label for="frm"><i class="fas fa-user-circle"></i> From:</label></td>
                                        <td>
                                            <select name="frm" id="frm" required>
                                                <option value="Mechanic" <?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Mechanic' ? 'selected' : ''); ?> >Mechanic</option>
                                                <!-- Add other roles if a mechanic can send as different roles, or make this readonly/hidden -->
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="too"><i class="fas fa-share-square"></i> To:</label></td>
                                        <td>
                                            <select name="too" id="too" required>
                                                <option value="" selected disabled>-- Select Recipient --</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Driver">Driver</option>
                                                <option value="Scheduler">Scheduler</option>
                                                <!-- Add other user roles as needed -->
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="pnumber"><i class="fas fa-phone"></i> Phone No:</label></td>
                                        <td><input name="pnumber" type="tel" id="pnumber" onkeypress="return isNumberKey(event);" maxlength="10" placeholder="09xxxxxxxx" required /></td>
                                    </tr>
                                    <tr>
                                        <td><label for="message"><i class="fas fa-comment-dots"></i> Message:</label></td>
                                        <td><textarea name="message" id="message" required placeholder="Type your message here..."></textarea></td>
                                    </tr>
                                </table>
                                <div class="form-actions">
                                    <button name="send" type="submit" value="Send"><i class="fas fa-paper-plane"></i> Send</button>
                                    <button name="Clear" type="reset" value="Clear"><i class="fas fa-times-circle"></i> Clear</button>
                                </div>
                            </fieldset>
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