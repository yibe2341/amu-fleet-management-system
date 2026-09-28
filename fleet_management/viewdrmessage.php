<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insect Blog Template - Free CSS Website Layout</title>
<meta name="keywords" content="free css website layout, insect, blog template, XHTML, CSS" />
<meta name="description" content="Insect Blog Template - Free CSS website layout, W3C compliant XHTML CSS" />
<link href="templatemo_style121.css" rel="stylesheet" type="text/css" />
 
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
                 <li><a href="driver.php"  class="current">Home</a></li>
				         <li><a href="requestmaintenance.html">Requestmaintenance</a></li>			  
				         <li><a href="#">View</a>
						 <ul>
						 <li><a href="dviewschedule.php">view schedule</a></li>
						  <li><a href="viewmessage.php">view message</a></li>
						  <li><a href="exit11.php">view permission</a></li>
						  </ul>
						  </li>
                 <li><a href="exitrequest.html">Request exit</a></li>
				 <li><a href="changepssdriver.html">Changepassword</a></li>
                 <li><a href="index.html"  class="current">Logout</a></li>
                 </ul> 	
        </div>
		</div> <!-- end of menu -->
    </div> <!-- end of top section -->
</div> <!-- end of top panel -->    

		
          <thead>

<center>



<form>
<div class="id" style="background-color:#FFFFCC; color:black;border-radius:25px; width:70%">


 <?php
include("config.php");
?>
<html><head>
<form name="search" method="post" action="searchmsg2.php">
<br><br>
 <table border="1" align="center" width="90%">
<h1><p align="center">View message </p> </h1>
<tr>
<th style='height:50px;	color:#000;	font-weight:bold;background-color:#FFFFCC;'><font color='black' size='2'>M_id</th>
<th style='height:50px;	color:#000;	font-weight:bold;background-color:#FFFFCC;'><font color='black' size='2'>From</th>
<th style='height:50px;	color:#000;	font-weight:bold;background-color:#FFFFCC;'><font color='black' size='2'>Too</th>
<th style='height:50px;	color:#000;	font-weight:bold;background-color:#FFFFCC;'><font color='black' size='2'>Pnumber</th>
<th style='height:50px;	color:#000;	font-weight:bold;background-color:#FFFFCC;'><font color='black' size='2'>Message</th>
<th style='height:50px;	color:#000;	font-weight:bold;background-color:#FFFFCC;'><font color='black' size='2'>Date</th>
<th style='height:50px;	color:#000;	font-weight:bold;background-color:#FFFFCC;'><font color='black' size='2'>Action</th>
</tr>

<?php
// Connect to the database
include('config.php');

// Check if the search form is submitted
if (isset($_POST["search"])) {
    // Sanitize the search input
    $search = htmlspecialchars($_POST["search"]);
    $flag = 0;

    // Prepare the SQL query with a prepared statement
    $query = "SELECT * FROM message WHERE too LIKE ? AND too = 'Driver'";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind the search parameter to the query
        $search_param = "%" . $search . "%";
        $stmt->bind_param("s", $search_param);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Display the results in a table
        while ($row3 = $result->fetch_assoc()) {
            $flag = 1;
            echo "<tr>";
            echo '<td>' . htmlspecialchars($row3['m_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row3['frm']) . '</td>';
            echo '<td>' . htmlspecialchars($row3['too']) . '</td>';
            echo '<td>' . htmlspecialchars($row3['pnumber']) . '</td>';
            echo '<td>' . htmlspecialchars($row3['message']) . '</td>';
            echo '<td>' . htmlspecialchars($row3['Date']) . '</td>';
            echo '<td>';
            ?>
            <a rel="facebook" href="tt.php?m_id=<?php echo $row3['m_id']; ?>&view=delete" onClick="return confirm('Are you sure??')">
                <img src="delete.png" alt="Delete">
            </a>
            <?php
            echo "</td></tr>";
        }

        // Close the statement
        $stmt->close();

        // If no results are found
        if ($flag == 0) {
            echo "<tr><td colspan='7'>No results found.</td></tr>";
        }
    } else {
        // Error in preparing the statement
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>        
</table>
</div>
</form>

</center> 

</body>
</html> 
 
 