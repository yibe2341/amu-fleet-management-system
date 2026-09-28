<?php
session_start();
include("config.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $Vehicle_id = mysqli_real_escape_string($conn, $_POST['Vehicle_id']);
    $PlateNo = mysqli_real_escape_string($conn, $_POST['PlateNo']);
    $VehicleType = mysqli_real_escape_string($conn, $_POST['VehicleType']);
    $Model = mysqli_real_escape_string($conn, $_POST['Model']);
    $ChessisNo = mysqli_real_escape_string($conn, $_POST['ChessisNo']);
    $Capacity = mysqli_real_escape_string($conn, $_POST['Capacity']);
    $ProductionDate = mysqli_real_escape_string($conn, $_POST['ProductionDate']);
    $EngineNo = mysqli_real_escape_string($conn, $_POST['EngineNo']);
    $EnginePower = mysqli_real_escape_string($conn, $_POST['EnginePower']);
    $Owner = mysqli_real_escape_string($conn, $_POST['Owner']);

    // Prepare the SQL query to update the vehicle details
    $query = "UPDATE vehicles SET 
              Vehicle_id = ?, 
              VehicleType = ?, 
              Model = ?, 
              ChessisNo = ?, 
              Capacity = ?, 
              ProductionDate = ?, 
              EngineNo = ?, 
              EnginePower = ?, 
              Owner = ? 
              WHERE PlateNo = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind parameters to the query
        $stmt->bind_param("ssssssssss", $Vehicle_id, $VehicleType, $Model, $ChessisNo, $Capacity, $ProductionDate, $EngineNo, $EnginePower, $Owner, $PlateNo);

        // Execute the query
        if ($stmt->execute()) {
            // Success: Redirect to the vehicle list page
            header("Location: view1.php");
            exit();
        } else {
            // Error: Display an error message
            die("Error updating record: " . $stmt->error);
        }

        // Close the statement
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }

    // Close the database connection
    $conn->close();
} else {
    // If the form was not submitted, redirect back to the form
    header("Location: editvehicle.php?PlateNo=" . urlencode($_POST['PlateNo']));
    exit();
}
?>