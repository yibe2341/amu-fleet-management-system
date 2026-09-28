<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
      <meta http-equiv="Pragma" content="no-cache">
      <meta http-equiv="Expires" content="0">
  
      <!-- Add client-side session check -->
      <script>
          // Check session status on page load
          fetch('check_login.php') // Create this file (see below)
              .then(response => response.text())
              .then(data => {
                  if (data === 'false') {
                      window.location.href = 'index.html';
                  }
              });
      </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insect Blog Template - Free CSS Website Layout</title>
<meta name="keywords" content="free css website layout, insect, blog template, XHTML, CSS" />
<meta name="description" content="Insect Blog Template - Free CSS website layout, W3C compliant XHTML CSS" />
<link href="templatemo_style12.css" rel="stylesheet" type="text/css" />
 
</head>
<body>
<div id="templatemo_top_panel">
<img src="wou arm.jpg.png" alt="AMU Logo" style="width: 180px; height: 120px; float: right;" />
        <img src="wou arm.jpg.png" alt="AMU Logo" style="width: 180px; height: 120px; float: left;" />
    
    <div id="templatemo_top_section">
        <div id="site_title">
       AMU FLEET MANAGEMENT SYSTEM
        <div id="templatemo_menu">          
           
     <div id="templatemo_menu">          
                 <ul>
                <li><a href="scheduler.php"  class="current">Home</a></li>
				<li><a href="newsche.php">schedule</a></li>
				<li><a href="#">View</a>
				<ul>
                <li><a href="sviewschedule.php">View schedule</a></li>
				<li><a href="smessage.php">View message</a></li>
				</ul>
				</li>
                <li><a href="searchvinfo1.html">Search vehicle</a></li>
				<li><a href="changepss.php">Change Password</a></li>
				<li><a href="logout.php">Logout</a></li>         
             </ul> 	
        </div>
		</div> <!-- end of menu -->
    </div> <!-- end of top section -->
</div> <!-- end of top panel -->  
<?php
// Include the database configuration file
include("config.php");

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if m_id is set in the URL
if (isset($_GET['m_id'])) {
    // Sanitize the input
    $m_id = mysqli_real_escape_string($conn, $_GET['m_id']);

    // Prepare and execute the delete query
    $query = "DELETE FROM message WHERE m_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error in preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $m_id); // Bind the parameter as an integer
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "<h1 align='center'><b><i>Message successfully deleted!!</i></b></h1>";
    } else {
        echo "<h1 align='center'><b><i>No record found with the provided m_id.</i></b></h1>";
    }

    // Close the statement
    $stmt->close();

    // Provide a link to go back
    echo "<a href='viewsmessage.php'><h1 align='center'>Back</h1></a>";
}

// Close the database connection
$conn->close();
?>