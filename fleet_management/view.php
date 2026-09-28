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
    <title>Account Management | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, account management">
    <meta name="description" content="AMU Fleet Management System - Account Management">
    <style type="text/css">
        /* ===== Global Styles ===== */
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
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
        }

        /* ===== Header Styles ===== */
        #templatemo_top_panel {
            background-color: rgba(26, 35, 126, 0.85); /* Header Navy Blue */
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
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 8px;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
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
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        /* ===== Main Content Styles ===== */
        .content-container {
            padding: 40px 5%;
            min-height: calc(100vh - 160px); /* Considers header and footer */
            display: flex;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        .data-table-container {
            background-color: #1A237E; /* Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 1200px;
            overflow-x: auto;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: #fff;
            position: relative;
            padding-bottom: 10px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
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
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: rgba(46, 125, 50, 0.3); /* AMU Green tint for table headers */
            font-weight: 600;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .action-icon {
            width: 20px;
            height: 20px;
            transition: transform 0.3s;
        }

        .action-icon:hover {
            transform: scale(1.2);
        }
        
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: rgba(46, 125, 50, 0.8); /* Green for success */
            color: white;
        }
        .error-msg-php { /* Differentiated from form error message */
            background-color: rgba(211, 47, 47, 0.8); /* Red for error */
            color: white;
        }


        /* ===== Footer Styles ===== */
        #templatemo_footer_panel {
            background-color: rgba(26, 35, 126, 0.85); /* Footer Navy Blue */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        #templatemo_footer_section a {
            color: #4caf50;
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary);
            text-decoration: underline;
        }

        /* ===== Responsive Adjustments ===== */
        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            #templatemo_top_panel img { display: none; }
            #site_title { margin: 10px 0 15px; }
            #templatemo_menu ul { flex-wrap: wrap; justify-content: center; }
            #templatemo_menu li { margin: 5px; }
            .content-container { padding: 30px 3%; }
            .data-table-container { padding: 20px; }
            table { display: block; white-space: nowrap; }
            th, td { white-space: nowrap; }
        }
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
        <div class="data-table-container">
            <h1 class="page-title">Registered Users</h1>
            
            <?php
            // Display success or error messages from session (if any)
            if (isset($_SESSION['update_message'])) {
                echo '<div class="message ' . (isset($_SESSION['update_status']) && $_SESSION['update_status'] == 'success' ? 'success' : 'error-msg-php') . '">' . htmlspecialchars($_SESSION['update_message']) . '</div>';
                unset($_SESSION['update_message']); // Clear the message after displaying
                unset($_SESSION['update_status']);
            }

            include('config.php'); 

            if (!isset($conn) || $conn->connect_error) {
                $errorMessage = isset($conn) ? $conn->connect_error : "Configuration file not loaded or connection not established.";
                echo "<div class='message error-msg-php'>Connection failed: " . htmlspecialchars($errorMessage) . "</div>";
            } else {
                // Get results from the database
                // User_id type: if it's like '/1834/09', it's a VARCHAR. If purely numeric, it's INT.
                // Assuming VARCHAR based on previous observations.
                $query = "SELECT User_id, First_Name, Last_Name, Sex, Role, Email, Mobile_No, UserName FROM User_registration";
                $result = $conn->query($query);

                if (!$result) {
                    echo "<div class='message error-msg-php'>Error fetching data: " . htmlspecialchars($conn->error) . "</div>";
                } else {
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
                            // Ensure User_id is properly URL encoded
                            echo '<td><a href="edit1.php?User_id=' . urlencode($row['User_id']) . '"><img src="edit.png" alt="Edit" class="action-icon"></a></td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' style='text-align:center;'>No users found.</td></tr>";
                    }
                    echo "</tbody></table>";
                }
                $conn->close();
            }
            ?>
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