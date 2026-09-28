<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// Connect to the database
include('config.php'); // Ensure this doesn't output anything before headers

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = '';
$message_class = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $Driver_ID = mysqli_real_escape_string($conn, $_POST['Driver_ID']);
    $Driver_Name = mysqli_real_escape_string($conn, $_POST['Driver_Name']);
    $Driver_phone_no = mysqli_real_escape_string($conn, $_POST['Driver_phone_no']);
    $Vehicle_type = mysqli_real_escape_string($conn, $_POST['Vehicle_type']);
    $Plate_no = mysqli_real_escape_string($conn, $_POST['Plate_no']);
    $Place_of_start = mysqli_real_escape_string($conn, $_POST['Place_of_start']);
    $Place_of_arrive = mysqli_real_escape_string($conn, $_POST['Place_of_arrive']);
    $Service_time = mysqli_real_escape_string($conn, $_POST['Service_time']);
    $Date = date('d-m-Y'); // Original date format
    $Enterance_time = mysqli_real_escape_string($conn, $_POST['Enterance_time']);
    $Outgoing_time = mysqli_real_escape_string($conn, $_POST['Outgoing_time']);
    $For = mysqli_real_escape_string($conn, $_POST['For']);

    // Check if the vehicle is already scheduled
    // Consider adding a date check here too if a vehicle can be scheduled for the same service time on different dates
    $query = "SELECT * FROM schedule WHERE Plate_no = ? AND Service_time = ? AND Date = ?"; // Added Date to the check
    $stmt_check = $conn->prepare($query); // Use a different variable name
    if (!$stmt_check) {
        // Log error instead of die() for better user experience if possible
        error_log("Error in preparing SELECT statement: " . $conn->error);
        $message = "Error checking schedule. Please try again.";
        $message_class = "error";
    } else {
        $stmt_check->bind_param("sss", $Plate_no, $Service_time, $Date); // Bind the date
        if (!$stmt_check->execute()) {
            error_log("Error executing SELECT statement: " . $stmt_check->error);
            $message = "Error checking existing schedule. Please try again.";
            $message_class = "error";
        } else {
            $result_check = $stmt_check->get_result(); // Use a different variable name
            $count = $result_check->num_rows;

            if ($count != 0) {
                $message = "Sorry! This vehicle is already scheduled for this service time on this date.";
                $message_class = "error";
            } else {
                // Insert the new schedule into the database
                $sql = "INSERT INTO schedule (Driver_ID, Driver_Name, Driver_phone_no, Vehicle_type, Plate_no, Place_of_start, Place_of_arrive, Service_time, Date, Enterance_time, Outgoing_time, `For`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Backticks for `For`
                $stmt_insert = $conn->prepare($sql); // Use a different variable name
                if (!$stmt_insert) {
                    error_log("Error in preparing INSERT statement: " . $conn->error);
                    $message = "Error scheduling vehicle. Please try again.";
                    $message_class = "error";
                } else {
                    $stmt_insert->bind_param("ssssssssssss", $Driver_ID, $Driver_Name, $Driver_phone_no, $Vehicle_type, $Plate_no, $Place_of_start, $Place_of_arrive, $Service_time, $Date, $Enterance_time, $Outgoing_time, $For);

                    if ($stmt_insert->execute()) {
                        $message = "Vehicle scheduled successfully!";
                        $message_class = "success";
                    } else {
                        error_log("Error executing INSERT statement: " . $stmt_insert->error);
                        $message = "Error: " . htmlspecialchars($stmt_insert->error); // Show specific error cautiously
                        $message_class = "error";
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_check->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>AMU Fleet Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32; /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
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
            box-shadow: 0 2px 15px rgba(0,0,0,0.4); /* Added for consistency */
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
            background-color: var(--amu-primary); /* Original color */
        }

        /* Dropdown */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.9); /* Original color */
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
            align-items: flex-start; /* Align content to top */
        }

        .container {
            max-width: 800px; /* Adjusted max-width for a single form container */
            width: 100%; /* Take available width */
            margin: 0 auto; /* Center if parent doesn't use flex for centering */
            backdrop-filter: blur(2px); /* Blur background behind content */
        }

        /* Form Container */
        .form-container {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0; /* Consistent margin */
            box-shadow: 0 5px 25px rgba(0,0,0,0.5); /* Consistent shadow */
            border: 1px solid rgba(255,255,255,0.1); /* Consistent border */
        }

        .form-container h2 {
            color: #fff; /* MODIFIED: White for better contrast on navy */
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.6rem; /* Slightly larger title */
            position: relative;
            padding-bottom: 0.5rem;
        }
        .form-container h2::after { /* Underline for title */
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary); /* Original green */
        }


        /* Message Styles */
        .message {
            padding: 1rem;
            margin: 1rem auto; /* Center message block */
            border-radius: 4px;
            text-align: center;
            max-width: 100%; /* Allow full width within container */
            border: 1px solid;
            color: var(--amu-text); /* Ensure text is light for contrast */
        }

        .message.success {
            background-color: rgba(46, 125, 50, 0.2); /* Lighter green */
            border-color: var(--amu-primary);
        }

        .message.error {
            background-color: rgba(211, 47, 47, 0.2); /* Lighter red */
            border-color: #d32f2f;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--amu-text);
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="time"],
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            background-color: rgba(0, 0, 0, 0.5); /* Inputs have their own dark bg */
            color: var(--amu-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-group input::placeholder, .form-group select option[value=""] { /* Style placeholder and default select */
            color: var(--amu-text-secondary);
            opacity: 0.7;
        }


        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--amu-primary);
        }

        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0; /* Remove margin as form-row handles it */
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex; /* For icon alignment */
            align-items: center;
            gap: 0.5rem; /* Space between icon and text */
        }

        .btn-primary {
            background-color: var(--amu-primary); /* Original green */
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
        }

        .btn-secondary { /* Original style for "Clear" button */
            background-color: #333; 
            color: white;
        }

        .btn-secondary:hover {
            background-color: #444;
        }

        /* Footer */
        footer {
            background-color: var(--amu-new-bg); /* MODIFIED */
            padding: 1rem 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
            margin-top: auto; /* Pushes footer to bottom */
        }

        footer a {
            color: var(--amu-primary); /* Original green */
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
            }
            .form-container {
                padding: 1rem;
            }
             .form-container h2 {
                font-size: 1.4rem;
            }
            .form-row {
                flex-direction: column;
                gap: 0; /* Remove gap when stacked */
            }
             .form-row .form-group {
                margin-bottom: 1rem; /* Add margin back for stacked groups */
            }
        }

        @media (max-width: 480px) {
            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
                width: 100%; /* Make buttons full width if needed */
            }
            .form-actions {
                flex-direction: column; /* Stack buttons */
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
                <li><a href="newsche.php" class="current"><i class="fas fa-calendar-plus"></i> Schedule</a></li> <!-- Assuming current page -->
                <li>
                    <a href="#"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="sviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="smessage.php"><i class="fas fa-envelope"></i> View Messages</a></li>
                    </ul>
                </li>
                <li><a href="searchvinfo1.php"><i class="fas fa-search"></i> Search Vehicle</a></li>
                <li><a href="changepss.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <div class="container">
            <div class="form-container">
                <h2>Schedule Vehicle</h2>
                
                <?php if (!empty($message)): // Check if message is not empty before displaying ?>
                    <div class="message <?= htmlspecialchars($message_class) // Escape class for security ?>">
                        <?= htmlspecialchars($message) // Escape message for security ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="Driver_ID">Driver ID <span style="color:var(--amu-danger)">*</span></label>
                            <input type="text" name="Driver_ID" id="Driver_ID" maxlength="50" required placeholder="Enter Driver ID">
                        </div>
                        <div class="form-group">
                            <label for="Driver_Name">Driver Name <span style="color:var(--amu-danger)">*</span></label>
                            <input type="text" name="Driver_Name" id="Driver_Name" maxlength="80" required placeholder="Enter Driver Name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="Driver_phone_no">Driver Phone No <span style="color:var(--amu-danger)">*</span></label>
                            <input type="text" name="Driver_phone_no" id="Driver_phone_no" maxlength="10" required placeholder="09XXXXXXXX">
                        </div>
                        <div class="form-group">
                            <label for="Vehicle_type">Vehicle Type <span style="color:var(--amu-danger)">*</span></label>
                            <input type="text" name="Vehicle_type" id="Vehicle_type" maxlength="50" required placeholder="e.g., Pickup, Minibus">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="Plate_no">Plate No <span style="color:var(--amu-danger)">*</span></label>
                            <input type="text" name="Plate_no" id="Plate_no" maxlength="30" required value="<?php echo isset($_GET['fid']) ? htmlspecialchars($_GET['fid']) : ''; ?>" placeholder="Enter Plate Number">
                        </div>
                        <div class="form-group">
                            <label for="Service_time">Service Time <span style="color:var(--amu-danger)">*</span></label>
                            <select name="Service_time" id="Service_time" required>
                                <option value="">-- Select service time --</option>
                                <option value="Morning_time">Morning Time (8AM - 12PM)</option>
                                <option value="Lunch_time">Lunch Time (12PM - 2PM)</option>
                                <option value="Afternoon">Afternoon (2PM - 5PM)</option>
                                <option value="Full_Day">Full Day</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="Place_of_start">Place of Start <span style="color:var(--amu-danger)">*</span></label>
                            <input type="text" name="Place_of_start" id="Place_of_start" maxlength="30" required placeholder="Enter Starting Place">
                        </div>
                        <div class="form-group">
                            <label for="Place_of_arrive">Place of Arrive <span style="color:var(--amu-danger)">*</span></label>
                            <input type="text" name="Place_of_arrive" id="Place_of_arrive" maxlength="30" required placeholder="Enter Destination">
                        </div>
                    </div>

                    <div class="form-row">
                         <div class="form-group"> <!-- Date was missing from your original HTML form structure in this file -->
                            <label for="Date">Date <span style="color:var(--amu-danger)">*</span></label>
                            <input type="date" name="Date" id="Date" required>
                        </div>
                        <div class="form-group">
                            <label for="For">Purpose (For) <span style="color:var(--amu-danger)">*</span></label>
                            <select name="For" id="For" required>
                                <option value="">-- Select Purpose --</option>
                                <option value="Employee">Employee</option>
                                <option value="Student">Student</option>
                                <option value="Official_Duty">Official Duty</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="Outgoing_time">Outgoing Time <span style="color:var(--amu-danger)">*</span></label>
                            <input type="time" name="Outgoing_time" id="Outgoing_time" required>
                        </div>
                        <div class="form-group">
                             <label for="Enterance_time">Return Time (Expected) <span style="color:var(--amu-danger)">*</span></label>
                            <input type="time" name="Enterance_time" id="Enterance_time" required>
                        </div>
                    </div>


                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>© Copyright © <?= date('Y') ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a></p>
    </footer>

    <script>
        // Client-side session check (optional)
        /*
        document.addEventListener('DOMContentLoaded', function() {
            fetch('check_login.php')
                .then(response => response.text())
                .then(data => {
                    if (data.trim().toLowerCase() === 'false') {
                        window.location.href = 'index.html';
                    }
                })
                .catch(error => console.error('Error checking login status:', error));
        });
        */

        // Phone number validation
        const phoneInput = document.querySelector('input[name="Driver_phone_no"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
            });
        }


        // Form validation
        const scheduleForm = document.querySelector('form[action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"]');
        if (scheduleForm) {
            scheduleForm.addEventListener('submit', function(e) {
                const phone = document.querySelector('input[name="Driver_phone_no"]').value;
                if (phone.length !== 10 || !phone.startsWith('09')) {
                    alert('Please enter a valid 10-digit Ethiopian phone number starting with 09.');
                    e.preventDefault();
                    document.querySelector('input[name="Driver_phone_no"]').focus();
                    return;
                }

                const dateInput = document.querySelector('input[name="Date"]');
                if (dateInput && dateInput.value) {
                    const selectedDate = new Date(dateInput.value);
                    const today = new Date();
                    today.setHours(0,0,0,0);
                    if (selectedDate < today) {
                        alert("Schedule date cannot be in the past.");
                        e.preventDefault();
                        dateInput.focus();
                        return;
                    }
                }

                const outgoingTimeInput = document.querySelector('input[name="Outgoing_time"]');
                const entranceTimeInput = document.querySelector('input[name="Enterance_time"]'); // This is Return Time
                if (dateInput && dateInput.value && outgoingTimeInput && outgoingTimeInput.value && entranceTimeInput && entranceTimeInput.value) {
                    const outgoingDateTime = new Date(dateInput.value + "T" + outgoingTimeInput.value);
                    const entranceDateTime = new Date(dateInput.value + "T" + entranceTimeInput.value);
                    if (entranceDateTime <= outgoingDateTime) {
                        alert("Return time must be after Outgoing time on the same day.");
                        e.preventDefault();
                        entranceTimeInput.focus();
                        return;
                    }
                }

                // Add other field checks from your original checkform() if needed
                const requiredFields = ['Driver_ID', 'Driver_Name', 'Vehicle_type', 'Plate_no', 'Place_of_start', 'Place_of_arrive', 'Service_time', 'Date', 'For', 'Outgoing_time', 'Enterance_time'];
                for (let fieldName of requiredFields) {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field && field.value.trim() === "") {
                        alert(`Please fill the "${field.previousElementSibling.textContent.replace('*','').trim()}" field.`);
                        e.preventDefault();
                        field.focus();
                        return;
                    }
                }

            });
        }
    </script>
</body>
</html>
<?php
// Close the database connection
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>