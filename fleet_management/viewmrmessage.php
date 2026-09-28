<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include('config.php');

// Initialize variables
$search = '';
$messages = [];
$noResults = false;

// Check if search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search = trim($_POST["search"]);
    
    $query = "SELECT * FROM message WHERE too = 'Manager' AND (frm LIKE ? OR message LIKE ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $search_param = "%" . $search . "%";
        $stmt->bind_param("ss", $search_param, $search_param); 
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $messages = $result->fetch_all(MYSQLI_ASSOC);
            $noResults = count($messages) === 0;
        } else {
            // Optional: Log error or display a generic error to the user
            // echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Optional: Log error or display a generic error to the user
        // echo "Error preparing query: " . $conn->error;
    }
}
// Only close connection if it was successfully opened in config.php
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            --amu-navy-base: #000080; 
            --amu-navy-heavy-transparent: rgba(0, 0, 128, 0.85);
            --amu-navy-medium-transparent: rgba(0, 0, 128, 0.75);
            --amu-navy-light-transparent: rgba(0, 0, 128, 0.5);
            --amu-navy-lighter-transparent: rgba(0, 0, 128, 0.3);
            --amu-navy-hover-transparent: rgba(0, 0, 128, 0.2); 
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-danger: #dc3545;
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

        #templatemo_top_panel {
            background-color: var(--amu-navy-heavy-transparent); 
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
            background-color: rgba(0, 0, 128, 0.9); 
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-panel {
            display: flex; /* This allows content-right to expand */
            min-height: calc(100vh - 160px); /* Header (80px) + Footer (80px estimate if footer has fixed height too) */
            padding: 20px 0; /* Vertical padding, horizontal handled by content-right */
        }

        /* .content-left and .login-section styles removed as the element is removed */

        .content-right {
            flex: 1; /* Takes up all available space */
            padding: 20px 40px;
            overflow-x: auto; /* Important for table responsiveness */
            display: flex; /* To center messages-container if it's not max-width 100% */
            justify-content: center; /* Centers messages-container horizontally */
        }

        .messages-container {
            background-color: var(--amu-navy-medium-transparent); 
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            width: 100%; /* Take full width of content-right */
            max-width: 1200px; /* But don't get too wide on large screens */
            /* margin: 0 auto; -- No longer needed if content-right uses flex to center */
        }

        .messages-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--amu-primary);
            text-align: center;
        }

        .search-form {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .search-input {
            flex: 1;
            max-width: 400px;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: var(--amu-navy-light-transparent); 
            color: var(--amu-text);
            font-size: 1rem;
        }

        .search-input:focus {
            border-color: var(--amu-primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3);
        }

        .search-btn {
            padding: 12px 25px;
            background-color: var(--amu-primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .search-btn:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
        }

        .data-table-wrapper { 
            overflow-x: auto;
            width: 100%;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: var(--amu-navy-lighter-transparent); 
            min-width: 700px; /* Adjusted min-width for better single-column view */
        }

        .data-table th {
            background-color: var(--amu-primary); 
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0; 
            z-index: 1;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            white-space: nowrap; 
        }
        .data-table td:nth-child(5) { /* Message column */
            white-space: normal; 
            min-width: 200px; 
        }


        .data-table tr:hover {
            background-color: var(--amu-navy-hover-transparent); 
        }

        .action-btn {
            color: var(--amu-danger);
            text-decoration: none;
            font-size: 1.2rem;
            transition: all 0.3s;
            display: inline-block; 
        }

        .action-btn:hover {
            color: #c82333; 
            transform: scale(1.2);
        }

        .no-messages {
            text-align: center;
            padding: 30px;
            font-size: 1.2rem;
            color: var(--amu-text-secondary);
            background-color: var(--amu-navy-light-transparent); 
            border-radius: 8px;
            margin: 20px 0;
        }
        .no-messages i {
            margin-right: 8px;
            color: var(--amu-primary); 
        }

        .clock-display-wrapper { 
            text-align: center;
            margin-bottom: 20px;
        }
        .clock-display {
            font-size: 1.2rem;
            color: var(--amu-primary);
            font-family: 'Arial', sans-serif;
            background-color: var(--amu-navy-light-transparent); 
            padding: 10px 15px;
            border-radius: 6px;
            display: inline-block; 
        }

        #templatemo_footer_panel {
            background-color: var(--amu-navy-heavy-transparent); 
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: relative; /* Ensure it's part of the flow for min-height calculation */
            margin-top: auto; /* Pushes footer to bottom if content is short */
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
        #templatemo_footer_section a {
            color: var(--amu-primary); 
            text-decoration: none;
        }
        #templatemo_footer_section a:hover {
            text-decoration: underline;
        }


        @media (max-width: 768px) { /* Combined media queries for simplicity now */
            .content-right {
                padding: 20px 15px; /* Adjust padding */
            }
            .messages-container {
                padding: 15px;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-input {
                max-width: none; 
            }
            
            .data-table {
                font-size: 0.85rem;
                min-width: 0; 
            }
             .data-table td {
                white-space: normal; 
            }
            
            .data-table th, 
            .data-table td {
                padding: 8px 10px;
            }
            .messages-title {
                font-size: 1.5rem; 
            }
            .clock-display {
                font-size: 1rem;
                padding: 8px 12px;
            }
        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(data => {
                if (data === 'false') {
                    window.location.href = 'index.html';
                }
            })
            .catch(error => {
                console.error('Error checking login status:', error);
            });
            
        // Live clock display
        function showTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateString = now.toLocaleDateString([], { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            
            const clockElements = document.querySelectorAll('.clock-display');
            clockElements.forEach(el => {
                if (el) {
                    el.textContent = `${dateString} | ${timeString}`;
                }
            });
            
            setTimeout(showTime, 1000);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.clock-display')) {
                showTime();
            }
        });
        
        function confirmDelete(messageId) {
            return confirm(`Are you sure you want to delete message #${messageId}? This action cannot be undone.`);
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
                <li><a href="manager.php">Home</a></li>
                <li><a href="#">Vehicle</a>
                    <ul>
                        <li><a href="vehicle-register.html">Register vehicle</a></li>
                        <li><a href="view1.php">Update vehicle</a></li>
                        <li><a href="searchvinfo.html">Search vehicles</a></li>
                    </ul>
                </li>
                <li><a href="#" class="current">View</a>
                    <ul>
                        <li><a href="mviewschedule.php">View schedule</a></li>
                        <li><a href="exitrequest1.php">View exit request</a></li>
                        <li><a href="mrequest-view.php">View maintenance request</a></li>
                        <li><a href="mmessage.php" class="current">View message</a></li>
                        <li><a href="comment12.php">View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php">Fuel</a></li>
                <li><a href="upload.php">Report</a></li>
                 <li><a href="changepssmanager.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-panel">
        <!-- Left content panel removed -->
        
        <div class="content-right">
            <div class="messages-container">
                <h1 class="messages-title">View Messages Received</h1>
                
                <div class="clock-display-wrapper">
                    <div class="clock-display">Loading time...</div>
                </div>
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="search-form">
                    <input type="text" name="search" class="search-input" placeholder="Search in From or Message content..." value="<?php echo htmlspecialchars($search); ?>" required>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
                
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                    <?php if (!empty($messages)): ?>
                        <div class="data-table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Phone</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($message['m_id']); ?></td>
                                            <td><?php echo htmlspecialchars($message['frm']); ?></td>
                                            <td><?php echo htmlspecialchars($message['too']); ?></td>
                                            <td><?php echo htmlspecialchars($message['pnumber']); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
                                            <td><?php echo htmlspecialchars(date("Y-m-d H:i", strtotime($message['Date']))); ?></td>
                                            <td align="center">
                                                <a href="tt.php?m_id=<?php echo urlencode($message['m_id']); ?>&view=delete" 
                                                   class="action-btn"
                                                   onclick="return confirmDelete('<?php echo htmlspecialchars($message['m_id']); ?>')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif ($noResults): ?>
                        <div class="no-messages">
                            <i class="fas fa-exclamation-circle"></i> No messages found matching your search term "<strong><?php echo htmlspecialchars($search); ?></strong>".
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="no-messages">
                        <i class="fas fa-envelope-open-text"></i> Use the search form above to view messages sent to the Manager.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
            © <?php echo date("Y"); ?> All Rights Reserved and Protected | <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>