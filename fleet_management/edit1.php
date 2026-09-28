<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include('config.php'); 

$userData = [];
$error = '';
$User_id_get = ''; // Initialize

if ($conn->connect_error) {
    $error = 'Database connection failed: ' . $conn->connect_error;
} else {
    if (isset($_GET['User_id'])) {
        $User_id_get = $_GET['User_id']; 
        
        // Prepare and execute query
        // Removed Password and ConfrimPassword from SELECT as they are not needed for pre-filling form
        // or ConfrimPassword is not a DB field.
        $stmt = $conn->prepare("SELECT User_id, First_Name, Last_Name, Sex, Role, Email, Mobile_No, UserName FROM User_registration WHERE User_id = ?");
        if ($stmt) {
            // User_id type: if it's like '/1834/09', it's a VARCHAR ("s"). 
            // If purely numeric (e.g. 1, 2, 3), it's INT ("i").
            // Assuming VARCHAR based on your previous SQL context. Change "s" to "i" if it's an integer.
            $stmt->bind_param("s", $User_id_get); 
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
            } else {
                $error = "No user found with the provided ID: " . htmlspecialchars($User_id_get);
            }
            $stmt->close();
        } else {
            $error = "Failed to prepare statement: " . $conn->error;
        }
    } else {
        $error = "User ID not provided. Please select a user from the list.";
    }
    $conn->close();
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
    <title>Update Account | AMU Fleet System</title>
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
        }

        body {
            margin: 0; padding: 0;
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            color: var(--amu-text); min-height: 100vh; line-height: 1.6;
        }

        #templatemo_top_panel {
            background-color: rgba(26, 35, 126, 0.85); height: 80px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
            position: relative; z-index: 1000;
        }
        #templatemo_top_panel img { height: 50px; width: auto; object-fit: contain; }
        #site_title {
            font-size: 1.4rem; font-weight: 700; color: #fff;
            text-align: center; text-transform: uppercase; letter-spacing: 1px;
        }
        #templatemo_menu ul { display: flex; list-style: none; margin: 0; padding: 0; }
        #templatemo_menu li { position: relative; margin: 0 8px; }
        #templatemo_menu a {
            color: #fff; text-decoration: none; padding: 8px 15px;
            border-radius: 4px; transition: all 0.3s ease; font-size: 0.9rem;
        }
        #templatemo_menu a:hover, #templatemo_menu .current { background-color: var(--amu-primary); }
        #templatemo_menu ul ul {
            display: none; position: absolute; top: 100%; left: 0;
            background-color: rgba(0, 0, 0, 0.9); border-radius: 0 0 4px 4px;
            width: 180px; padding: 5px 0;
        }
        #templatemo_menu li:hover > ul { display: block; }

        .content-container {
            padding: 40px 5%; min-height: calc(100vh - 160px);
            display: flex; justify-content: center; backdrop-filter: blur(2px);
        }
        .form-container {
            background-color: #1A237E; border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1); padding: 30px;
            width: 100%; max-width: 800px;
        }
        .page-title {
            font-size: 1.8rem; font-weight: 600; margin-bottom: 25px; color: #fff;
            position: relative; padding-bottom: 10px; text-align: center;
        }
        .page-title::after {
            content: ''; position: absolute; bottom: 0; left: 50%;
            transform: translateX(-50%); width: 100px; height: 2px;
            background-color: var(--amu-primary);
        }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: rgba(255, 255, 255, 0.9); }
        .form-input {
            width: 100%; padding: 12px 15px; border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);
            color: #fff; font-size: 1rem; transition: all 0.3s; box-sizing: border-box;
        }
        .form-input:focus {
            outline: none; border-color: var(--amu-primary);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3);
        }
        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 10px center; background-size: 20px;
        }
        .form-input option { color: black; background-color: white; }
        .form-actions {
            grid-column: 1 / -1; display: flex;
            justify-content: center; gap: 20px; margin-top: 20px;
        }
        .form-button {
            color: white; padding: 12px 30px; border: none; border-radius: 6px;
            cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.3s;
        }
        .form-button:hover { transform: translateY(-2px); }
        button[type="submit"].form-button { background-color: #00897B; }
        button[type="submit"].form-button:hover { background-color: #00695C; }
        button[type="reset"].form-button { background-color: #FF6F00; }
        button[type="reset"].form-button:hover { background-color: #E65100; }

        .error-message-form { /* Differentiated from PHP error message */
            color: #ffcdd2; /* Lighter red for form validation */
            margin-bottom: 15px;
            text-align: center;
            background-color: rgba(239, 83, 80, 0.2);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid rgba(239, 83, 80, 0.5);
        }
         .php-error-message { /* For errors from PHP block at top */
            color: #ff6b6b; 
            margin-bottom: 20px;
            text-align: center;
            background-color: rgba(255,0,0,0.1);
            padding: 10px;
            border-radius: 5px;
        }

        #templatemo_footer_panel {
            background-color: rgba(26, 35, 126, 0.85); padding: 20px 5%;
            text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        #templatemo_footer_section { color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; }
        #templatemo_footer_section a { color: #4caf50; text-decoration: none; transition: color 0.3s; }
        #templatemo_footer_section a:hover { color: var(--amu-primary); text-decoration: underline; }

        @media (max-width: 768px) {
            #templatemo_top_panel { flex-direction: column; height: auto; padding: 15px; }
            #templatemo_top_panel img { display: none; }
            #site_title { margin: 10px 0 15px; }
            #templatemo_menu ul { flex-wrap: wrap; justify-content: center; }
            #templatemo_menu li { margin: 5px; }
            .content-container { padding: 30px 3%; }
            .form-container { padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-actions { flex-direction: column; }
            .form-button { width: 100%; }
        }
    </style>
    <script>
        function validateForm1() {
            const form = document.forms['updateUserForm']; // Changed form name for clarity
            
            const requiredFields = ['First_Name', 'Last_Name', 'Sex', 'Role', 'Email', 'Mobile_No', 'UserName'];
            for (const fieldName of requiredFields) {
                const fieldElement = form[fieldName];
                if (fieldElement && !fieldElement.value.trim()) {
                    alert(`Please fill in ${fieldName.replace('_', ' ')}.`);
                    if (fieldElement.focus) fieldElement.focus();
                    return false;
                }
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (form.Email && !emailRegex.test(form.Email.value)) {
                alert("Please enter a valid email address.");
                if (form.Email.focus) form.Email.focus();
                return false;
            }

            const phoneRegex = /^09\d{8}$/; // Ethiopian format
            if (form.Mobile_No && !phoneRegex.test(form.Mobile_No.value)) {
                alert("Please enter a valid Ethiopian phone number starting with 09 (10 digits total).");
                if (form.Mobile_No.focus) form.Mobile_No.focus();
                return false;
            }

            // Password validation: Only if new password is being entered
            const newPassword = form.Password.value;
            const confirmPassword = form.ConfrimPassword.value;

            if (newPassword !== "") { // If user is trying to set a new password
                if (newPassword.length < 8) {
                    alert("New Password must be at least 8 characters long.");
                    if (form.Password.focus) form.Password.focus();
                    return false;
                }
                if (newPassword !== confirmPassword) {
                    alert("New Passwords do not match.");
                    if (form.ConfrimPassword.focus) form.ConfrimPassword.focus();
                    return false;
                }
            } else { // New password field is empty
                if (confirmPassword !== "") { // But confirm password is not
                    alert("Please enter the New Password if you wish to change it, or leave both password fields blank.");
                    if (form.Password.focus) form.Password.focus();
                    return false;
                }
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
                <li><a href="#" class="current">Account</a>
                    <ul>
                        <li><a href="createaccount.php">Create account</a></li>
                        <li><a href="view.php" class="current">Update account</a></li>
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
    <div class="content-container">
        <div class="form-container">
            <h1 class="page-title">Update User Account</h1>
            
            <?php if ($error): ?>
                <div class="php-error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($userData)): ?>
                <form name="updateUserForm" method="post" action="edit2.php" onsubmit="return validateForm1();" class="form-grid">
                    <!-- IMPORTANT: Hidden input for User_id to be submitted with the form -->
                    <input type="hidden" name="User_id_hidden" value="<?php echo htmlspecialchars($userData['User_id']); ?>">
                    
                    <div class="form-group">
                        <label for="User_id_display" class="form-label">User ID (Cannot be changed)</label>
                        <input type="text" name="User_id_display" id="User_id_display" class="form-input" value="<?php echo htmlspecialchars($userData['User_id']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="First_Name" class="form-label">First Name</label>
                        <input type="text" name="First_Name" id="First_Name" class="form-input" value="<?php echo htmlspecialchars($userData['First_Name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="Last_Name" class="form-label">Last Name</label>
                        <input type="text" name="Last_Name" id="Last_Name" class="form-input" value="<?php echo htmlspecialchars($userData['Last_Name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="Sex" class="form-label">Sex</label>
                        <select name="Sex" id="Sex" class="form-input" required>
                            <option value="">Select Sex</option>
                            <option value="Male" <?php echo (isset($userData['Sex']) && $userData['Sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo (isset($userData['Sex']) && $userData['Sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="Role" class="form-label">Role</label>
                        <select name="Role" id="Role" class="form-input" required>
                             <option value="">Select Role</option>
                            <option value="Admin" <?php echo (isset($userData['Role']) && $userData['Role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="Driver" <?php echo (isset($userData['Role']) && $userData['Role'] == 'Driver') ? 'selected' : ''; ?>>Driver</option>
                            <option value="Manager" <?php echo (isset($userData['Role']) && $userData['Role'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                            <option value="User" <?php echo (isset($userData['Role']) && $userData['Role'] == 'User') ? 'selected' : ''; ?>>User</option>
                            <option value="Mechanic" <?php echo (isset($userData['Role']) && $userData['Role'] == 'Mechanic') ? 'selected' : ''; ?>>Mechanic</option>
                            <option value="Scheduler" <?php echo (isset($userData['Role']) && $userData['Role'] == 'Scheduler') ? 'selected' : ''; ?>>Scheduler</option>
                            <option value="Police" <?php echo (isset($userData['Role']) && $userData['Role'] == 'Police') ? 'selected' : ''; ?>>Police</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="Email" class="form-label">Email</label>
                        <input type="email" name="Email" id="Email" class="form-input" value="<?php echo htmlspecialchars($userData['Email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="Mobile_No" class="form-label">Mobile No</label>
                        <input type="text" name="Mobile_No" id="Mobile_No" class="form-input" value="<?php echo htmlspecialchars($userData['Mobile_No'] ?? ''); ?>" required pattern="09\d{8}" title="Ethiopian number: 09 followed by 8 digits">
                    </div>
                    
                    <div class="form-group">
                        <label for="UserName" class="form-label">Username</label>
                        <input type="text" name="UserName" id="UserName" class="form-input" value="<?php echo htmlspecialchars($userData['UserName'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="Password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="Password" id="Password" class="form-input" autocomplete="new-password">
                    </div>
                    
                    <div class="form-group">
                        <label for="ConfrimPassword" class="form-label">Confirm New Password</label>
                        <input type="password" name="ConfrimPassword" id="ConfrimPassword" class="form-input" autocomplete="new-password">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="form-button">Update Account</button>
                        <button type="reset" class="form-button">Reset Changes</button>
                    </div>
                </form>
            <?php elseif (empty($error) && !isset($_GET['User_id'])): ?>
                 <!-- This condition is now less likely if $error is set when User_id is missing -->
                 <div class="php-error-message">Please provide a User ID to update an account. You can select a user from the <a href="view.php" style="color: var(--amu-primary); text-decoration:underline;">User List</a>.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>