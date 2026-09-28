<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "fleet", "11111111", "fleet");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $Driver_id = $conn->real_escape_string(trim($_POST['Driver_id']));
    $Driver_Name = $conn->real_escape_string(trim($_POST['Driver_Name']));
    $Mechanic_Name = $conn->real_escape_string(trim($_POST['Mechanic_Name']));
    $PlateNo = $conn->real_escape_string(trim($_POST['PlateNo']));
    $Vehicle_Type = $conn->real_escape_string(trim($_POST['Vehicle_Type']));
    $Date = date('Y-m-d'); // Standard MySQL date format
    $Problem_of_vehicle = $conn->real_escape_string(trim($_POST['Problem_of_vehicle']));
    $current_user = $_SESSION['username'];

    // First check if the table has the new columns
    $check_columns = $conn->query("SHOW COLUMNS FROM request LIKE 'driver_username'");
    if ($check_columns->num_rows == 0) {
        // If columns don't exist, use the old duplicate check temporarily
        $query = "SELECT * FROM request 
                  WHERE PlateNo = ? 
                  AND Problem_of_vehicle LIKE CONCAT('%', ?, '%')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $PlateNo, $Problem_of_vehicle);
    } else {
        // Use the new duplicate check with status
        $query = "SELECT * FROM request 
                  WHERE PlateNo = ? 
                  AND Problem_of_vehicle LIKE CONCAT('%', ?, '%')
                  AND driver_username = ?
                  AND status NOT IN ('Completed', 'Rejected')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $PlateNo, $Problem_of_vehicle, $current_user);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['status_message'] = "You already have a similar active request for this vehicle.";
        $_SESSION['status_type'] = "error";
    } else {
        // Check if we should use new or old insert query
        $check_columns = $conn->query("SHOW COLUMNS FROM request LIKE 'driver_username'");
        if ($check_columns->num_rows == 0) {
            // Old insert without new columns
            $sql = "INSERT INTO request 
                    (Driver_id, Driver_Name, Mechanic_Name, PlateNo, Vehicle_Type, Date, Problem_of_vehicle) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", 
                $Driver_id, $Driver_Name, $Mechanic_Name, 
                $PlateNo, $Vehicle_Type, $Date, $Problem_of_vehicle
            );
        } else {
            // New insert with all columns
            $sql = "INSERT INTO request 
                    (Driver_id, Driver_Name, Mechanic_Name, PlateNo, Vehicle_Type, Date, Problem_of_vehicle, driver_username, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", 
                $Driver_id, $Driver_Name, $Mechanic_Name, 
                $PlateNo, $Vehicle_Type, $Date, $Problem_of_vehicle, $current_user
            );
        }

        if ($stmt->execute()) {
            $_SESSION['status_message'] = "Your maintenance request has been successfully submitted!";
            $_SESSION['status_type'] = "success";
            $_SESSION['form_submitted'] = true;
        } else {
            $_SESSION['status_message'] = "Error submitting request: " . $stmt->error;
            $_SESSION['status_type'] = "error";
        }
    }
    $stmt->close();
}
$conn->close();

// Redirect back to the form page
header("Location: requestmaintenance1.php");
exit();
?>