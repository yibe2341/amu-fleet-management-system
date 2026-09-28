<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include("config.php");

if (!isset($_GET['PlateNo'])) {
    die("No vehicle selected for editing.");
}

$PlateNo = $_GET['PlateNo'];

// Fetch current vehicle details
$query = "SELECT * FROM vehicles WHERE PlateNo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $PlateNo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Vehicle not found.");
}

$vehicle = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Update Vehicle | AMU Fleet System</title>
    <meta name="keywords" content="AMU, fleet management, update vehicle">
    <meta name="description" content="AMU Fleet Management System - Update Vehicle">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --amu-primary: #2e7d32; /* AMU Green */
            --amu-primary-dark: #1b5e20; /* Darker AMU Green */
            
            /* New Navy Blue Palette */
            --amu-navy-bg-base: #001A57; /* Base Navy for opaque backgrounds if needed */
            --amu-navy-bg-transparent-heavy: rgba(0, 26, 87, 0.85); /* For top/footer panels */
            --amu-navy-bg-transparent-medium: rgba(0, 26, 87, 0.75); /* For update-container */
            --amu-navy-bg-transparent-light: rgba(0, 26, 87, 0.5); /* For form controls */
            --amu-navy-bg-transparent-very-light: rgba(0, 26, 87, 0.3); /* For readonly form controls */
            --amu-navy-dropdown-bg: rgba(0, 26, 87, 0.9); /* For dropdown menu */

            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0; /* Light text, good contrast on navy */
            --amu-text-secondary: #b0b0b0; /* Secondary light text */
            --amu-success: #28a745;
            --amu-danger: #dc3545;
            --amu-border-light: rgba(255, 255, 255, 0.1); /* Light border for definition */
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
            background-color: var(--amu-navy-bg-transparent-heavy); /* Changed to navy */
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.4); /* Shadow can remain dark */
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
            background-color: var(--amu-navy-dropdown-bg); /* Changed to navy */
            border-radius: 0 0 4px 4px;
            width: 180px;
            padding: 5px 0;
        }

        #templatemo_menu li:hover > ul {
            display: block;
        }

        .content-panel {
            /* display: flex; // No longer needed as content-right is the only child */
            min-height: calc(100vh - 160px); /* Header (80px) + Footer panel (approx 80px visual height) */
            padding: 20px 0; /* Vertical padding for the overall content area */
        }

        /* .content-left and its related styles removed */

        .content-right {
            /* flex: 1; // Not needed if content-panel isn't flex */
            padding: 20px 40px; /* Padding for the area containing update-container */
        }

        .update-container {
            background-color: var(--amu-navy-bg-transparent-medium); /* Changed to navy */
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5); /* Shadow can remain dark */
            backdrop-filter: blur(8px);
            border: 1px solid var(--amu-border-light);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto; /* This will center it within content-right */
        }

        .update-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--amu-primary); /* AMU Green title */
            text-align: center;
        }

        .update-form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--amu-text);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid var(--amu-border-light);
            background-color: var(--amu-navy-bg-transparent-light); /* Changed to navy */
            color: var(--amu-text);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--amu-primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(46, 125, 50, 0.3); /* Focus shadow uses AMU Green */
        }

        .form-control[readonly] {
            background-color: var(--amu-navy-bg-transparent-very-light); /* Changed to navy */
            color: var(--amu-text-secondary);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23e0e0e0' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        .form-actions {
            grid-column: 1 / -1;
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
            border: none;
        }

        .btn-primary {
            background-color: var(--amu-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--amu-danger);
            color: white;
        }

        .btn-secondary:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .full-width {
            grid-column: 1 / -1;
        }

        #templatemo_footer_panel {
            background-color: var(--amu-navy-bg-transparent-heavy); /* Changed to navy */
            padding: 20px 5%;
            text-align: center;
            border-top: 1px solid var(--amu-border-light);
        }

        #templatemo_footer_section {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
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
            }
            
            #templatemo_menu ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            #templatemo_menu li {
                margin: 5px;
            }
            
            .content-panel {
                /* flex-direction: column; // No longer needed as only one child */
            }
            
            /* .content-left styles removed from media query */
            
            .content-right {
                padding: 20px; /* Adjusted padding for smaller screens */
            }
            
            .update-form {
                grid-template-columns: 1fr; /* Single column form on smaller screens */
            }
        }
    </style>
    <script>
        // Client-side session check
        fetch('check_login.php')
            .then(response => response.text())
            .then(data => {
                if (data === 'false') {
                    window.location.href = 'index.html';
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
                <li><a href="manager.php">Home</a></li>
                <li><a href="#" class="current">Vehicle</a>
                    <ul>
                        <li><a href="vehicle-register.html">Register vehicle</a></li>
                        <li><a href="view1.php">Update vehicle</a></li>
                        <li><a href="searchvinfo.html">Search vehicles</a></li>
                    </ul>
                </li>
                <li><a href="fuel.php">Fuel</a></li>
                <li><a href="upload.html">Report</a></li>
                <li><a href="index.php">Logout</a></li>
            </ul>
        </div>
        <img src="wou arm.jpg.png" alt="AMU Logo">
    </div>

    <!-- Main Content Section -->
     <div class="content-panel">
        <!-- Left content panel removed -->
        
        <div class="content-right">
            <div class="update-container">
                <h1 class="update-title">Update Vehicle Information</h1>
                
                <form method="post" action="editvehicle1.php" class="update-form">
                    <input type="hidden" name="Vehicle_id" value="<?php echo htmlspecialchars($vehicle['Vehicle_id']); ?>">

                    <div class="form-group">
                        <label for="PlateNo">Plate Number</label>
                        <input type="text" name="PlateNo" class="form-control" 
                               value="<?php echo htmlspecialchars($vehicle['PlateNo']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="VehicleType">Vehicle Type</label>
                        <select name="VehicleType" class="form-control" required>
                            <option value="Bus" <?php echo $vehicle['VehicleType'] == 'Bus' ? 'selected' : ''; ?>>Bus</option>
                            <option value="Minibus" <?php echo $vehicle['VehicleType'] == 'Minibus' ? 'selected' : ''; ?>>Minibus</option>
                            <option value="Toyota" <?php echo $vehicle['VehicleType'] == 'Toyota' ? 'selected' : ''; ?>>Toyota</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="Model">Model</label>
                        <input type="text" name="Model" class="form-control" 
                               value="<?php echo htmlspecialchars($vehicle['Model']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ChessisNo">Chassis Number</label>
                        <input type="text" name="ChessisNo" class="form-control" 
                               value="<?php echo htmlspecialchars($vehicle['ChessisNo']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="Capacity">Capacity</label>
                        <select name="Capacity" class="form-control" required>
                            <option value="High" <?php echo $vehicle['Capacity'] == 'High' ? 'selected' : ''; ?>>High</option>
                            <option value="Middle" <?php echo $vehicle['Capacity'] == 'Middle' ? 'selected' : ''; ?>>Middle</option>
                            <option value="Low" <?php echo $vehicle['Capacity'] == 'Low' ? 'selected' : ''; ?>>Low</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="ProductionDate">Production Date</label>
                        <input type="date" name="ProductionDate" class="form-control" 
                               value="<?php echo htmlspecialchars($vehicle['ProductionDate']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="EngineNo">Engine Number</label>
                        <input type="text" name="EngineNo" class="form-control" 
                               value="<?php echo htmlspecialchars($vehicle['EngineNo']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="EnginePower">Engine Power</label>
                        <input type="text" name="EnginePower" class="form-control" 
                               value="<?php echo htmlspecialchars($vehicle['EnginePower']); ?>" required>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="Owner">Owner</label>
                        <select name="Owner" class="form-control" required>
                            <option value="main" <?php echo $vehicle['Owner'] == 'main' ? 'selected' : ''; ?>>Main</option>
                            <option value="chamo" <?php echo $vehicle['Owner'] == 'chamo' ? 'selected' : ''; ?>>Chamo</option>
                            <option value="kulfo" <?php echo $vehicle['Owner'] == 'kulfo' ? 'selected' : ''; ?>>Kulfo</option>
                            <option value="abayya" <?php echo $vehicle['Owner'] == 'abayya' ? 'selected' : ''; ?>>Abbaya</option>
                            <option value="nechsar" <?php echo $vehicle['Owner'] == 'nechsar' ? 'selected' : ''; ?>>Nechsar</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Vehicle
                        </button>
                        <a href="view1.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
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