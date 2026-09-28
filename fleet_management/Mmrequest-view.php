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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Requests | AMU Fleet System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32;
            --amu-primary-dark: #1b5e20;
            /* --amu-dark: #121212; */ /* Original black, replaced by navy */
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-success: #28a745;
            --amu-danger: #dc3545;

            /* Navy Blue Theme Variables */
            --amu-navy-bg-header-footer: rgba(26, 35, 126, 0.85);
            --amu-navy-bg-panel: rgba(26, 35, 126, 0.75);
            --amu-navy-bg-table-container: rgba(26, 35, 126, 0.65); /* For table background if needed */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: url('Amu gate.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            color: var(--amu-text);
            min-height: 100vh;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Header Styles */
        #templatemo_top_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            padding: 10px 5%;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .logo-container {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 60px;
            max-width: 120px;
        }

        #templatemo_top_panel img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        #site_title {
            font-size: clamp(0.9rem, 3vw, 1.3rem);
            font-weight: 700;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 10px;
            flex: 2;
            min-width: 180px;
        }

        #templatemo_menu {
            flex: 3;
            min-width: 200px;
            max-width: 100%;
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }

        #templatemo_menu li {
            margin: 2px 0;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: clamp(0.7rem, 2vw, 0.85rem);
            display: inline-flex; /* For icon alignment */
            align-items: center; /* For icon alignment */
            gap: 5px; /* Space for icon */
            white-space: nowrap;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary);
        }

        /* Main Content */
        #templatemo_content_panel {
            flex: 1;
            padding: 20px 0; /* Increased padding */
            width: 100%;
            overflow: hidden;
            backdrop-filter: blur(2px);
        }

        #templatemo_content_section {
            /* display: flex; /* This will be handled by the child .templatemo_content_right */
            /* flex-direction: column; /* Original, not needed if left is removed */
            width: 95%;
            max-width: 1200px; /* Or your preferred width for the table */
            margin: 0 auto;
            /* gap: 15px; /* Original, not needed if left is removed */
        }

        /* Sidebar - #templatemo_content_left and its children are REMOVED */
        /*
        #templatemo_content_left { ... }
        #login_section { ... }
        #login_section_title { ... }
        #login_section img { ... }
        */

        /* Main Content Area */
        #templatemo_content_right {
            /* order: 2; /* Original, not needed */
            width: 100%; /* Takes full width now */
        }

        .right_column_section {
            background-color: var(--amu-navy-bg-panel); /* MODIFIED */
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        .right_column_section_title {
            font-size: clamp(1.2rem, 3vw, 1.6rem); /* Slightly larger title */
            font-weight: 600;
            padding: 15px; /* Adjusted padding */
            color: var(--amu-text); /* MODIFIED for contrast */
            text-align: center;
            /* background-color: rgba(0, 0, 0, 0.75); /* Original, now inherits from parent */
            width: 100%;
            border-bottom: 1px solid rgba(255,255,255,0.1); /* Separator line */
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 15px; /* Adjusted padding */
            box-sizing: border-box;
             /* background-color: var(--amu-navy-bg-table-container); /* Optional: if table needs distinct bg */
        }

        /* Table Styles */
        .request-table {
            width: 100%;
            border-collapse: collapse;
            /* background-color: rgba(255, 255, 255, 0.1); /* Original, less needed if container is styled */
            table-layout: fixed; /* Keeps columns even on mobile initially */
        }

        .request-table th,
        .request-table td {
            padding: 10px 12px; /* Adjusted padding */
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15); /* Slightly more visible border */
            word-break: break-word;
            color: var(--amu-text-secondary);
        }
        .request-table td {
            color: var(--amu-text); /* Data cells brighter */
        }


        .request-table th {
            background-color: var(--amu-primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1; /* Ensure header stays above content when scrolling */
            font-size: clamp(0.75rem, 2vw, 0.9rem); /* Adjusted */
        }

        .request-table td {
            font-size: clamp(0.7rem, 2vw, 0.85rem);
        }

        .request-table tr:hover td { /* Hover effect for rows */
            background-color: rgba(255, 255, 255, 0.08);
        }

        /* Footer */
        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-header-footer); /* MODIFIED */
            padding: 15px 5%; /* Adjusted padding */
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: clamp(0.75rem, 2vw, 0.85rem); /* Adjusted */
            width: 100%;
            margin-top: auto; /* Push to bottom */
        }
        #templatemo_footer_section { /* Added for footer text styling */
            color: rgba(255,255,255,0.7);
        }

        #templatemo_footer_section a {
            color: var(--amu-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary-dark);
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (min-width: 768px) {
            /* #templatemo_content_section {
                flex-direction: row; /* Original, no longer needed if only one main content block */
            } */
            
            /* #templatemo_content_left rules are removed */
            
            #templatemo_content_right {
                /* flex: 1; /* Original, not strictly needed if it's the only child and width is 100% */
                /* order: 2; /* Original, not needed */
            }

            .request-table {
                table-layout: auto; /* Allow table to naturally size columns on larger screens */
            }
        }

        /* Mobile Table View */
        @media (max-width: 767px) {
            .request-table {
                display: block;
                width: 100%;
            }
            
            .request-table thead {
                display: none;
            }
            
            .request-table tbody {
                display: block;
                width: 100%;
            }
            
            .request-table tr {
                display: block;
                margin-bottom: 15px; /* Increased spacing */
                border: 1px solid var(--amu-primary);
                border-radius: 8px; /* Softer radius */
                overflow: hidden;
                background-color: var(--amu-navy-bg-table-container); /* Use navy theme for cards */
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
            
            .request-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 12px; /* Adjusted padding */
                text-align: right;
                border-bottom: 1px dotted rgba(255, 255, 255, 0.2); /* Dotted separator */
            }
            .request-table tr td:last-child {
                border-bottom: none; /* No border for last cell in card */
            }
            
            .request-table td:before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 10px; /* Adjusted margin */
                color: var(--amu-primary);
                text-align: left;
                flex-basis: 40%; /* Give label more defined space */
                padding-right: 5px;
            }
            
            .request-table td:after { /* This was likely a placeholder, removing as value is in cell */
                /* content: ''; */
                /* flex: 2; */
                /* text-align: left; */
                /* padding-left: 10px; */
            }
             .request-table td span.cell-value { /* Wrap cell content in a span if more control needed */
                flex-basis: 60%;
                text-align: right;
            }
        }

        @media (max-width: 480px) {
            #templatemo_top_panel {
                flex-direction: column;
                align-items: center;
                gap: 10px;
                height: auto; /* Allow header to grow */
                padding-top: 15px;
                padding-bottom: 15px;
            }
            
            .logo-container { /* Hide logos on very small screens to save space */
                display: none;
            }
            
            #site_title {
                order: 1;
                width: 100%;
                margin-bottom: 10px;
            }
            
            #templatemo_menu {
                order: 2;
                width: 100%;
            }
            
            #templatemo_menu ul {
                justify-content: space-around;
            }
            
            /* #login_section img styles are removed as the section is gone */
        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') { // Robust check
                    window.location.href = 'index.html';
                }
            })
            .catch(error => console.error('Error checking login status:', error));

        // Enhance mobile table display
        document.addEventListener('DOMContentLoaded', function() {
            function setupMobileTable() {
                if (window.innerWidth <= 767) {
                    const table = document.querySelector('.request-table');
                    if (table) {
                        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
                        const rows = table.querySelectorAll('tbody tr');
                        
                        rows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            cells.forEach((cell, i) => {
                                if (i < headers.length) {
                                    cell.setAttribute('data-label', headers[i]);
                                    // Optional: Wrap cell content for better styling if needed for td:after removal
                                    // if (!cell.querySelector('.cell-value')) {
                                    //    cell.innerHTML = `<span class="cell-value">${cell.innerHTML}</span>`;
                                    // }
                                }
                            });
                        });
                    }
                } else { // Reset for larger screens if styles were applied
                    const table = document.querySelector('.request-table');
                     if (table) {
                        const rows = table.querySelectorAll('tbody tr');
                        rows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            cells.forEach((cell) => {
                                cell.removeAttribute('data-label');
                                // const cellValue = cell.querySelector('.cell-value');
                                // if (cellValue) cell.innerHTML = cellValue.innerHTML;
                            });
                        });
                    }
                }
            }

            setupMobileTable();
            window.addEventListener('resize', setupMobileTable);
        });
    </script>
</head>
<body>
    <div id="templatemo_top_panel">
        <div class="logo-container">
            <img src="wou arm.jpg.png" alt="AMU Logo">
        </div>
        <div id="site_title">AMU FLEET MANAGEMENT SYSTEM</div>
        <div id="templatemo_menu">
            <ul>
                <li><a href="mechanic.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="Mmrequest-view.php" class="current"><i class="fas fa-tools"></i> veiw Requests</a></li>
                <li><a href="massage2.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="changepssmechanic.php"><i class="fas fa-key"></i> Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="logo-container" style="justify-content: flex-end;">
            <img src="wou arm.jpg.png" alt="AMU Logo">
        </div>
    </div>

    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <!-- #templatemo_content_left has been removed -->
            
            <div id="templatemo_content_right">
                <div class="right_column_section">
                    <div class="right_column_section_title">
                        <i class="fas fa-wrench"></i> Maintenance Requests
                    </div>
                    <div class="table-container">
                        <?php
                        // Ensure config.php is in the same directory or adjust path
                        // include_once('config.php'); // Use include_once or require_once
                        
                        // ---- START MOCK DB CONNECTION & DATA for testing without DB ----
                        // This is for standalone testing if config.php is problematic.
                        // Remove or comment this block when using your actual config.php
                        
                        $conn = new stdClass(); $conn->connect_error = null; $conn->error = null;
                        $mock_data = [
                            ['Driver_id' => 'D001', 'Driver_Name' => 'John Doe', 'Mechanic_Name' => 'Mike R.', 'PlateNo' => 'AB 123 CD', 'Vehicle_Type' => 'Toyota Hilux', 'Date' => '2023-10-26', 'Problem_of_vehicle' => 'Engine oil change required and brake pads replacement.'],
                            ['Driver_id' => 'D002', 'Driver_Name' => 'Jane Smith', 'Mechanic_Name' => 'Sarah K.', 'PlateNo' => 'EF 456 GH', 'Vehicle_Type' => 'Ford Ranger', 'Date' => '2023-10-25', 'Problem_of_vehicle' => 'Flat tire, needs repair or replacement. Check alignment.'],
                            ['Driver_id' => 'D003', 'Driver_Name' => 'Alex Lee', 'Mechanic_Name' => 'Mike R.', 'PlateNo' => 'IJ 789 KL', 'Vehicle_Type' => 'Nissan NP200', 'Date' => '2023-10-27', 'Problem_of_vehicle' => 'Strange noise from the exhaust system. Needs inspection.'],
                        ];
                        $current_row = 0;
                        $mock_result = new stdClass();
                        $mock_result->num_rows = count($mock_data); // Add num_rows for checking
                        $mock_result->fetch_assoc = function() use (&$mock_data, &$current_row) {
                            if ($current_row < count($mock_data)) { return $mock_data[$current_row++]; } return null;
                        };
                        $conn->query = function($query) use ($mock_result) { return $mock_result; };
                        $conn->close = function() {};
                        // ---- END MOCK DB CONNECTION & DATA ----
                        
                        // **IMPORTANT**: Comment out or remove the MOCK block above and UNCOMMENT the line below
                        //                when you have your actual 'config.php' ready.
                        include_once('config.php');


                        if ($conn->connect_error) {
                            die("<p style='color:red; text-align:center; padding:10px;'>Connection failed: " . htmlspecialchars($conn->connect_error) . "</p>");
                        }

                        $sql = "SELECT Driver_id, Driver_Name, Mechanic_Name, PlateNo, Vehicle_Type, Date, Problem_of_vehicle FROM request ORDER BY Date DESC";
                        $result = $conn->query($sql);

                        if (!$result) {
                            die("<p style='color:red; text-align:center; padding:10px;'>Error fetching requests: " . htmlspecialchars($conn->error) . "</p>");
                        }

                        if ($result->num_rows > 0) {
                            echo "<table class='request-table'>";
                            echo "<thead><tr> 
                                    <th>Driver ID</th>  
                                    <th>Driver</th>
                                    <th>Mechanic</th> 
                                    <th>Plate No</th> 
                                    <th>Vehicle</th>
                                    <th>Date</th> 
                                    <th>Problem Details</th>
                                  </tr></thead><tbody>";

                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo '<td data-label="Driver ID">' . htmlspecialchars($row['Driver_id']) . '</td>';
                                echo '<td data-label="Driver">' . htmlspecialchars($row['Driver_Name']) . '</td>';
                                echo '<td data-label="Mechanic">' . htmlspecialchars($row['Mechanic_Name']) . '</td>';
                                echo '<td data-label="Plate No">' . htmlspecialchars($row['PlateNo']) . '</td>';
                                echo '<td data-label="Vehicle">' . htmlspecialchars($row['Vehicle_Type']) . '</td>';
                                echo '<td data-label="Date">' . htmlspecialchars($row['Date']) . '</td>';
                                echo '<td data-label="Problem">' . htmlspecialchars($row['Problem_of_vehicle']) . '</td>';
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p style='text-align:center; padding: 20px; color: var(--amu-text-secondary);'>No maintenance requests found.</p>";
                        }
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="templatemo_footer_panel">
        <div id="templatemo_footer_section">
        Copyright © <?php echo date("Y"); ?> <a href="#">AMU University</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a>
        </div>
    </div>
</body>
</html>