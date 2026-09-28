<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// Include the database configuration file
// include("config.php"); // Included later in the body in your original code

// Handle form submission from this page IF it were to submit to itself (currently submits to edit4.php)
// For this display page, we only need to fetch data if Driver_ID is present.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>AMU Fleet Management System - Edit Schedule</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32;
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
            background-color: var(--amu-new-bg); /* MODIFIED */
            padding: 0.8rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            min-width: 160px;
            padding: 0.5rem 0;
            display: none;
            z-index: 1000;
        }

        li:hover > .dropdown {
            display: block;
        }

        .dropdown a {
            padding: 0.8rem 1.2rem;
            text-align: left;
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 1.5rem 5%;
            width: 100%;
            display: flex; /* To center content */
            justify-content: center; /* Center .container */
            align-items: flex-start; /* Align .container to top */
        }

        .container {
            max-width: 800px; /* Adjusted for a form */
            width: 100%;
            margin: 0 auto; /* Fallback centering */
            backdrop-filter: blur(2px);
        }

        /* Form Container */
        .form-container {
            background-color: var(--amu-new-bg); /* MODIFIED */
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0; /* Original margin */
            box-shadow: 0 5px 25px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .form-container h2 {
            color: #fff; /* MODIFIED for contrast */
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.6rem; /* Matching other navy titles */
            position: relative;
            padding-bottom: 0.5rem;
        }
        .form-container h2::after { /* Underline for title */
            content: '';
            position: absolute;
            bottom: 0; /* Adjusted to be directly under padding */
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary); /* Original green underline */
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
            background-color: rgba(0, 0, 0, 0.5); /* Inputs keep their dark bg */
            color: var(--amu-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-group input::placeholder, .form-group select option[value=""] {
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
            margin-bottom: 0; /* Handled by .form-row gap */
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
            background-color: var(--amu-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
        }

        .btn-secondary {
            background-color: #333; /* Original dark grey for reset */
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
            margin-top: auto; /* Push footer to bottom */
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
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .dropdown {
                position: static;
                display: none;
                width: 100%;
            }
            
            li:hover > .dropdown {
                display: block;
            }

            .logo {
                height: 40px;
            }
             header img.logo:last-of-type {
                display: none;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
             .form-row .form-group {
                margin-bottom: 1rem; /* Add margin back when stacked */
            }
        }

        @media (max-width: 480px) {
            main {
                padding: 1rem;
            }

            .form-container {
                padding: 1rem;
            }
             .form-container h2 {
                font-size: 1.4rem;
            }

            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
            .form-actions { /* Stack buttons on very small screens */
                flex-direction: column;
            }
            .btn {
                width: 100%;
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
                <li><a href="scheduler.php"><i class="fas "></i> Home</a></li>
                <li><a href="newsche.php"><i class="fas "></i> Schedule</a></li>
                <li>
                    <a href="#"><i class="fas "></i> View <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="sviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="smessage.php"><i class="fas fa-envelope"></i> View Messages</a></li>
                    </ul>
                </li>
                <li><a href="searchvinfo1.php"><i class="fas "></i> Search Vehicle</a></li>
                <li><a href="changepss.php"><i class="fas "></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas "></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <div class="container">
            <div class="form-container">
                <h2>Edit Vehicle Schedule</h2>
                
                <?php
                // Check if Driver_ID is set in the URL
                if (isset($_GET['Driver_ID'])) {
                    // Include the database configuration file
                    include('config.php'); // Original placement

                    // Check database connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Sanitize the input
                    $Driver_ID = $conn->real_escape_string($_GET['Driver_ID']);
                    // If Plate_no and Date are also part of the unique key for editing (as in sviewschedule.php's edit link)
                    $Plate_no_key = isset($_GET['Plate_no']) ? $conn->real_escape_string($_GET['Plate_no']) : null;
                    $Date_key = isset($_GET['Date']) ? $conn->real_escape_string($_GET['Date']) : null;


                    // Prepare and execute the query
                    // Use all key parts if available to fetch the correct unique record
                    if ($Plate_no_key && $Date_key) {
                         $query = "SELECT * FROM schedule WHERE Driver_ID = ? AND Plate_no = ? AND Date = ?";
                         $stmt = $conn->prepare($query);
                         if (!$stmt) { die("Error in preparing statement: " . $conn->error); }
                         $stmt->bind_param("sss", $Driver_ID, $Plate_no_key, $Date_key);
                    } else { // Fallback if only Driver_ID is passed (might not be unique enough for a schedule)
                        $query = "SELECT * FROM schedule WHERE Driver_ID = ?";
                        $stmt = $conn->prepare($query);
                        if (!$stmt) { die("Error in preparing statement: " . $conn->error); }
                        $stmt->bind_param("s", $Driver_ID);
                    }
                   

                    if (!$stmt->execute()) {
                        die("Error executing statement: " . $stmt->error);
                    }
                    $result = $stmt->get_result();

                    // Check if a record was found
                    if ($result->num_rows > 0) {
                        // Fetch the row
                        $row = $result->fetch_assoc();
                        ?>
                        
                        <form action="edit4.php" method="post" name="abc" onsubmit="return validateForm1()">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="Driver_ID">Driver ID</label>
                                    <input type="text" name="Driver_ID" maxlength="30" value="<?= htmlspecialchars($row['Driver_ID']) ?>" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="Driver_Name">Driver Name</label>
                                    <input type="text" name="Driver_Name" maxlength="30" value="<?= htmlspecialchars($row['Driver_Name']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="Driver_phone_no">Phone Number</label>
                                    <input type="text" name="Driver_phone_no" maxlength="10" placeholder="09XXXXXXXX" value="<?= htmlspecialchars($row['Driver_phone_no']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="Vehicle_type">Vehicle Type</label>
                                    <input type="text" name="Vehicle_type" maxlength="30" value="<?= htmlspecialchars($row['Vehicle_type']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="Plate_no">Plate Number</label>
                                    <input type="text" name="Plate_no" maxlength="30" value="<?= htmlspecialchars($row['Plate_no']) ?>" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="Service_time">Service Time</label>
                                    <select name="Service_time" required>
                                        <option value="">-- Select service time --</option>
                                        <option value="Morning_time" <?php if ($row['Service_time'] == 'Morning_time') echo 'selected'; ?>>Morning Time (8AM - 12PM)</option>
                                        <option value="Lunch_time" <?php if ($row['Service_time'] == 'Lunch_time') echo 'selected'; ?>>Lunch Time (12PM - 2PM)</option>
                                        <option value="Afternoon" <?php if ($row['Service_time'] == 'Afternoon') echo 'selected'; ?>>Afternoon (2PM - 5PM)</option>
                                        <option value="Full_Day" <?php if ($row['Service_time'] == 'Full_Day') echo 'selected'; ?>>Full Day</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="Place_of_start">Place of Start</label>
                                    <input type="text" name="Place_of_start" maxlength="30" value="<?= htmlspecialchars($row['Place_of_start']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="Place_of_arrive">Place of Arrive</label>
                                    <input type="text" name="Place_of_arrive" maxlength="30" value="<?= htmlspecialchars($row['Place_of_arrive']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="Date">Date</label>
                                     <?php 
                                        // Convert DD-MM-YYYY from DB to YYYY-MM-DD for input type="date"
                                        $db_date_formatted = $row['Date'];
                                        $input_date_val = '';
                                        if (preg_match("/^\d{2}-\d{2}-\d{4}$/", $db_date_formatted)) {
                                            $date_parts_val = explode('-', $db_date_formatted);
                                            $input_date_val = $date_parts_val[2] . '-' . $date_parts_val[1] . '-' . $date_parts_val[0];
                                        } elseif (preg_match("/^\d{4}-\d{2}-\d{2}$/", $db_date_formatted)) { // If already YYYY-MM-DD
                                            $input_date_val = $db_date_formatted;
                                        }
                                    ?>
                                    <input type="date" name="Date" value="<?= htmlspecialchars($input_date_val) ?>" required>
                                </div>
                                <div class="form-group">
                                     <label for="For">Purpose (For)</label>
                                    <select name="For" required>
                                        <option value="">-- Select Purpose --</option>
                                        <option value="Employee" <?php if ($row['For'] == 'Employee') echo 'selected'; ?>>Employee</option>
                                        <option value="Student" <?php if ($row['For'] == 'Student') echo 'selected'; ?>>Student</option>
                                        <option value="Official_Duty" <?php if ($row['For'] == 'Official_Duty') echo 'selected'; ?>>Official Duty</option>
                                        <option value="Other" <?php if ($row['For'] == 'Other') echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            
                             <div class="form-row">
                                <div class="form-group">
                                    <label for="Outgoing_time">Outgoing Time</label>
                                    <input type="time" name="Outgoing_time" value="<?= htmlspecialchars($row['Outgoing_time']) ?>" required>
                                </div>
                               <div class="form-group">
                                    <label for="Enterance_time">Return Time (Expected)</label>
                                    <input type="time" name="Enterance_time" value="<?= htmlspecialchars($row['Enterance_time']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                                <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</button>
                            </div>
                        </form>
                        
                        <?php
                    } else {
                        echo '<p style="text-align: center; color: var(--amu-text-secondary);">No record found for the given parameters to edit.</p>';
                    }

                    // Close the statement and connection
                    $stmt->close();
                    if (isset($conn) && $conn instanceof mysqli) { // Check if $conn is still valid
                         $conn->close();
                    }
                } else {
                    echo '<p style="text-align: center; color: var(--amu-text-secondary);">Required parameters (Driver_ID, Plate_no, Date) not provided in the URL to identify the schedule.</p>';
                }
                ?>
            </div>
        </div>
    </main>

    <footer>
        <p>© Copyright © <?= date('Y') ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Vehicle Management Office</a></p>
    </footer>

    <script>
        // Client-side session check
        document.addEventListener('DOMContentLoaded', function() {
            fetch('check_login.php')
                .then(response => response.text())
                .then(data => {
                    if (data === 'false') {
                        window.location.href = 'index.html';
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        function validateForm1() {
            // Add your form validation logic here as from your original 'newsche.php' if needed
            const phoneInput = document.querySelector('input[name="Driver_phone_no"]');
            if (phoneInput && (phoneInput.value.length !== 10 || !phoneInput.value.startsWith('09'))) {
                 alert('Please enter a valid 10-digit Ethiopian phone number starting with 09.');
                 phoneInput.focus();
                 return false;
            }

            const dateInput = document.querySelector('input[name="Date"]');
            if (dateInput && dateInput.value) {
                const selectedDate = new Date(dateInput.value);
                const today = new Date();
                today.setHours(0,0,0,0);
                // For editing, you might allow past dates if the record is old, or add specific logic
                // if (selectedDate < today) {
                //     alert("Schedule date cannot be in the past for new schedules. If editing an old record, this might be fine.");
                //     // form.Date.focus(); // Might not be an error if editing old data
                //     // return false;
                // }
            }

            const outgoingTimeInput = document.querySelector('input[name="Outgoing_time"]');
            const entranceTimeInput = document.querySelector('input[name="Enterance_time"]');
            if (dateInput && dateInput.value && outgoingTimeInput && outgoingTimeInput.value && entranceTimeInput && entranceTimeInput.value) {
                const outgoingDateTime = new Date(dateInput.value + "T" + outgoingTimeInput.value);
                const entranceDateTime = new Date(dateInput.value + "T" + entranceTimeInput.value);
                if (entranceDateTime <= outgoingDateTime) {
                    alert("Return time must be after Outgoing time on the same day.");
                    entranceTimeInput.focus();
                    return false;
                }
            }
            return true;
        }
    </script>
</body>
</html>