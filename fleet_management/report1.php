<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Include the configuration file
include('config.php'); // Ensure this doesn't output anything

// Check database connection
if ($conn->connect_error) {
    // It's better to display an error message on the page or log it,
    // rather than die() here if the HTML structure is already being output.
    // For now, keeping original behavior if this was intended.
    die('Could not connect: ' . $conn->connect_error);
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
    <title>Reports | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, reports">
    <meta name="description" content="AMU Fleet Management System - View Reports">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32; /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;
            --amu-new-bg: rgba(26, 35, 126, 0.85); /* Navy Blue */
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
            display: flex; /* For footer positioning */
            flex-direction: column; /* For footer positioning */
        }

        #templatemo_top_panel {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
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
            flex-grow: 1; /* Allows title to take available space */
            margin: 0 15px; /* Adds some spacing around the title */
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap; /* Allow menu items to wrap */
            justify-content: center; /* Center items if they wrap */
        }

        #templatemo_menu li {
            position: relative;
            margin: 0 5px; /* Reduced margin for tighter fit if wrapping */
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 8px 10px; /* Adjusted padding */
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem; /* Slightly smaller font for more items */
            white-space: nowrap;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary); /* Original color */
        }

        #templatemo_menu ul ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: rgba(0, 0, 0, 0.9); /* Original color */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
            z-index: 1001; /* Ensure dropdown is above */
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-container {
            padding: 40px 5%;
            min-height: calc(100vh - 160px); /* Header and Footer height */
            backdrop-filter: blur(2px); /* Blur background behind content */
            flex: 1; /* Allow container to grow */
            display: flex; /* To center .report-container */
            justify-content: center;
            align-items: flex-start; /* Align to top */
        }

        .report-container {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* Optional, parent has blur */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            margin-bottom: 30px; /* Original margin */
            width: 100%; /* Take full width of parent */
            max-width: 1200px; /* Max width for report content */
        }

        .report-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #fff; /* MODIFIED: White for better contrast */
            text-align: center;
            text-transform: uppercase;
            position: relative;
            padding-bottom: 10px;
        }
        .report-title::after { /* Underline for main title */
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 2px;
            background-color: var(--amu-primary); /* Original color */
        }


        .report-subtitle {
            font-size: 1.4rem; /* Slightly larger for better hierarchy */
            color: #fff; /* MODIFIED: White for better contrast */
            text-align: center;
            margin-bottom: 15px; /* Adjusted margin */
            margin-top: 30px; /* Space above subtitle */
            padding-bottom: 5px;
            border-bottom: 1px dashed rgba(255,255,255,0.2);
        }


        .date-range {
            text-align: center;
            font-size: 1rem;
            margin-bottom: 20px; /* Adjusted margin */
            color: var(--amu-text-secondary);
        }
        .date-range strong {
            color: var(--amu-text); /* Make dates stand out a bit */
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            /* background-color: rgba(0,0,0,0.1); /* Lighter background for table if needed */
        }

        .report-table th {
            background-color: var(--amu-primary); /* Original color */
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            position: sticky; /* For sticky headers */
            top: 0;
            z-index: 1; /* Ensure headers are above table content */
        }

        .report-table td {
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .report-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.03); /* Subtle striping */
        }

        .report-table tr:hover {
            background-color: rgba(255, 255, 255, 0.08); /* Subtle hover */
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .action-button {
            background-color: var(--amu-primary); /* Original color */
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex; /* For icon alignment */
            align-items: center;
            gap: 8px; /* Space between icon and text */
        }

        .action-button:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
        }

        .no-results {
            text-align: center;
            color: var(--amu-danger);
            padding: 20px;
            font-size: 1.1rem;
            background-color: rgba(0,0,0,0.1); /* Light background for message */
            border-radius: 6px;
            border: 1px dashed var(--amu-danger);
        }

        #templatemo_footer_panel {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            /* margin-top: auto; /* Ensured by flex on body */
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
         #templatemo_footer_section a {
            color: #4caf50; /* Original color */
            text-decoration: none;
            transition: color 0.3s;
        }
        #templatemo_footer_section a:hover {
            color: var(--amu-primary); /* Original color */
            text-decoration: underline;
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
                margin: 10px 0 15px;
                font-size: 1.2rem;
            }
            
            #templatemo_menu ul {
                flex-wrap: wrap;
                justify-content: center;
                flex-direction: column;
                align-items: center;
            }
            
            #templatemo_menu li {
                margin: 5px 0;
            }
            .content-container {
                padding: 20px;
            }
            
            .report-container {
                padding: 20px;
            }
            .report-title {
                font-size: 1.6rem;
            }
            .report-subtitle {
                font-size: 1.2rem;
            }
            
            .report-table {
                font-size: 0.85rem;
                display: block; /* For horizontal scroll on small screens */
                overflow-x: auto;
                white-space: nowrap;
            }
             .report-table th, .report-table td {
                white-space: nowrap; /* Prevent text wrapping */
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            .action-button {
                width: 100%;
            }
        }

        @media print {
            body {
                background: none;
                color: #000;
                margin: 20px; /* Add some margin for printing */
                font-size: 10pt; /* Adjust base font size for print */
            }
            
            .report-container {
                background: none;
                box-shadow: none;
                border: 1px solid #ccc; /* Add a light border for printed table */
                padding: 0;
                width: 100%;
                max-width: 100%;
                margin: 0;
                backdrop-filter: none;
            }
            .report-title, .report-subtitle {
                color: #000;
            }
            .report-title { font-size: 16pt; }
            .report-subtitle { font-size: 14pt; }
            .report-title::after, .report-subtitle::after {
                 background-color: #000;
            }

            .date-range {
                color: #000;
            }
            .date-range strong {
                color: #000;
            }
            .report-table {
                width: 100%;
                border: 1px solid #ccc;
                font-size: 9pt; /* Smaller font for print table */
            }
            
            .report-table th, .report-table td {
                border: 1px solid #ccc;
                color: #000;
                padding: 6px; /* Reduce padding for print */
            }
            .report-table th {
                background-color: #f0f0f0 !important; /* Light grey for print header */
                color: #000 !important;
            }
            .report-table tr:nth-child(even) {
                background-color: #f9f9f9 !important; /* Light striping for print */
            }

            
            #templatemo_top_panel, #templatemo_footer_panel, .action-buttons {
                display: none !important;
            }
        }
    </style>
    <script>
        function printpage() {
            window.print();
        }
        
        // Client-side session check (optional)
        /*
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') { // Ensure robust check
                    window.location.href = 'index.html';
                }
            })
            .catch(error => console.error('Error checking session:', error));
        */
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
                <li><a href="#">Account</a>
                    <ul>
                        <li><a href="createaccount.php">Create account</a></li>
                        <li><a href="view.php">Update account</a></li>
                        <li><a href="view2.php">Delete account</a></li>
                    </ul>
                </li>
                <li><a href="aviewschedule.php">View Schedule</a></li>
                <li><a href="upload1.php" class="current">Report</a></li> <!-- Assuming this is report.php -->
                <li><a href="changepssadmin.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-container">
        <div class="report-container">
            <h1 class="report-title">FLEET MANAGEMENT SYSTEM REPORT</h1>
            
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
                <?php
                // Sanitize the input to prevent SQL injection
                // Use prepared statements for date comparison as dates can be tricky
                $a_from_input = $_POST['dayfrom']; // DD-MM-YYYY
                $b_to_input = $_POST['dayto'];   // DD-MM-YYYY
                
                $a_from_db = '';
                $b_to_db = '';
                $valid_dates = true;

                // Convert DD-MM-YYYY to YYYY-MM-DD for MySQL
                if (preg_match("/^\d{2}-\d{2}-\d{4}$/", $a_from_input)) {
                    $date_parts = explode('-', $a_from_input);
                    $a_from_db = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
                } else {
                    $valid_dates = false;
                }
                if (preg_match("/^\d{2}-\d{2}-\d{4}$/", $b_to_input)) {
                    $date_parts = explode('-', $b_to_input);
                    $b_to_db = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
                } else {
                    $valid_dates = false;
                }

                ?>
                
                <!-- Registered Employee Report -->
                <div class="report-section">
                    <h2 class="report-subtitle">REGISTERED EMPLOYEE REPORT</h2>
                    <div class="date-range">
                        From: <strong><?php echo htmlspecialchars($_POST['dayfrom']); ?></strong> 
                        To: <strong><?php echo htmlspecialchars($_POST['dayto']); ?></strong>
                    </div>
                    
                    <?php
                    if ($conn && $valid_dates) {
                        // Assuming 'Date' column in User_registration is DATE or DATETIME
                        $query_emp = "SELECT User_id, First_Name, Last_Name, Sex, Role, Email, Mobile_No, Date FROM User_registration WHERE Date BETWEEN ? AND ?";
                        $stmt_emp = $conn->prepare($query_emp);
                        
                        if ($stmt_emp) {
                            $stmt_emp->bind_param("ss", $a_from_db, $b_to_db);
                            $stmt_emp->execute();
                            $result_emp = $stmt_emp->get_result();
                        
                            if ($result_emp->num_rows == 0) : ?>
                                <div class="no-results">
                                    No employee registration records found for the selected date range.
                                </div>
                            <?php else : ?>
                                <table class="report-table">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Sex</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th>Mobile No</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result_emp->fetch_assoc()) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['User_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['First_Name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['Last_Name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['Sex']); ?></td>
                                                <td><?php echo htmlspecialchars($row['Role']); ?></td>
                                                <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['Mobile_No']); ?></td>
                                                <td><?php echo htmlspecialchars($row['Date']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php endif; 
                            $stmt_emp->close();
                        } else {
                            echo '<div class="no-results">Error preparing employee report query: ' . htmlspecialchars($conn->error) . '</div>';
                        }
                    } elseif (!$valid_dates) {
                        echo '<div class="no-results">Invalid date format submitted. Please use DD-MM-YYYY.</div>';
                    } else {
                        echo '<div class="no-results">Database connection error.</div>';
                    }
                    ?>
                </div>
                                
                <div class="action-buttons">
                    <a href="upload1.php" class="action-button"><i class="fas fa-arrow-left"></i> Back to Search</a>
                    <button onclick="printpage()" class="action-button"><i class="fas fa-print"></i> Print Report</button>
                </div>

            <?php else : ?>
                <div class="no-results" style="padding: 30px; font-size: 1.2em;">
                    <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                    Please use the search form on the previous page (upload1.php) to generate reports for a specific date range.
                </div>
                <div class="action-buttons" style="margin-top: 30px;">
                    <a href="upload1.php" class="action-button"><i class="fas fa-search"></i> Go to Report Search Page</a>
                </div>
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
<?php
// Close the database connection
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>