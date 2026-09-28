<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AMU</title>
<meta name="keywords" content="free css website layout, insect, blog template, XHTML, CSS" />
<meta name="description" content="Insect Blog Template - Free CSS website layout, W3C compliant XHTML CSS" />
<link href="templatemo_style.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div id="templatemo_top_panel">
<img src="wou arm.jpg.png" alt="AMU Logo" style="width: 180px; height: 120px; float: right;" />
        <img src="wou arm.jpg.png" alt="AMU Logo" style="width: 180px; height: 120px; float: left;" />
    
    <div id="templatemo_top_section">
        <div id="site_title">
       AMU FLEET MANAGEMENT SYSTEM
        <div id="templatemo_menu">
                                 <ul>
                <li><a href="police.html"  class="current">Home</a></li>
				<li><a href="exit2.php">View exit</a></li>
				 <li><a href="changepsspolice.html">Change Password</a></li> 
                <li><a href="index.html"  class="current">Logout</a></li>
                                 
                                   

                </ul>
				</div> 
		</div> <!-- end of menu -->
    </div> <!-- end of top section -->
</div> <!-- end of top panel -->    

<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
include('config.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if Plate_no is set in the URL
if (isset($_GET['Plate_no'])) {
    // Sanitize the input
    $Plate_no = $conn->real_escape_string($_GET['Plate_no']);

    // Prepare the SQL query with a prepared statement
    $sql = "DELETE FROM permission WHERE Plate_no = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the Plate_no parameter to the query
        $stmt->bind_param("s", $Plate_no);

        // Execute the query
        if ($stmt->execute()) {
            // Success message
            echo "<script>
                    alert('Permission successfully deleted!!');
                    window.location.href = 'exit2.php';
                  </script>";

        // Close the statement
        $stmt->close();
    } else {
        echo "<h1 align='center'><b><i>Error preparing statement: " . $conn->error . "</i></b></h1>";
    }}
 else {
    echo "<h1 align='center'><b><i>Plate_no not provided!</i></b></h1>";
}}

// Close the database connection
$conn->close();
?>
    
</body>
</html>