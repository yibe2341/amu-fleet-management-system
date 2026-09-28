<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Database connection
include('config.php'); // Ensure this path is correct

// Retrieve messages
$messages = [];
$searchTerm = '';
$status_message = '';
$status_type = '';

if (isset($_POST['search'])) {
    $searchTerm = $conn->real_escape_string(trim($_POST['search'])); // Trim whitespace
}

try {
    $query = "SELECT date, frm, message FROM message";
    
    if (!empty($searchTerm)) {
        // Use prepared statements for search to prevent SQL injection
        $likeTerm = "%" . $searchTerm . "%";
        $query = "SELECT date, frm, message FROM message WHERE message LIKE ? OR frm LIKE ? ORDER BY date DESC";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ss", $likeTerm, $likeTerm);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
    } else {
        $query .= " ORDER BY date DESC";
        $result = $conn->query($query);
    }
    
    if ($result) {
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        if (isset($stmt)) $stmt->close(); // Close statement if it was used
    } else {
        // This else might not be reached if prepare fails, as it throws an exception
        throw new Exception($conn->error); 
    }
} catch (Exception $e) {
    $status_message = "Error loading messages: " . htmlspecialchars($e->getMessage());
    $status_type = "error";
    error_log("View Messages Error: " . $e->getMessage()); // Log the detailed error
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            /* overflow-x: hidden; */ /* Handled by containers */
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
            background-color: rgba(26, 35, 126, 0.85); /* MODIFIED: Header Navy Blue */
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
            max-width: 120px;
            object-fit: contain;
        }

        #site_title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex-grow: 1;
            margin: 0 20px;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
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
            white-space: nowrap;
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

        #templatemo_content_panel {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: calc(100vh - 160px);
            padding: 20px 0;
            width: 100%;
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
            gap: 20px;
        }

        #templatemo_content_left {
            width: 250px;
            background-color: #1A237E; /* MODIFIED: Navy Blue */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            flex-shrink: 0;
            height: fit-content;
        }

        #login_section {
            background-color: rgba(26, 35, 126, 0.9); /* MODIFIED: Slightly lighter Navy */
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            padding: 20px;
        }

        #login_section_title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--amu-primary);
        }

        #login_section img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px auto;
            display: block;
            border: 3px solid var(--amu-primary);
        }
        #login_section_middle p {
            color: var(--amu-text-secondary);
            font-weight: 500;
        }

        #templatemo_content_right {
            flex: 1;
            /* padding: 20px 30px; */ /* Padding moved to .right_column_section */
        }

        .right_column_section {
            background-color: #1A237E; /* MODIFIED: Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            margin-bottom: 20px;
            width: 100%;
        }

        .right_column_section_title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: #fff;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
         .right_column_section_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary);
        }


        .status-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: rgba(0,0,0,0.6);
            color: white;
            text-align: center;
            border-left: 4px solid var(--amu-primary);
        }
        
        .status-success {
            border-left-color: var(--amu-success);
        }
        
        .status-error {
            border-left-color: var(--amu-danger);
        }

        .search-form {
            margin-bottom: 25px;
            /* background-color: rgba(0, 102, 255, 0.2); */ /* Removed blue tint */
            padding: 15px;
            border-radius: 5px;
            display: flex; /* For better alignment of input and button */
            gap: 10px; /* Space between input and button */
            border: 1px solid rgba(255,255,255,0.1); /* Subtle border */
        }

        .search-form input[type="text"] {
            padding: 10px 15px; /* Adjusted padding */
            flex-grow: 1; /* Allow input to take available space */
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--amu-text);
            font-size: 1rem;
        }
        .search-form input[type="text"]:focus {
            outline: none;
            border-color: var(--amu-primary);
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3);
        }


        .search-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #00897B; /* MODIFIED: Teal Green */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-form input[type="submit"]:hover {
            background-color: #00695C; /* MODIFIED: Darker Teal Green */
        }

        .message-table-container { /* Added for overflow control */
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 20px;
        }

        .message-table {
            width: 100%;
            min-width: 600px; /* Ensure table has some width */
            border-collapse: collapse;
            /* background-color: rgba(255, 255, 255, 0.1); */ /* Parent has background now */
        }
        
        .message-table th {
            background-color: var(--amu-primary); /* AMU Green for table headers */
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        
        .message-table td {
            padding: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .message-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.08);
        }
         .message-table tr:nth-child(even) td {
            background-color: rgba(255, 255, 255, 0.03);
        }
         .message-table tr:nth-child(even):hover td {
            background-color: rgba(255, 255, 255, 0.1);
        }

        #templatemo_footer_panel {
            background-color: rgba(26, 35, 126, 0.85); /* MODIFIED: Footer Navy Blue */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0 auto;
            max-width: 1200px;
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


        @media (max-width: 992px) {
            #templatemo_content_section {
                flex-direction: column;
                align-items: center;
            }
            
            #templatemo_content_left {
                width: 100%;
                max-width: 400px;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                margin-bottom: 20px;
            }
            
            #templatemo_content_right {
                padding: 15px;
                width: 100%;
            }
            
            #templatemo_menu ul {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            #templatemo_menu li {
                margin: 5px;
            }
        }

        @media (max-width: 768px) {
            #templatemo_top_panel {
                flex-direction: column;
                height: auto;
                padding: 15px;
            }
            
            #templatemo_top_panel img {
                display: none;
            }
            
            #site_title {
                margin: 10px 0;
                font-size: 1.2rem;
            }
             #templatemo_menu ul {
                flex-direction: column;
                align-items: center;
            }

            .right_column_section {
                padding: 20px;
            }
            .right_column_section_title {
                font-size: 1.5rem;
            }
            
            .search-form {
                flex-direction: column; /* Stack search input and button */
            }
            .search-form input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .search-form input[type="submit"] {
                width: 100%;
                margin-left: 0;
            }
            .message-table {
                font-size: 0.9rem;
            }
             .message-table th, .message-table td {
                padding: 10px;
            }
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
                <li><a href="driver.php">Home</a></li>
                <li><a href="requestmaintenance1.php">Request Maintenance</a></li>
                <li><a href="#" class="current">View</a>
                    <ul>
                        <li><a href="dviewschedule.php">View Schedule</a></li>
                        <li><a href="viewmessage.php" class="current">View Messages</a></li>
                        <li><a href="exit11.php">View Permission</a></li>
                    </ul>
                </li>
                <li><a href="exitrequest.php">Request Exit</a></li>
                <li><a href="changepssdriver.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <div id="login_section">
                    <div id="login_section_title">Driver Portal</div>
                    <img src="Driverr.png" alt="Driver">
                    <div id="login_section_middle">
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    </div>
                </div>
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        View Messages
                    </div>
                    <div class="right_column_section_body">
                        <?php if (!empty($status_message)): ?>
                            <div class="status-message status-<?php echo htmlspecialchars($status_type); ?>">
                                <?php echo $status_message; // Already HTML escaped in PHP block ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="search-form">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="text" name="search" placeholder="Search messages or sender..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                                <input type="submit" value="Search">
                            </form>
                        </div>
                        
                        <?php if (!empty($messages)): ?>
                            <div class="message-table-container">
                                <table class="message-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>From</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($messages as $message): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($message['date'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($message['frm'] ?? 'Unknown'); ?></td>
                                                <td><?php echo nl2br(htmlspecialchars($message['message'] ?? '')); // Use nl2br to preserve line breaks ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p style="text-align: center; color: var(--amu-text-secondary); padding: 20px;">
                                <?php echo empty($searchTerm) ? 'No messages found.' : 'No messages match your search criteria.'; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
     <script>
        // Client-side session check (optional)
        /*
        fetch('check_login.php') // Make sure check_login.php exists and works
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') {
                    window.location.href = 'index.html';
                }
            })
            .catch(error => console.error('Error checking login status:', error));
        */
    </script>
</body>
</html>