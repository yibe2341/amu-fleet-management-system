<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// error_reporting(E_ALL); // Uncomment for development
// ini_set('display_errors', 1); // Uncomment for development

include_once('config.php'); // Ensure $conn is established and included only once

// Initialize variables for form pre-filling
$prefill_plate_no = '';
$prefill_start_time = '';
$prefill_return_time = '';
$prefill_permission_status = ''; // To pre-select if editing an existing permission
$original_request_date = ''; // Will hold the date from GET
$req_id = ''; // Will hold the original request id from GET

$form_action_params = ''; // To build GET params for form action

// --- Logic for GET request (Displaying the form) ---
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['fid']) && isset($_GET['req_id']) && isset($_GET['date'])) {
        $prefill_plate_no = htmlspecialchars($_GET['fid']);
        $original_request_date = htmlspecialchars($_GET['date']); // Store for form action and potential use
        $req_id = htmlspecialchars($_GET['req_id']);

        // Persist GET parameters in the form action URL
        $form_action_params = sprintf("?fid=%s&req_id=%s&date=%s",
            urlencode($_GET['fid']),
            urlencode($_GET['req_id']),
            urlencode($_GET['date'])
        );

        if ($conn && !$conn->connect_error) {
            // Fetch Start_time and Return_time from the original exit1 request
            $stmt_fetch_exit_details = $conn->prepare(
                "SELECT Start_time, Return_time FROM exit1 WHERE id = ? AND Car_id = ? AND Date = ?"
            );
            if ($stmt_fetch_exit_details) {
                $stmt_fetch_exit_details->bind_param("iss", $_GET['req_id'], $_GET['fid'], $_GET['date']);
                $stmt_fetch_exit_details->execute();
                $result_exit_details = $stmt_fetch_exit_details->get_result();
                if ($row_exit = $result_exit_details->fetch_assoc()) {
                    $prefill_start_time = htmlspecialchars($row_exit['Start_time']);
                    $prefill_return_time = htmlspecialchars($row_exit['Return_time']);
                } else {
                    error_log("exitrequest11.php: No matching exit request found for prefill: req_id=" . $_GET['req_id']);
                }
                $stmt_fetch_exit_details->close();
            } else {
                error_log("exitrequest11.php: Error preparing stmt to fetch exit details: " . $conn->error);
            }

            // Check if there's an existing permission record to pre-fill status (Permitted/Not Permitted)
            $stmt_fetch_perm = $conn->prepare(
                "SELECT Permission, Start_time, Return_time FROM permission WHERE Plate_no = ? AND Date = ?"
            );
            if ($stmt_fetch_perm) {
                $stmt_fetch_perm->bind_param("ss", $_GET['fid'], $_GET['date']);
                $stmt_fetch_perm->execute();
                $result_perm = $stmt_fetch_perm->get_result();
                if ($row_perm = $result_perm->fetch_assoc()) {
                    $prefill_permission_status = htmlspecialchars($row_perm['Permission']);
                    // If permission exists, use its times for prefill, otherwise use exit1 times
                    $prefill_start_time = htmlspecialchars($row_perm['Start_time']);
                    $prefill_return_time = htmlspecialchars($row_perm['Return_time']);
                }
                $stmt_fetch_perm->close();
            } else {
                error_log("exitrequest11.php: Error preparing stmt to fetch permission: " . $conn->error);
            }
        } else {
            $db_error_get = isset($conn) ? $conn->connect_error : "Connection object not established for GET.";
            error_log("exitrequest11.php: DB connection error in GET: " . $db_error_get);
            // Potentially show an error message on the page
        }
        // $conn will be closed at the end of the script if it's not a POST request that closes it earlier
    } else {
        // Missing critical GET parameters - the form area will show an error message.
    }
}


// --- Handle permission notification form submission (POST request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$conn || $conn->connect_error) {
        $db_error_post = isset($conn) ? $conn->connect_error : "Connection object not established or lost before POST.";
        error_log("exitrequest11.php: DB connection error in POST: " . $db_error_post);
        echo "<script>alert('Database connection error. Please try again later.'); window.history.back();</script>";
        exit();
    }

    // Critical: Ensure original_request_date is available from GET params in form action
    if (!isset($_GET['date']) || !isset($_GET['fid'])) { // req_id is good to have but date and fid are primary for permission table
        error_log("exitrequest11.php: Critical context parameters (fid, date) missing in POST request's form action URL.");
        echo "<script>alert('Error: Form context is missing. Please try again from the requests list.'); window.location.href = 'exitrequest1.php';</script>";
        exit();
    }

    // Sanitize form inputs
    $Plate_no = mysqli_real_escape_string($conn, $_POST['Plate_no']); // This should match $_GET['fid']
    $Start_time = mysqli_real_escape_string($conn, $_POST['Start_time']);
    $Return_time = mysqli_real_escape_string($conn, $_POST['Return_time']);
    $permission_status = mysqli_real_escape_string($conn, $_POST['Permission']); // e.g., "Permitted" or "Not Permitted"
    
    // **CRITICAL CHANGE: Use the original request date passed via GET param in form action**
    $Original_Date_From_Request = mysqli_real_escape_string($conn, $_GET['date']);

    // UPSERT Logic: Insert or Update
    // Using INSERT ... ON DUPLICATE KEY UPDATE requires a UNIQUE key on (Plate_no, Date) in 'permission' table.
    // ALTER TABLE permission ADD UNIQUE INDEX `idx_plate_date` (`Plate_no`, `Date`);
    $upsert_query = "INSERT INTO permission (Plate_no, Start_time, Return_time, Permission, Date) 
                     VALUES (?, ?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE 
                        Start_time = VALUES(Start_time), 
                        Return_time = VALUES(Return_time), 
                        Permission = VALUES(Permission)";
    
    $stmt = $conn->prepare($upsert_query);

    if ($stmt) {
        // Bind parameters: Plate_no, Start_time, Return_time, Permission_Status, Original_Request_Date
        $stmt->bind_param("sssss", $Plate_no, $Start_time, $Return_time, $permission_status, $Original_Date_From_Request);

        if ($stmt->execute()) {
            $message = ($stmt->affected_rows === 1) ? "Permission notification submitted successfully!" : "Permission notification updated successfully!";
            if ($stmt->affected_rows === 0) { // Occurs if update makes no changes to existing data
                $message = "Permission status remains unchanged (no new data).";
            }
            echo "<script>alert('" . $message . "');
            window.location.href = 'exitrequest1.php'; // Redirect to view exit requests page
            </script>";
            // $stmt->close(); // Closed below
            // if ($conn) $conn->close(); // Closed below
            // exit(); // Done by script termination after successful redirect
        } else {
            error_log("exitrequest11.php: Error submitting permission (execute): " . $stmt->error . " for query: " . $upsert_query . " with Plate: $Plate_no, Date: $Original_Date_From_Request");
            echo "<script>alert('Error submitting permission notification. Please check logs and try again.'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        error_log("exitrequest11.php: Error preparing UPSERT statement: " . $conn->error . " for query: " . $upsert_query);
        echo "<script>alert('Error preparing statement. Please contact support.'); window.history.back();</script>";
    }
    
    if ($conn) $conn->close(); // Close connection after POST processing
    exit(); // Ensure script terminates after POST handling
}
// If script reaches here, it's a GET request. The connection ($conn) is still open if successfully established.
// It will be closed at the very end of the script for GET requests.
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Permission Form | AMU Fleet System</title>
    <meta name="keywords" content="AMU University, Fleet Management, Permission Form" />
    <meta name="description" content="AMU University Fleet Management System - Permission Form" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        /* styles from your provided code */
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-danger: #dc3545; 
        }
        body { margin: 0; padding: 0; background: url('Amu gate.jpg') no-repeat center center fixed; background-size: cover; font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif; color: var(--amu-text); min-height: 100vh; line-height: 1.6; display: flex; flex-direction: column; }
        #templatemo_top_panel { background-color: rgba(26, 35, 126, 0.85); height: 80px; display: flex; align-items: center; justify-content: space-between; padding: 0 5%; box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4); position: relative; z-index: 1000; }
        #templatemo_top_panel img { height: 50px; width: auto; max-width: 120px; object-fit: contain; }
        #site_title { font-size: 1.4rem; font-weight: 700; color: #fff; text-align: center; text-transform: uppercase; letter-spacing: 1px; flex-grow: 1; margin: 0 20px; }
        #templatemo_menu ul { display: flex; list-style: none; margin: 0; padding: 0; flex-wrap: nowrap; }
        #templatemo_menu li { position: relative; margin: 0 8px; }
        #templatemo_menu a { color: #fff; text-decoration: none; padding: 8px 10px; border-radius: 4px; transition: all 0.3s ease; font-size: 0.85rem; display: block; white-space: nowrap; }
        #templatemo_menu a:hover, #templatemo_menu .current { background-color: var(--amu-primary); }
        #templatemo_menu ul ul { display: none; position: absolute; top: 100%; left: 0; background-color: rgba(0, 0, 0, 0.9); border-radius: 0 0 4px 4px; width: 180px; padding: 5px 0; z-index: 1001; }
        #templatemo_menu li:hover > ul { display: block; }
        #templatemo_content_panel { padding: 40px 5%; flex: 1; display: flex; justify-content: center; align-items: flex-start; backdrop-filter: blur(2px); }
        #templatemo_content_section { width: 100%; max-width: 700px; }
        #templatemo_content_right { width: 100%; background-color: #1A237E; border-radius: 10px; box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.1); padding: 30px; }
        .form-title { font-size: 1.8rem; font-weight: 600; margin-bottom: 25px; color: #fff; position: relative; padding-bottom: 10px; text-align: center; }
        .form-title::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 100px; height: 2px; background-color: var(--amu-primary); }
        .permission-form { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: var(--amu-text); }
        .form-control { width: 100%; padding: 12px 15px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.2); background-color: rgba(0, 0, 0, 0.5); color: var(--amu-text); font-size: 1rem; transition: all 0.3s; box-sizing: border-box;}
        .form-control:focus { border-color: var(--amu-primary); outline: none; box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3); }
        select.form-control { appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23e0e0e0' class='bi bi-caret-down-fill' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 15px center; background-size: 12px; padding-right: 40px; }
        .form-control option { color: black; background-color: white; }
        .form-actions { display: flex; justify-content: center; gap: 20px; margin-top: 30px; }
        .btn { padding: 12px 30px; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; border: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background-color: #00897B; color: white; }
        .btn-primary:hover { background-color: #00695C; }
        .btn-reset { background-color: #FF6F00; color: white; }
        .btn-reset:hover { background-color: #E65100; }
        #templatemo_footer_panel { background-color: rgba(26, 35, 126, 0.85); padding: 20px 5%; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.1); margin-top: auto; }
        #templatemo_footer_section { color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; }
        #templatemo_footer_section a { color: #4caf50; text-decoration: none; transition: color 0.3s; }
        #templatemo_footer_section a:hover { color: var(--amu-primary); text-decoration: underline; }
        @media (max-width: 992px) { #templatemo_content_section { align-items: center; } #templatemo_content_right { width: 100%; max-width: 700px; padding: 25px; } #templatemo_menu ul { flex-wrap: wrap; justify-content: center; } #templatemo_menu li { margin: 5px; } }
        @media (max-width: 768px) { #templatemo_top_panel { flex-direction: column; height: auto; padding: 15px; } #templatemo_top_panel img { display: none; } #site_title { margin: 10px 0 15px; font-size: 1.2rem; } #templatemo_menu ul { flex-direction: column; align-items: center; } #templatemo_menu li { margin: 5px 0; } #templatemo_content_right { padding: 20px; } .form-title { font-size: 1.5rem; } .permission-form { padding: 20px; } .form-actions { flex-direction: column; } .btn { width: 100%; } .btn-reset { margin-top: 10px; } }
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
                <li><a href="#" class="current"><i class="fas fa-eye"></i> View</a>
                    <ul>
                        <li><a href="mviewschedule.php"><i class="fas fa-calendar-alt"></i> View schedule</a></li>
                        <li><a href="exitrequest1.php" class="current"><i class="fas fa-door-open"></i> View exit request</a></li>
                        <li><a href="mrequest-view.php"><i class="fas fa-tools"></i> View maintenance</a></li>
                        <li><a href="mmessage.php"><i class="fas fa-envelope"></i> View message</a></li>
                        <li><a href="comment12.php"><i class="fas fa-comments"></i> View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php"><i class="fas fa-gas-pump"></i> Fuel</a></li>
                <li><a href="upload.php"><i class="fas fa-file-alt"></i> Report</a></li>
                <li><a href="changepssmanager.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_right">
                <?php if (isset($_GET['fid']) && isset($_GET['req_id']) && isset($_GET['date'])): ?>
                    <h1 class="form-title"><i class="fas fa-user-check"></i> Notify Exit Permission</h1>
                    
                    <form name="permissionForm" method="post" 
                          action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . $form_action_params; ?>" 
                          onsubmit="return checkform(this);" class="permission-form">
                        
                        <div class="form-group">
                            <label for="Plate_no">Plate Number</label>
                            <input type="text" name="Plate_no" id="Plate_no" class="form-control" 
                                   value="<?php echo $prefill_plate_no; ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="Start_time">Start Time (e.g., 09:00 AM)</label>
                            <input type="text" name="Start_time" id="Start_time" class="form-control" 
                                   value="<?php echo $prefill_start_time; ?>" required placeholder="HH:MM AM/PM">
                        </div>
                        
                        <div class="form-group">
                            <label for="Return_time">Return Time (e.g., 05:00 PM)</label>
                            <input type="text" name="Return_time" id="Return_time" class="form-control" 
                                   value="<?php echo $prefill_return_time; ?>" required placeholder="HH:MM AM/PM">
                        </div>
                        
                        <div class="form-group">
                            <label for="Permission">Permission Status</label>
                            <select name="Permission" id="Permission" class="form-control" required>
                                <option value="">-- Select Status --</option>
                                <option value="Permitted" <?php echo ($prefill_permission_status === 'Permitted') ? 'selected' : ''; ?>>Permitted</option>
                                <option value="Not Permitted" <?php echo ($prefill_permission_status === 'Not Permitted') ? 'selected' : ''; ?>>Not Permitted</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit</button>
                            <button type="reset" class="btn btn-reset"><i class="fas fa-undo"></i> Clear</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div style="text-align:center; padding: 20px; background-color: rgba(0,0,0,0.5); border-radius: 8px;">
                        <p style="color: var(--amu-danger); font-size: 1.2em;">
                            Required information (Plate Number, Request ID, or Date) is missing. Cannot process permission.
                        </p>
                        <p><a href="exitrequest1.php" style="color: var(--amu-primary); text-decoration: underline;">Go back to view exit requests.</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            Copyright © <?php echo date("Y"); ?> <a href="#">AMU University</a> | <a href="http://www.AMU.edu.et" target="_blank">AMU Vehicle Management Office</a>
        </div>
    </div>
    <script>
        function checkform(form) {
            const requiredFields = ['Plate_no', 'Start_time', 'Return_time', 'Permission'];
            for (const fieldName of requiredFields) {
                const fieldElement = form[fieldName];
                if (fieldElement && fieldElement.value.trim() === '') { // Check for empty or only whitespace
                    alert(`Please fill in the "${fieldName.replace('_', ' ')}" field.`);
                    if (fieldElement.focus) fieldElement.focus();
                    return false;
                }
            }
            // Basic time format check (can be improved with more robust regex or a time picker library)
            const timePattern = /^(0?[1-9]|1[0-2]):[0-5][0-9](\s*(AM|PM))?$/i; // Allows H:MM or HH:MM with optional AM/PM
            const twentyFourHourPattern = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/; // HH:MM (00:00 to 23:59)

            if (!timePattern.test(form.Start_time.value.trim()) && !twentyFourHourPattern.test(form.Start_time.value.trim())) {
                alert("Please enter Start Time in a valid format (e.g., 09:00 AM or 14:30).");
                form.Start_time.focus();
                return false;
            }
            if (!timePattern.test(form.Return_time.value.trim()) && !twentyFourHourPattern.test(form.Return_time.value.trim())) {
                alert("Please enter Return Time in a valid format (e.g., 05:00 PM or 17:00).");
                form.Return_time.focus();
                return false;
            }
            // You might want to add a check here to ensure Return_time is after Start_time if they are on the same day.
            return true;
        }
    </script>
</body>
</html>
<?php
// Close connection if it was opened and not closed by POST processing
if ($conn && $_SERVER["REQUEST_METHOD"] != "POST") {
    $conn->close();
}
?>