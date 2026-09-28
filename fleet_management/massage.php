<html>
<body>
<?php
// Database connection
include("config.php");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST["send"])) {
    // Sanitize user inputs (optional but recommended)
    $from = htmlspecialchars($_POST['frm']);
    $too = htmlspecialchars($_POST['too']);
    $pnumber = htmlspecialchars($_POST['pnumber']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare and bind the SQL statement
    $query = "INSERT INTO message (frm, too, pnumber, message, Date) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind parameters to the statement
        $stmt->bind_param("ssss", $from, $too, $pnumber, $message);

        // Execute the statement
        if ($stmt->execute()) {
            // Success message
            echo '<script type="text/javascript">
                    alert("Message successfully sent!");
                    window.location = "massage2.php";
                  </script>';
        } else {
            // Error message
            echo "Message send failed: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error in preparing the statement
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>	
</body>

</html>