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
    <title>Delete Account | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, account deletion">
    <meta name="description" content="AMU Fleet Management System - Delete User Accounts">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;       /* AMU green */
            --amu-primary-dark: #1b5e20;
            /* Navy Theme Variables */
            --amu-navy-bg-heavy: rgba(0, 0, 128, 0.85);   /* For header/footer */
            --amu-navy-bg-medium: rgba(0, 0, 128, 0.75);  /* For content containers */
            --amu-navy-bg-light: rgba(0, 0, 128, 0.7);    /* For lighter elements if needed */
            --amu-navy-bg-lighter: rgba(0, 0, 128, 0.5);   /* For inputs or accents */
            --amu-navy-dropdown: rgba(0, 0, 128, 0.9);    /* For dropdowns */

            --amu-light: #f5f5f5;        /* Light color for text on dark navy */
            --amu-text: #e0e0e0;         /* Primary text color */
            --amu-text-secondary: #b0b0b0;/* Secondary text color */
            --amu-danger: #dc3545;       /* Red for delete actions */
            --amu-orange-bright: #FF6F00; /* Your bright orange */
            --amu-orange-dark: #E65100;   /* Your darker orange */
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

        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-heavy); /* Using Navy Heavy */
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
            color: var(--amu-light); /* Ensure title is light on navy */
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex-grow: 1;
            margin: 0 1rem;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 5px;
        }

        #templatemo_menu a {
            color: var(--amu-light); /* Ensure menu text is light */
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        #templatemo_menu a .fas {
             font-size: 0.9em;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--amu-navy-dropdown); /* Using Navy for dropdown */
            border-radius: 0 0 4px 4px;
            min-width: 180px;
            padding: 5px 0;
            z-index: 1001;
        }
         #templatemo_menu ul ul a {
            padding: 10px 15px;
            font-size: 0.8rem;
        }


        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-container {
            padding: 40px 5%;
            min-height: calc(100vh - 160px);
            display: flex;
            justify-content: center;
            backdrop-filter: blur(2px);
            flex: 1;
        }

        .data-table-container {
            background-color: var(--amu-navy-bg-medium); /* Using Navy Medium */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); -- Optional if parent already has it */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 1200px;
            overflow-x: auto; /* For table responsiveness */
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--amu-light); /* Light text on navy */
            position: relative;
            padding-bottom: 10px;
            text-align: center; /* Center title */
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%; /* Center underline */
            transform: translateX(-50%); /* Center underline */
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            color: var(--amu-text);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15); /* Slightly more visible border */
        }

        th {
            background-color: rgba(46, 125, 50, 0.4); /* AMU Green tint for table headers on navy */
            font-weight: 600;
            color: var(--amu-light);
        }

        tr:hover {
            background-color: var(--amu-navy-bg-lighter); /* Lighter navy for hover */
        }

        .delete-btn {
            color: var(--amu-danger);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex; /* For icon alignment */
            align-items: center;
            gap: 5px; /* Space between icon and text */
            transition: color 0.3s ease;
        }
        .delete-btn .fas { /* Icon inside delete button */
             font-size: 0.9em;
        }

        .delete-btn:hover {
            color: #a71d2a; /* Darker red for hover */
            text-decoration: underline;
        }

        .confirmation-dialog {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75); /* Slightly darker overlay */
            z-index: 1001;
            justify-content: center;
            align-items: center;
        }

        .dialog-content {
            background-color: var(--amu-navy-bg-medium); /* Dialog with navy background */
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2); /* More visible border for dialog */
            box-shadow: 0 5px 20px rgba(0,0,0,0.4);
        }
         .dialog-content h3 {
            color: var(--amu-light);
            margin-top: 0;
            margin-bottom: 15px;
        }
        .dialog-content p {
            color: var(--amu-text); /* Use primary text color for better readability */
            margin-bottom: 25px;
        }

        .dialog-actions {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .dialog-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .dialog-btn:hover {
            transform: translateY(-1px);
        }

        .dialog-confirm {
            background-color: var(--amu-danger);
            color: white;
        }
        .dialog-confirm:hover {
            background-color: #c82333;
        }

        .dialog-cancel {
            background-color: var(--amu-orange-bright);
            color: white;
        }
        .dialog-cancel:hover {
            background-color: var(--amu-orange-dark);
        }


        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-heavy); /* Using Navy Heavy */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: var(--amu-primary); /* Use AMU primary for footer links */
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            #templatemo_top_panel img { display: none; }
            #site_title { margin: 10px 0 15px; font-size: 1.2rem; }
            #templatemo_menu ul { flex-direction: column; align-items: center; }
            #templatemo_menu li { margin: 5px 0; width: 100%;}
            #templatemo_menu a { justify-content: center; }
            #templatemo_menu ul ul { position: static; width: 100%; background-color: var(--amu-navy-dropdown); }

            .content-container { padding: 30px 3%; }
            .data-table-container { padding: 20px; }
            table { display: block; white-space: nowrap; font-size: 0.9rem; }
            th, td { white-space: nowrap; padding: 10px 8px;}
            .dialog-actions { flex-direction: column; }
            .dialog-btn { width: 100%; }
        }
    </style>
    <script>
        function confirmDelete(userId) {
            const dialog = document.getElementById('confirmationDialog');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const encodedUserId = encodeURIComponent(userId);

            // Update the href for the confirm button each time the dialog is shown
            confirmBtn.href = 'deluser.php?User_id=' + encodedUserId + '&view=delete'; // Changed to use href for an <a> tag

            dialog.style.display = 'flex';

            document.getElementById('cancelDeleteBtn').onclick = function() {
                dialog.style.display = 'none';
            };
        }

         // If confirmDeleteBtn is an <a> tag, this handles the click to navigate
        document.addEventListener('DOMContentLoaded', function() {
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            if (confirmBtn.tagName === 'A') { // Check if it's an anchor tag
                confirmBtn.onclick = function(event) {
                    // No need to preventDefault if it's just navigating
                    // If it were a button type submit in a form, you might
                    window.location.href = this.href; // Navigate
                };
            } else { // If it's a button, the JS function above handles it
                 confirmBtn.onclick = function() { // This part is for if confirmBtn is a <button>
                    // The confirmDelete function above should set window.location.href for a button
                    // This is a bit redundant if confirmDelete sets window.location.href,
                    // but safe if confirmDelete was meant to only set up.
                    // For a button, the action would be set within confirmDelete.
                    // To be clean, if it's a button, the navigation should be handled
                    // by the confirmDelete() function setting confirmBtn.onclick properly.
                    // The current confirmDelete() is good for a button.
                 };
            }
        });

    </script>
</head>
<body>
    <!-- Header Section -->
    <div id="templatemo_top_panel">
        <img src="wou arm.jpg.png" alt="AMU Logo">
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="Admin.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#" class="current"><i class="fas fa-users-cog"></i> Account <i class="fas fa-caret-down"></i></a>
                    <ul>
                        <li><a href="createaccount.php"><i class="fas fa-user-plus"></i> Create account</a></li>
                        <li><a href="view.php"><i class="fas fa-user-edit"></i> Update account</a></li>
                        <li><a href="view2.php" class="current"><i class="fas fa-user-minus"></i> Delete account</a></li>
                    </ul>
                </li>
                <li><a href="aviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                <li><a href="upload1.php"><i class="fas fa-file-alt"></i> Report</a></li>
                <li><a href="changepssadmin.php"><i class="fas fa-key"></i> Change Pass</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-container">
        <div class="data-table-container">
            <h1 class="page-title">Delete User Accounts</h1>

            <?php
            include('config.php');

            if (!isset($conn) || $conn->connect_error) {
                $errorMessage = isset($conn) ? $conn->connect_error : "Configuration file not loaded or connection not established.";
                echo "<table><tr><td colspan='9' class='error-message'>Connection failed: " . htmlspecialchars($errorMessage) . "</td></tr></table>";
            } else {
                $query = "SELECT User_id, First_Name, Last_Name, Sex, Role, Email, Mobile_No, UserName FROM User_registration";
                $stmt = $conn->prepare($query);

                if (!$stmt) {
                    echo "<table><tr><td colspan='9' class='error-message'>Error preparing statement: " . htmlspecialchars($conn->error) . "</td></tr></table>";
                } else {
                    if (!$stmt->execute()) {
                        echo "<table><tr><td colspan='9' class='error-message'>Error executing statement: " . htmlspecialchars($stmt->error) . "</td></tr></table>";
                    } else {
                        $result = $stmt->get_result();

                        echo "<table>";
                        echo "<thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Sex</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>";

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo '<td>' . htmlspecialchars($row['User_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['First_Name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Last_Name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Sex']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Role']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Email']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['Mobile_No']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['UserName']) . '</td>';
                                echo '<td>
                                        <a href="javascript:void(0)" onclick="confirmDelete(\'' . htmlspecialchars(addslashes($row['User_id']), ENT_QUOTES) . '\')" class="delete-btn">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                      </td>';
                                echo "</tr>";
                            }
                        } else {
                             echo "<tr><td colspan='9' style='text-align:center;'>No users found.</td></tr>";
                        }
                        echo "</tbody></table>";
                        $stmt->close();
                    }
                }
                $conn->close();
            }
            ?>
        </div>
    </div>

    <!-- Confirmation Dialog -->
    <div id="confirmationDialog" class="confirmation-dialog">
        <div class="dialog-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this user account? This action cannot be undone.</p>
            <div class="dialog-actions">
                <!-- Changed confirmDeleteBtn to an <a> tag for direct navigation, can also be a button with JS navigation -->
                <a href="#" id="confirmDeleteBtn" class="dialog-btn dialog-confirm">Delete</a>
                <button id="cancelDeleteBtn" class="dialog-btn dialog-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            <!-- Your original footer text had year 2025, changed to dynamic year -->
            Copyright © <?php echo date("Y"); ?> <a href="#">AMU University</a> | <a href="http://www.amu.edu.et" target="_blank">AMU Vehicle Management Office</a>
        </div>
    </div>
</body>
</html>