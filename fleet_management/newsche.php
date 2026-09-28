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
    <title>AMU Fleet Management System</title>
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

        /* Header Styles - Matched original colors */
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

        /* Navigation - Matched original colors */
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

        /* Dropdown - Matched original colors */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 0 0 4px 4px;
            min-width: 160px;
            padding: 0.5rem 0;
            display: none;
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Vehicle List Section - Matched original colors */
        .vehicle-list {
            background-color: var(--amu-new-bg); /* MODIFIED */
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            overflow-x: auto;
        }

        .vehicle-list h2 {
            color: #fff; /* MODIFIED for contrast */
            margin-bottom: 1rem;
            text-align: center;
            font-size: 1.4rem;
        }

        .vehicle-list p.info {
            text-align: center;
            margin-bottom: 1rem;
            color: var(--amu-text-secondary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        th, td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            /* background-color: rgba(0, 0, 0, 0.5); /* Original Table Header BG */
            background-color: var(--amu-primary); /* Using primary color as in other tables */
            color: white; /* Ensure contrast */
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .action-btn {
            color: var(--amu-primary);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 0.3rem;
        }

        .action-btn:hover {
            color: var(--amu-primary-dark);
            transform: scale(1.1);
        }

        /* Schedule Form - Matched original colors */
        .schedule-form {
            background-color: var(--amu-new-bg); /* MODIFIED */
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .schedule-form h2 {
            color: #fff; /* MODIFIED for contrast */
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.4rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            background-color: rgba(0, 0, 0, 0.5); /* Keeping input fields dark */
            color: var(--amu-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus {
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
        }

        .btn-primary {
            background-color: var(--amu-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
        }

        .btn-secondary {
            background-color: #333;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #444;
        }

        /* Footer - Matched original colors */
        footer {
            background-color: var(--amu-new-bg); /* MODIFIED */
            padding: 1rem 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
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
        @media (max-width: 992px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            .form-row .form-group { /* Add margin back when stacked */
                 margin-bottom: 1rem;
            }
        }

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
             header img.logo:last-of-type { /* Hide second logo on mobile if too cluttered */
                display: none;
            }
        }

        @media (max-width: 576px) {
            main {
                padding: 1rem;
            }

            .vehicle-list, .schedule-form {
                padding: 1rem;
            }

            th, td {
                padding: 0.5rem;
                font-size: 0.9rem;
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

        // Form validation functions
        function checkform(form) {
            if (form.Driver_ID.value === "") {
                alert("Please fill Driver ID");
                form.Driver_ID.focus();
                return false;
            }
            if (form.Driver_Name.value === "") {
                alert("Please fill Driver Name");
                form.Driver_Name.focus();
                return false;
            }
            if (form.Driver_phone_no.value === "") {
                alert("Please enter phone number");
                form.Driver_phone_no.value = "";
                form.Driver_phone_no.focus();
                return false;
            }
            
            var str = form.Driver_phone_no.value;
            var valid = "0123456789+";
            for (var i = 0; i < str.length; i++) {
                if (valid.indexOf(str.charAt(i)) === -1) {
                    alert("Please insert phone number only with digits");
                    form.Driver_phone_no.value = "";
                    form.Driver_phone_no.focus();
                    return false;
                }
            }
            
            if (str.length !== 10) {
                alert("Please enter 10 digit phone number");
                form.Driver_phone_no.focus();
                return false;
            }
            
            if (str.charAt(0) !== "0") {
                alert("Phone number should start with 0");
                form.Driver_phone_no.focus();
                return false;
            }
            
            if (str.charAt(1) !== "9") {
                alert("Phone number should start with 09");
                form.Driver_phone_no.focus();
                return false;
            }

            if (form.Vehicle_type.value === "") {
                alert("Please fill Vehicle type");
                form.Vehicle_type.focus();
                return false;
            }
            
            if (form.Plate_no.value === "") {
                alert("Please fill Plate number");
                form.Plate_no.focus();
                return false;
            }
            
            if (form.Place_of_start.value === "") {
                alert("Please fill Place of start");
                form.Place_of_start.focus();
                return false;
            }
            
            if (form.Place_of_arrive.value === "") {
                alert("Please fill Place of arrive");
                form.Place_of_arrive.focus();
                return false;
            }
            
            if (form.Service_time.value === "") {
                alert("Please select service time");
                form.Service_time.focus();
                return false;
            }
            
            if (form.Date.value === "") {
                alert("Please fill Date");
                form.Date.focus();
                return false;
            }
             // Validate date is not in the past
            var selectedDate = new Date(form.Date.value);
            var today = new Date();
            today.setHours(0,0,0,0); // Compare dates only, not time
            if (selectedDate < today) {
                alert("Schedule date cannot be in the past.");
                form.Date.focus();
                return false;
            }
            
            if (form.Enterance_time.value === "") {
                alert("Please fill Entrance time"); // This is labeled "Return Time" in your form
                form.Enterance_time.focus();
                return false;
            }
            
            if (form.Outgoing_time.value === "") {
                alert("Please fill Outgoing time");
                form.Outgoing_time.focus();
                return false;
            }
             // Validate Outgoing_time is not before Enterance_time on the same day
            if (form.Date.value !== "" && form.Enterance_time.value !== "" && form.Outgoing_time.value !== "") {
                var entrance = new Date(form.Date.value + "T" + form.Enterance_time.value); // This is Return Time
                var outgoing = new Date(form.Date.value + "T" + form.Outgoing_time.value);
                if (outgoing >= entrance) { // Outgoing time should be BEFORE return time
                    alert("Outgoing time must be before Return time.");
                    form.Outgoing_time.focus();
                    return false;
                }
            }
             if (form.For.value === "") { // Added validation for "For" field
                alert("Please select the purpose (For Employee/Student/etc.)");
                form.For.focus();
                return false;
            }
            
            return true;
        }

        function ValidateAlpha(evt) {
            var keyCode = (evt.which) ? evt.which : evt.keyCode;
            if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && keyCode != 8 && keyCode != 9) {
                // alert("Only letters are allowed!"); // Kept commented as per original
                return false;
            }
            return true;
        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !==8 && charCode !==9) { // Allow backspace & tab
                // alert("Only numbers are allowed!"); // Kept commented as per original
                return false;
            }
            return true;
        }

        // function printpage() { // Not used in this layout
        //    window.print();
        // }
    </script>
</head>
<body>
    <header>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
        <h1 class="site-title">AMU FLEET MANAGEMENT SYSTEM</h1>
        <nav>
            <ul>
                <li><a href="scheduler.php" class="current"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="newsche.php"><i class="fas fa-calendar-plus"></i> Schedule</a></li>
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
            <?php
            include("config.php"); // Included as per original

            // Check connection
            if ($conn->connect_error) {
                die("<p style='text-align:center; color: var(--amu-danger);'>Connection failed: " . $conn->connect_error . "</p>");
            }

            $query = "SELECT Vehicle_id, PlateNo, VehicleType, Model, ChessisNo, Capacity, ProductionDate, EngineNo, EnginePower, Owner FROM vehicles"; // Explicitly list columns
            $result = $conn->query($query);

            $count = 0;
            $vehicles = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $vehicles[] = $row;
                }
                $count = count($vehicles); // More reliable count
            } else {
                echo '<script language="javascript">';
                echo 'alert("Error fetching vehicle data: ' . htmlspecialchars($conn->error) . '")'; // Be cautious with direct error output
                echo '</script>';
            }
            ?>

            <section class="vehicle-list">
                <h2>Registered Vehicles</h2>
                <?php if ($count > 0): ?>
                    <p class="info">Available vehicles to be scheduled: <?php echo $count; ?> vehicle<?php echo ($count > 1 ? 's' : ''); ?></p>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Vehicle ID</th>
                                    <th>Plate No</th>
                                    <th>Vehicle Type</th>
                                    <th>Model</th>
                                    <th>Chassis No</th>
                                    <th>Capacity</th>
                                    <th>Prod. Date</th>
                                    <th>Engine No</th>
                                    <th>Eng. Power</th>
                                    <th>Owner</th>
                                    <th class="action-cell">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vehicles as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row["Vehicle_id"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["PlateNo"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["VehicleType"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["Model"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["ChessisNo"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["Capacity"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["ProductionDate"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["EngineNo"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["EnginePower"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["Owner"]); ?></td>
                                        <td class="action-cell">
                                            <a href="newsche.php?fid=<?php echo urlencode($row["PlateNo"]); ?>#schedule-form-anchor" 
                                               class="action-btn" 
                                               title="Schedule vehicle: <?php echo htmlspecialchars($row["PlateNo"]); ?>"
                                               onClick="return confirm('Do you want to schedule vehicle <?php echo htmlspecialchars(addslashes($row["PlateNo"])); ?>?');">
                                                <i class="fas fa-calendar-check"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="info">No registered vehicles available for scheduling</p>
                <?php endif; ?>
            </section>

            <?php if (isset($_GET['fid']) && !empty($_GET['fid'])): 
                $lid = $conn->real_escape_string($_GET['fid']);
                // Optionally, fetch vehicle type based on $lid to pre-fill if needed
                $vehicle_type_prefill = '';
                $vehicle_details_query = "SELECT VehicleType FROM vehicles WHERE PlateNo = ?";
                $stmt_vehicle = $conn->prepare($vehicle_details_query);
                if($stmt_vehicle) {
                    $stmt_vehicle->bind_param("s", $lid);
                    $stmt_vehicle->execute();
                    $result_vehicle = $stmt_vehicle->get_result();
                    if($result_vehicle->num_rows > 0){
                        $vehicle_row = $result_vehicle->fetch_assoc();
                        $vehicle_type_prefill = $vehicle_row['VehicleType'];
                    }
                    $stmt_vehicle->close();
                }
            ?>
                <section class="schedule-form" id="schedule-form-anchor">
                    <h2>Schedule Form for Vehicle: <?php echo htmlspecialchars($lid); ?></h2>
                    <form name="scheduleForm" method="post" action="schedulevehicle.php" onsubmit="return checkform(this)">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="Driver_ID">Driver ID <span style="color:var(--amu-danger)">*</span></label>
                                <input type="text" name="Driver_ID" id="Driver_ID" maxlength="50" required placeholder="Enter Driver ID">
                            </div>
                            <div class="form-group">
                                <label for="Driver_Name">Driver Name <span style="color:var(--amu-danger)">*</span></label>
                                <input type="text" name="Driver_Name" id="Driver_Name" maxlength="80" required onkeypress="return ValidateAlpha(event)" placeholder="Enter Driver Name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="Driver_phone_no">Driver Phone No <span style="color:var(--amu-danger)">*</span></label>
                                <input type="text" name="Driver_phone_no" id="Driver_phone_no" maxlength="10" required onkeypress="return isNumberKey(event)" placeholder="09XXXXXXXX">
                            </div>
                            <div class="form-group">
                                <label for="Vehicle_type">Vehicle Type <span style="color:var(--amu-danger)">*</span></label>
                                <input type="text" name="Vehicle_type" id="Vehicle_type" maxlength="50" required value="<?php echo htmlspecialchars($vehicle_type_prefill); ?>" placeholder="Enter Vehicle Type">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="Plate_no">Plate No <span style="color:var(--amu-danger)">*</span></label>
                                <input type="text" name="Plate_no" id="Plate_no" maxlength="30" required value="<?php echo htmlspecialchars($lid); ?>" readonly>
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
                            <div class="form-group">
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
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit Schedule</button>
                            <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Clear Form</button>
                        </div>
                    </form>
                </section>
            <?php 
                endif; // End of if(isset($_GET['fid']))
            if (isset($conn) && $conn instanceof mysqli) { $conn->close(); } // Close connection at the end of PHP block
            ?>
        </div>
    </main>

    <footer>
        <p>© Copyright <?php echo date('Y'); ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Vehicle Management Office</a></p>
    </footer>
</body>
</html>