<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// Include the database configuration file
include("config.php"); // Ensure this doesn't output anything
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMU Fleet Management System - Search Vehicle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --amu-primary: #2e7d32; /* AMU green */
            --amu-primary-dark: #1b5e20;
            --amu-dark: #121212;
            --amu-light: #f5f5f5;
            --amu-text: #e0e0e0;
            --amu-text-secondary: #b0b0b0;
            --amu-danger: #dc3545; /* For btn-reset */
            --amu-new-bg: rgba(26, 35, 126, 0.85); /* Navy Blue */
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
            background-color: var(--amu-new-bg); 
            padding: 0.8rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 15px rgba(0,0,0,0.4); 
            position: relative;
            z-index: 1000;
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
            background-color: rgba(0, 0, 0, 0.9); 
            border-radius: 0 0 4px 4px;
            min-width: 180px; 
            padding: 0.5rem 0;
            display: none;
            z-index: 1001; 
        }

        li:hover > .dropdown {
            display: block;
        }

        .dropdown a {
            padding: 0.8rem 1.2rem;
            text-align: left;
            width: 100%; 
        }
        .dropdown a i {
            margin-right: 0.5rem; 
        }

        /* Main Content */
        main {
            flex: 1; 
            padding: 1.5rem 5%;
            width: 100%;
            display: flex; 
            justify-content: center;
            align-items: flex-start; 
        }

        .container {
            max-width: 1200px;
            width: 100%; 
            margin: 0 auto;
            backdrop-filter: blur(2px); 
        }

        /* Search Section */
        .search-section {
            background-color: var(--amu-new-bg); 
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0,0,0,0.5); 
            border: 1px solid rgba(255,255,255,0.1); 
        }
        .search-section h2 { 
            color: #fff; 
            margin-bottom: 1rem;
            font-size: 1.6rem; 
            position: relative;
            padding-bottom: 0.5rem;
        }
        .search-section h2::after { 
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary); 
        }

        .search-form {
            padding: 1.5rem;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--amu-text);
            font-weight: 500;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            background-color: rgba(0, 0, 0, 0.5); 
            color: var(--amu-text);
            font-size: 1rem;
        }
        input::placeholder { 
            color: var(--amu-text-secondary);
            opacity: 0.7;
        }
         input[type="text"]:focus {
            outline: none;
            border-color: var(--amu-primary);
        }

        .form-actions {
            display: flex;
            justify-content: center; 
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Buttons */
        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex; 
            align-items: center;
            gap: 0.5rem; 
        }

        .btn-primary {
            background-color: var(--amu-primary); 
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--amu-primary-dark);
        }

        .btn-reset { 
            background-color: var(--amu-danger);
            color: white;
        }
        .btn-reset:hover { 
            background-color: #c82333; 
            transform: translateY(-2px);
        }

        /* Results Section */
        .results-section {
            background-color: var(--amu-new-bg); 
            border-radius: 10px;
            padding: 1.5rem;
            overflow-x: auto; 
            box-shadow: 0 5px 25px rgba(0,0,0,0.5); 
            border: 1px solid rgba(255,255,255,0.1); 
            /* display: none; /* MODIFIED: Initially hidden, will be shown by PHP if form submitted */
        }
        .results-section h3 { 
            color: #fff; 
            margin-bottom: 1rem;
            text-align: center;
            font-size: 1.6rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        .results-section h3::after { 
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 2px;
            background-color: var(--amu-primary); 
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        th, td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: var(--amu-primary); 
            color: white; 
            font-weight: 600;
            position: sticky; 
            top: 0;
            z-index: 1; 
        }

        tr:hover td { 
             background-color: rgba(255, 255, 255, 0.08);
        }
        tr:nth-child(even) td { 
            background-color: rgba(255, 255, 255, 0.03);
        }
         tr:nth-child(even):hover td {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Message Styles */
        .message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
            color: var(--amu-text); 
            border: 1px solid; 
        }

        .message.info {
            background-color: rgba(23, 162, 184, 0.2); 
            border-color: rgba(23, 162, 184, 0.5); 
            color: #17a2b8; 
        }

        /* Footer */
        footer {
            background-color: var(--amu-new-bg); 
            padding: 1rem 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.8rem;
            margin-top: auto; 
        }

        footer a {
            color: var(--amu-primary); 
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: var(--amu-primary-dark);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
            }
            
            .site-title {
                margin: 0.5rem 0;
                font-size: 1.2rem;
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                flex-direction: column; 
                align-items: center;
            }
            nav li {
                margin: 0.3rem 0; 
            }
            
            .dropdown {
                position: static; 
                display: none; 
                width: 100%;
                background-color: rgba(0,0,0,0.7); 
            }
            
            li:hover > .dropdown, li:focus-within > .dropdown { 
                display: block;
            }

            .logo {
                height: 40px;
                margin-bottom: 0.5rem; 
            }
            header img.logo:last-of-type { 
                display: none;
            }
            main {
                 padding: 1rem;
            }
            .search-section, .results-section {
                padding: 1rem;
            }
            .search-section h2, .results-section h3 {
                font-size: 1.4rem;
            }

            table { 
                /* display: block; /* Not needed if parent .results-section has overflow-x */
                /* overflow-x: auto; */
            }
            th, td {
                padding: 0.6rem;
                font-size: 0.85rem;
            }
        }
        @media (max-width: 576px) {
             .form-actions {
                flex-direction: column; 
            }
            .btn {
                width: 100%;
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
                    <a href="#"><i class="fas fa-eye"></i> View <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="sviewschedule.php"><i class="fas fa-calendar-alt"></i> View Schedule</a></li>
                        <li><a href="smessage.php"><i class="fas fa-envelope"></i> View Messages</a></li>
                    </ul>
                </li>
                <li><a href="searchvinfo1.php" class="current"><i class="fas fa-search"></i> Search Vehicle</a></li> 
                <li><a href="changepss.php"><i class="fas fa-key"></i> Change Password</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <img src="wou arm.jpg.png" alt="AMU Logo" class="logo">
    </header>

    <main>
        <div class="container">
            <div class="search-section">
                <h2>Search Vehicle Information</h2>
                
                <form method="post" class="search-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> <!-- Action to self -->
                    <div class="form-group">
                        <label for="PlateNo">Enter Plate Number <span style="color:var(--amu-danger)">*</span></label>
                        <input type="text" name="PlateNo" id="PlateNo" maxlength="30" required placeholder="e.g., AA-12345" value="<?php echo isset($_POST['PlateNo']) ? htmlspecialchars($_POST['PlateNo']) : ''; ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <button type="reset" class="btn btn-reset" onclick="document.getElementById('PlateNo').value=''"> 
                            <i class="fas fa-undo"></i> Clear
                        </button>
                    </div>
                </form>
            </div>

            <?php
            // Check if the form is submitted to display the results section
            if (isset($_POST['submit'])) {
                echo '<div class="results-section">'; // Start results section only if form submitted
                if ($conn && $conn instanceof mysqli) { 
                    $id = $conn->real_escape_string(trim($_POST['PlateNo'])); 

                    $query0 = "SELECT Vehicle_id, PlateNo, VehicleType, Model, ChessisNo, Capacity, ProductionDate, EngineNo, EnginePower, Owner FROM vehicles WHERE PlateNo = ?";
                    $stmt = $conn->prepare($query0);
                    if (!$stmt) {
                        echo '<div class="message info" style="color:var(--amu-danger); border-color:var(--amu-danger);">Error preparing statement: ' . htmlspecialchars($conn->error) . '</div>';
                    } else {
                        $stmt->bind_param("s", $id);
                        if (!$stmt->execute()) {
                            echo '<div class="message info" style="color:var(--amu-danger); border-color:var(--amu-danger);">Error executing statement: ' . htmlspecialchars($stmt->error) . '</div>';
                        } else {
                            $result0 = $stmt->get_result();

                            if ($result0->num_rows > 0) {
                                echo "<h3>Vehicle Information for Plate No: " . htmlspecialchars($id) . "</h3>";
                                echo "<table>";
                                echo "<thead>
                                        <tr>
                                            <th>Vehicle ID</th>
                                            <th>Plate No</th>
                                            <th>Vehicle Type</th>
                                            <th>Model</th>
                                            <th>Chassis No</th>
                                            <th>Capacity</th>
                                            <th>Prod. Date</th>
                                            <th>Engine No</th>
                                            <th>Eng. Power</th>
                                            <th>Owner</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

                                while ($row = $result0->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row["Vehicle_id"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["PlateNo"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["VehicleType"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["Model"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["ChessisNo"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["Capacity"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["ProductionDate"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["EngineNo"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["EnginePower"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["Owner"]) . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody></table>";
                            } else {
                                echo '<div class="message info">No vehicle found with the Plate Number: <strong>' . htmlspecialchars($id) . '</strong>. Please check the plate number and try again.</div>';
                            }
                            $stmt->close();
                        }
                    }
                } else {
                     echo '<div class="message info" style="color:var(--amu-danger); border-color:var(--amu-danger);">Database connection error.</div>';
                }
                echo '</div>'; // End results-section
            } 
            ?>
        </div>
    </main>

    <footer>
        <p>© Copyright © <?php echo date('Y'); ?> <a href="#">AMU</a> | <a href="http://www.amu.edu.et" target="_blank">Fleet Management Office</a></p>
    </footer>

    <script>
        // Client-side session check (optional)
        /*
        document.addEventListener('DOMContentLoaded', function() {
            fetch('check_login.php')
                .then(response => response.text())
                .then(data => {
                    if (data.trim().toLowerCase() === 'false') {
                        window.location.href = 'index.html';
                    }
                })
                .catch(error => console.error('Error checking login status:', error));
        });
        */
    </script>
</body>
</html>
<?php
if (isset($conn) && $conn instanceof mysqli) { 
    $conn->close();
}
?>