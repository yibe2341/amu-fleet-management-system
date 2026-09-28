<?php
session_start();

// Include the database configuration file
include('config.php');

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and validate input data
$Driver_ID = mysqli_real_escape_string($conn, $_POST['Driver_ID']);
$Driver_Name = mysqli_real_escape_string($conn, $_POST['Driver_Name']);
$Driver_phone_no = mysqli_real_escape_string($conn, $_POST['Driver_phone_no']);
$Vehicle_type = mysqli_real_escape_string($conn, $_POST['Vehicle_type']);
$Plate_no = mysqli_real_escape_string($conn, $_POST['Plate_no']);
$Place_of_start = mysqli_real_escape_string($conn, $_POST['Place_of_start']);
$Place_of_arrive = mysqli_real_escape_string($conn, $_POST['Place_of_arrive']);
$Service_time = mysqli_real_escape_string($conn, $_POST['Service_time']);
$Date = mysqli_real_escape_string($conn, $_POST['Date']);
$Enterance_time = mysqli_real_escape_string($conn, $_POST['Enterance_time']);
$Outgoing_time = mysqli_real_escape_string($conn, $_POST['Outgoing_time']);

// Prepare and execute the update query
$query = "UPDATE schedule SET 
          Driver_ID = ?, 
          Driver_Name = ?, 
          Driver_phone_no = ?, 
          Vehicle_type = ?, 
          Plate_no = ?, 
          Place_of_start = ?, 
          Place_of_arrive = ?, 
          Service_time = ?, 
          Date = ?, 
          Enterance_time = ?, 
          Outgoing_time = ? 
          WHERE Driver_ID = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error in preparing statement: " . $conn->error);
}
$stmt->bind_param("ssssssssssss", $Driver_ID, $Driver_Name, $Driver_phone_no, $Vehicle_type, $Plate_no, $Place_of_start, $Place_of_arrive, $Service_time, $Date, $Enterance_time, $Outgoing_time, $Driver_ID);

if ($stmt->execute()) {
    echo '<script type="text/javascript">
          alert("Record updated successfully!!");
          window.location = "sviewschedule.php";
          </script>';
} else {
    echo "Error updating record: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>