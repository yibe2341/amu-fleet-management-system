<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the configuration file
include("config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $first_name  = $conn->real_escape_string($_POST['first_name']);
    $last_name   = $conn->real_escape_string($_POST['last_name']);
    $email       = $conn->real_escape_string($_POST['email']);
    $telephone   = $conn->real_escape_string($_POST['telephone']);
    $date        = date('d-m-Y');
    $comments    = $conn->real_escape_string($_POST['comments']);

    // Prepare SQL query with placeholders
    $sql = "INSERT INTO contact (first_name, last_name, email, telephone, date, comments) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error in preparing statement: " . $conn->error);
    }

    // Bind the parameters to the placeholders
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $telephone, $date, $comments);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Comments sent successfully."); window.location=\'contactus.html\';</script>';
    } else {
        echo "Comments sending failed: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>