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
                 <li><a href="mnager.html">Home</a></li> 
				  <li><a href="#">Vehicle</a>
				  <ul>
                 <li><a href="vehicle-register.html">Register vehicle </a></li>
				 <li><a href="view1.php">Update vehicle</a></li>
				 <li><a href="searchvinfo.html">search vehicles</a></li>
				 </ul>
				 </li>
				 
                  
                 <li><a href="#">View</a>
                  <ul>
                 <li><a href="mviewschedule.php">view schedule</a></li>
                 <li><a href="exitrequest1.php">View exit request</a></li>
                 <li><a href="mrequest-view.php">View maintenance request</a></li>
				 <li><a href="mmessage.php">View message</a></li>
				 <li><a href="comment12.php">View comment</a></li>
				 </ul>
				 </li>
				 <li><a href="fuel.php">Fuel</a></li>
                 <li><a href="upload.html"  class="current">Report</a></li>
				 <li><a href="index.html"  class="current">Logout</a></li>
                 </ul>  
				</div> 
		</div> <!-- end of menu -->
    </div> <!-- end of top section -->
</div> <!-- end of top panel -->    


<<?php
session_start();
include("config.php");

// Check if PlateNo is provided in the URL
if (!isset($_GET['PlateNo'])) {
    die("No vehicle selected for deletion.");
}

$PlateNo = $_GET['PlateNo']; // Get PlateNo from URL

// Prepare the SQL query to delete the vehicle
$query = "DELETE FROM vehicles WHERE PlateNo = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    // Bind the PlateNo parameter to the query
    $stmt->bind_param("s", $PlateNo);

    // Execute the query
    if ($stmt->execute()) {
        // Success: Redirect to the vehicle list page
        header("Location: view1.php");
        exit();
    } else {
        // Error: Display an error message
        die("Error deleting record: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

// Close the database connection
$conn->close();
?>