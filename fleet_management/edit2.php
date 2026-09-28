<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    // exit(); // We'll redirect later if needed, after setting messages
}

// Default message and status
$_SESSION['update_message'] = 'An unexpected error occurred.';
$_SESSION['update_status'] = 'error';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('config.php'); // Ensure this path is correct and it establishes $conn

    if (!isset($conn) || $conn->connect_error) {
        $_SESSION['update_message'] = 'Database connection failed: ' . (isset($conn) ? $conn->connect_error : "Connection object not found.");
        header("Location: view.php"); // Redirect back to user list
        exit();
    }

    // --- FORM DATA RETRIEVAL AND SANITIZATION ---
    // User_id_hidden is CRITICAL for the WHERE clause
    $user_id_to_update = isset($_POST['User_id_hidden']) ? trim($_POST['User_id_hidden']) : null;
    
    $first_name = isset($_POST['First_Name']) ? trim($_POST['First_Name']) : '';
    $last_name = isset($_POST['Last_Name']) ? trim($_POST['Last_Name']) : '';
    $sex = isset($_POST['Sex']) ? trim($_POST['Sex']) : '';
    $role = isset($_POST['Role']) ? trim($_POST['Role']) : '';
    $email = isset($_POST['Email']) ? trim($_POST['Email']) : '';
    $mobile_no = isset($_POST['Mobile_No']) ? trim($_POST['Mobile_No']) : '';
    $user_name = isset($_POST['UserName']) ? trim($_POST['UserName']) : '';
    
    $new_password = isset($_POST['Password']) ? $_POST['Password'] : ''; // Don't trim password yet
    $confirm_password = isset($_POST['ConfrimPassword']) ? $_POST['ConfrimPassword'] : '';

    // --- SERVER-SIDE VALIDATION ---
    $errors = [];
    if (empty($user_id_to_update)) {
        $errors[] = "User ID is missing. Cannot update.";
    }
    if (empty($first_name)) $errors[] = "First Name is required.";
    if (empty($last_name)) $errors[] = "Last Name is required.";
    if (empty($sex)) $errors[] = "Sex is required.";
    if (empty($role)) $errors[] = "Role is required.";
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Email format.";
    }
    if (empty($mobile_no)) {
        $errors[] = "Mobile Number is required.";
    } elseif (!preg_match("/^09\d{8}$/", $mobile_no)) { // Ethiopian phone format
        $errors[] = "Invalid Mobile Number format (must be 09XXXXXXXX).";
    }
    if (empty($user_name)) $errors[] = "Username is required.";

    // Password Validation (only if a new password is provided)
    $update_password = false;
    if (!empty($new_password)) {
        if (strlen($new_password) < 8) {
            $errors[] = "New Password must be at least 8 characters long.";
        }
        if ($new_password !== $confirm_password) {
            $errors[] = "New Passwords do not match.";
        }
        if (empty($errors)) { // Only proceed if no other errors and password fields are valid
            $update_password = true;
        }
    } elseif (!empty($confirm_password) && empty($new_password)) {
         $errors[] = "If confirming a new password, the 'New Password' field cannot be empty.";
    }


    if (!empty($errors)) {
        $_SESSION['update_message'] = "Update failed. Please correct the following errors: <ul><li>" . implode("</li><li>", $errors) . "</li></ul>";
        $_SESSION['update_status'] = 'error';
        // It's better to redirect back to edit1.php with the ID to show errors next to the form.
        // For simplicity here, we redirect to view.php.
        // To redirect to edit1.php: header("Location: edit1.php?User_id=" . urlencode($user_id_to_update));
        header("Location: view.php"); 
        exit();
    }

    // --- PREPARE SQL UPDATE STATEMENT ---
    $sql_set_parts = [];
    $bind_types = "";
    $bind_params = [];

    // Add fields to update
    $sql_set_parts[] = "First_Name = ?";
    $bind_types .= "s";
    $bind_params[] = &$first_name;

    $sql_set_parts[] = "Last_Name = ?";
    $bind_types .= "s";
    $bind_params[] = &$last_name;

    $sql_set_parts[] = "Sex = ?";
    $bind_types .= "s";
    $bind_params[] = &$sex;

    $sql_set_parts[] = "Role = ?";
    $bind_types .= "s";
    $bind_params[] = &$role;

    $sql_set_parts[] = "Email = ?";
    $bind_types .= "s";
    $bind_params[] = &$email;

    $sql_set_parts[] = "Mobile_No = ?";
    $bind_types .= "s";
    $bind_params[] = &$mobile_no;

    $sql_set_parts[] = "UserName = ?";
    $bind_types .= "s";
    $bind_params[] = &$user_name;
    
    if ($update_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        if ($hashed_password === false) {
            $_SESSION['update_message'] = "Error hashing password.";
            $_SESSION['update_status'] = 'error';
            header("Location: view.php");
            exit();
        }
        $sql_set_parts[] = "Password = ?"; // Assuming your column name is 'Password'
        $bind_types .= "s";
        $bind_params[] = &$hashed_password;
    }

    // Add the User_id for the WHERE clause at the end of bind_params
    // User_id type: if it's like '/1834/09', it's a VARCHAR ("s"). 
    // If purely numeric (e.g. 1, 2, 3), it's INT ("i").
    // Assuming VARCHAR here. Change "s" to "i" if User_id in DB is INT.
    $bind_types .= "s"; 
    $bind_params[] = &$user_id_to_update;


    $sql = "UPDATE User_registration SET " . implode(", ", $sql_set_parts) . " WHERE User_id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Dynamically bind parameters
        // The first argument to bind_param must be the types string.
        // The subsequent arguments must be the variables to bind, passed by reference.
        // array_unshift($bind_params, $bind_types); // Old way, not needed with splat operator
        // call_user_func_array(array($stmt, 'bind_param'), $bind_params);
        
        $stmt->bind_param($bind_types, ...$bind_params);


        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['update_message'] = "User account for ID: " . htmlspecialchars($user_id_to_update) . " updated successfully!";
                $_SESSION['update_status'] = 'success';
            } else {
                // This can happen if no data was actually changed, or if the User_id didn't match.
                // If User_id was invalid and didn't match, it would be caught by edit1.php not finding user.
                // So, this usually means "no changes were made to the data".
                $_SESSION['update_message'] = "No changes were made to the user account (ID: " . htmlspecialchars($user_id_to_update) . "). Data might be the same as existing.";
                $_SESSION['update_status'] = 'success'; // Or 'info' if you have such a style
            }
        } else {
            $_SESSION['update_message'] = "Error updating record: " . htmlspecialchars($stmt->error);
            $_SESSION['update_status'] = 'error';
        }
        $stmt->close();
    } else {
        $_SESSION['update_message'] = "Error preparing statement: " . htmlspecialchars($conn->error);
        $_SESSION['update_status'] = 'error';
    }

    $conn->close();

} else {
    // If not a POST request, redirect to view page or show an error
    $_SESSION['update_message'] = "Invalid request method.";
    $_SESSION['update_status'] = 'error';
}

// Redirect back to the view page to show the message
header("Location: view.php");
exit();

?>