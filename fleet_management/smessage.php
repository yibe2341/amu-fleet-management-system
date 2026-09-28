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
    <title>AMU Fleet Management System - View Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-navy-heavy: rgba(0, 0, 128, 0.85); 
            --amu-navy-medium: rgba(0, 0, 128, 0.75); 
            --amu-navy-light: rgba(0, 0, 128, 0.7);   
            --amu-navy-lighter: rgba(0, 0, 128, 0.5);  
            --amu-navy-dropdown: rgba(0, 0, 128, 0.9); 
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
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
            background-color: var(--amu-navy-heavy); 
            padding: 0.8rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3); 
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
            background-color: var(--amu-navy-dropdown); 
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Message Section */
        .message-section {
            background-color: var(--amu-navy-light); 
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.2); 
        }

        .message-section h1 {
            color: var(--amu-primary);
            text-align: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
        }

        /* Search Form - CSS rules removed as the form is removed */
        /* .search-form, .search-form input[type="text"], .search-form button[type="submit"] */
        

        /* Message Table */
        .message-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem; /* Kept margin-top for spacing from title */
        }

        .message-table th {
            background-color: var(--amu-primary); 
            color: white;
            padding: 1rem;
            text-align: left;
        }

        .message-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .message-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .message-table td.action-cell { 
            text-align: center;
        }

        .action-btn {
            color: #d32f2f; 
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem; 
        }

        .action-btn:hover {
            color: #b71c1c; 
            transform: scale(1.1);
        }

        /* Footer */
        footer {
            background-color: var(--amu-navy-heavy); 
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

            .message-table { 
                display: block;
                overflow-x: auto; 
            }
        }
         @media (max-width: 576px) {
            main {
                padding: 1rem 3%; 
            }
            .message-section { /* Adjusted from .search-form */
                padding: 1rem;
            }
            .message-table th, .message-table td {
                padding: 0.6rem;
                font-size: 0.9rem;
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
                <li><a href="newsche.php"><i class="fas fa-calendar-plus"></i> Schedule</a></li>
                <li>
                    <a href="#" class="current"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="sviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="smessage.php" class="current"><i class="fas fa-envelope"></i> View Messages</a></li>
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
            <div class="message-section">
                <h1>View Messages</h1>
                
                <!-- Search Form Removed -->
                <!-- 
                <form name="search" method="post" action="searchmsg2.php" class="search-form">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="flex: 1;">
                            <label for="search" style="display: block; margin-bottom: 0.5rem;">Search Messages:</label>
                            <input type="text" name="search" id="search" style="width: 100%; padding: 0.8rem; border-radius: 4px; border: 1px solid rgba(255,255,255,0.2); background-color: rgba(0,0,0,0.5); color: var(--amu-text);">
                        </div>
                        <div style="align-self: flex-end;">
                            <button type="submit" style="background-color: var(--amu-primary); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 4px; cursor: pointer; transition: all 0.3s ease;">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
                -->

                <div style="overflow-x:auto;">
                <table class="message-table">
                    <thead>
                        <tr>
                            <th>M_id</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Pnumber</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Include the database configuration file
                        include("config.php"); 

                        // Check database connection
                        if ($conn->connect_error) { 
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Since search is removed, we might want to display all messages for 'Scheduler'
                        // or a limited number (e.g., last 10 or 20).
                        // For now, keeping original query logic that expects a search,
                        // but it will effectively show all 'Scheduler' messages if $_POST["search"] is empty.
                        // If you want to *always* show all Scheduler messages, modify the query.

                        $search = isset($_POST["search"]) ? mysqli_real_escape_string($conn, $_POST["search"]) : ""; 
                        $flag = 0; 

                        // Prepare and execute the query
                        // This query structure relies on $_POST["search"].
                        // If you want to always show all messages for "Scheduler" on this page load,
                        // you would change the query to:
                        // $query = "SELECT * FROM message WHERE too = 'Scheduler' ORDER BY Date DESC";
                        // and remove the $searchParam and $stmt->bind_param parts.
                        
                        $query = "SELECT * FROM message WHERE too LIKE ? AND too = 'Scheduler'"; 
                        $stmt = $conn->prepare($query);
                        if (!$stmt) {
                            die("<tr><td colspan='7' style='text-align: center; color: red;'>Error preparing statement: " . htmlspecialchars($conn->error) . "</td></tr>");
                        }
                        // If search form is removed, $search will be empty, so $searchParam will be "%%"
                        // which will match any 'too' field if the second condition `too = 'Scheduler'` is also met.
                        // This means it should show all messages for 'Scheduler'.
                        $searchParam = "%" . $search . "%"; 
                        $stmt->bind_param("s", $searchParam); 
                        
                        if (!$stmt->execute()) {
                             echo "<tr><td colspan='7' style='text-align: center; color: red;'>Error executing statement: " . htmlspecialchars($stmt->error) . "</td></tr>";
                        } else {
                            $result = $stmt->get_result(); 

                            if ($result->num_rows > 0) { 
                                $flag = 1; 
                                while ($row3 = $result->fetch_assoc()) { 
                                    echo "<tr>";
                                    echo '<td>' . htmlspecialchars($row3['m_id']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row3['frm']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row3['too']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row3['pnumber']) . '</td>';
                                    echo '<td>' . nl2br(htmlspecialchars($row3['message'])) . '</td>';
                                    echo '<td>' . htmlspecialchars(date("M d, Y H:i", strtotime($row3['Date']))) . '</td>';
                                    echo '<td class="action-cell">'; 
                                    echo '<a class="action-btn" href="tt2.php?m_id=' . htmlspecialchars($row3['m_id']) . '&view=delete" onClick="return confirm(\'Are you sure??\')">';
                                    echo '<i class="fas fa-trash-alt"></i>';
                                    echo '</a>';
                                    echo "</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center;'>No messages found for Scheduler.</td></tr>"; 
                            }
                            $stmt->close(); 
                        }
                        $conn->close(); 
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>© Copyright © <?php echo date('Y'); ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a></p>
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

        // ValidateAlpha function is no longer used as search input is removed
        /*
        function ValidateAlpha(evt) {
            var keyCode = (evt.which) ? evt.which : evt.keyCode;
            if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && keyCode != 8 && keyCode != 9) {
                alert("Only letters are allowed!");
                return false;
            }
            return true;
        }
        */
    </script>
</body>
</html>