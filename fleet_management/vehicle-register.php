<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$con = new mysqli("localhost", "fleet", "11111111", "fleet");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$message = "";
$isError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $PlateNo = htmlspecialchars($_POST['PlateNo']);
    $VehicleType = htmlspecialchars($_POST['VehicleType']);
    $Model = htmlspecialchars($_POST['Model']);
    $ChessisNo = htmlspecialchars($_POST['ChessisNo']);
    $Capacity = htmlspecialchars($_POST['Capacity']);
    $ProductionDate = htmlspecialchars($_POST['ProductionDate']);
    $EngineNo = htmlspecialchars($_POST['EngineNo']);
    $EnginePower = htmlspecialchars($_POST['EnginePower']);
    $Owner = htmlspecialchars($_POST['Owner']);
    $Date = date('d-m-Y'); // Consider Y-m-d for database consistency

    // Check if the vehicle is already registered
    $query = "SELECT * FROM vehicles WHERE ChessisNo = ?";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        die("Prepare failed (select): (" . $con->errno . ") " . $con->error);
    }
    $stmt->bind_param("s", $ChessisNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Sorry! The vehicle is already registered.";
        $isError = true;
    } else {
        // Insert the new vehicle into the database
        $sql = "INSERT INTO vehicles (PlateNo, VehicleType, Model, ChessisNo, Capacity, ProductionDate, EngineNo, EnginePower, Owner, Date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $con->prepare($sql); // Use a new variable for the insert statement
        if (!$stmt_insert) {
            die("Prepare failed (insert): (" . $con->errno . ") " . $con->error);
        }
        $stmt_insert->bind_param("ssssssssss", $PlateNo, $VehicleType, $Model, $ChessisNo, $Capacity, $ProductionDate, $EngineNo, $EnginePower, $Owner, $Date);

        if ($stmt_insert->execute()) {
            $message = "Vehicle registration successful!";
        } else {
            $message = "Error: " . $stmt_insert->error;
            $isError = true;
        }
        $stmt_insert->close(); // Close the insert statement
    }

    // Close the select statement
    $stmt->close();
}

// Close the database connection
$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Registration Result | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, vehicle registration">
    <meta name="description" content="AMU Fleet Management System - Vehicle Registration Result">
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
            display: flex; /* Added for footer positioning */
            flex-direction: column; /* Added for footer positioning */
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
            flex-grow: 1; /* Allows title to take space */
            margin: 0 15px; /* Spacing around title */
        }

        #templatemo_menu ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }

        #templatemo_menu li {
            position: relative;
        }

        #templatemo_menu a {
            color: #fff;
            text-decoration: none;
            padding: 6px 10px; /* Slightly increased padding */
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            display: block;
            white-space: nowrap;
        }

        #templatemo_menu a:hover,
        #templatemo_menu .current {
            background-color: var(--amu-primary); /* Consider changing this accent color */
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
            z-index: 1001; /* Ensure dropdown is above other content */
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-panel { /* This now directly wraps the content-right */
            display: flex;
            flex-direction: column; /* To center its child */
            align-items: center; /* To center .result-container horizontally */
            justify-content: center; /* To center .result-container vertically */
            flex: 1; /* Takes remaining vertical space */
            min-height: calc(100vh - 160px); /* Header and Footer height */
            padding: 40px 20px; /* Padding for the overall content area */
            width: 100%;
            backdrop-filter: blur(2px); /* Apply blur to the background seen through this panel */
        }

        /* .content-left styles removed as the element is removed */

        .content-right { /* This is now the main content holder */
            width: 100%; /* Takes full width of its parent */
            max-width: 800px; /* Max width for the result container itself */
            /* padding: 20px 40px; Removed, padding is on .result-container */
        }

        .result-container {
            background-color: var(--amu-new-bg); /* MODIFIED to Navy Blue */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            /* backdrop-filter: blur(8px); /* Optional: can be here for stronger effect, or on parent */
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px; /* Increased padding */
            /* max-width: 800px; /* Moved to .content-right for overall centering */
            /* margin: 0 auto; /* Moved to .content-right for overall centering */
            text-align: center;
        }

        .result-title {
            font-size: 2rem; /* Increased size */
            font-weight: 600;
            margin-bottom: 30px;
            color: #fff; /* MODIFIED: White for better contrast on navy blue */
        }

        .result-message {
            font-size: 1.2rem;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 6px;
        }

        .success {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--amu-success);
            border: 1px solid var(--amu-success);
        }

        .error {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--amu-danger);
            border: 1px solid var(--amu-danger);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--amu-primary); /* Consider changing this accent color */
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
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
            color: #4caf50; /* Consider changing this link color */
            text-decoration: none;
            transition: color 0.3s;
        }

        #templatemo_footer_section a:hover {
            color: var(--amu-primary); /* Consider changing hover color */
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
                font-size: 1.2rem; /* Adjust title size */
            }
            
            #templatemo_menu ul {
                flex-direction: column;
                align-items: center;
            }
            
            #templatemo_menu li {
                margin: 5px 0;
            }
            
            .content-panel {
                padding: 20px; /* Adjust padding for smaller screens */
                justify-content: flex-start; /* Align content to top on mobile */
                min-height: calc(100vh - 130px); /* Adjust if header height changes on mobile */
            }
            
            .content-right {
                padding: 0; /* Let .result-container handle its padding */
            }
            .result-container {
                padding: 25px; /* Adjust padding */
            }
            .result-title {
                font-size: 1.6rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
    <script>
        // Client-side session check (optional, if check_login.php is implemented)
        /*
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data.trim().toLowerCase() === 'false') {
                    window.location.href = 'index.html';
                }
            }).catch(error => console.error('Error checking login status:', error));
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
                <li><a href="manager.php">Home</a></li>
                <li><a href="#" class="current">Vehicle</a>
                    <ul>
                        <li><a href="vehicle-register.html">Register vehicle</a></li>
                        <li><a href="view1.php">Update vehicle</a></li>
                        <li><a href="searchvinfo.html">Search vehicles</a></li>
                    </ul>
                </li>
                <li><a href="#">View</a>
                    <ul>
                        <li><a href="mviewschedule.php">View schedule</a></li>
                        <li><a href="exitrequest1.php">View exit request</a></li>
                        <li><a href="mrequest-view.php">View maintenance request</a></li>
                        <li><a href="mmessage.php">View message</a></li>
                        <li><a href="comment12.php">View comment</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php">Fuel</a></li>
                <li><a href="upload.html" class="current">Report</a></li>
                <li><a href="index.html">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
    <div class="content-panel">
        <!-- Left content panel removed -->
        <div class="content-right">
            <div class="result-container">
                <h1 class="result-title">Registration Result</h1>
                <div class="result-message <?php echo $isError ? 'error' : 'success'; ?>">
                    <?php echo htmlspecialchars($message); // Ensure message is escaped ?>
                </div>
                
                <div class="action-buttons">
                    <a href="vehicle-register.html" class="btn btn-primary">Register Another Vehicle</a>
                    <a href="manager.php" class="btn btn-primary">Return to Dashboard</a>
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
</body>
</html>