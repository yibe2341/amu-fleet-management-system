<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insect Blog Template - Free CSS Website Layout</title>
<meta name="keywords" content="free css website layout, insect, blog template, XHTML, CSS" />
<meta name="description" content="Insect Blog Template - Free CSS website layout, W3C compliant XHTML CSS" />
<link href="templatemo_style.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div id="templatemo_top_panel">
<img src="wou arm.jpg.png" style="width:"100px" height="95px" align="left"">
    
    <div id="templatemo_top_section">
        <div id="site_title">
        AMU FLEET MANAGEMENT SYSTEM
        <div id="templatemo_menu">          
                 <ul>              
                 <li><a href="mnager.html">Home</a></li> 
                 <li><a href="vehicle-register.html">Vehicle Register</a></li>
                 <li><a href="fuel.html">Fuel</a></li> 
                 <li><a href="viewvehicles.php"  class="current">view vehicles</a>
                  <ul>
                 <li><a href="mviewschedule.php">view schedule</a></li>
                 <li><a href="#">View exit request</a></li>
                 <li><a href="mrequest-view.php">View maintenance request</a></li>
                  </ul>
                 </li> 
                 <li><a href="#">Notify exit permission</a></li>
                 <li><a href="upload.html"  class="current">Report</a></li>
                 </ul> 
        </div>
		</div> <!-- end of menu -->
    </div> <!-- end of top section -->
</div> <!-- end of top panel -->    

                  <?php
// Connect to the database
$dbLink = new mysqli('localhost', 'root', '', 'fleet');
if(mysqli_connect_errno()) {
    die("MySQL connection failed: ". mysqli_connect_error());
}
 
// Query for a list of all existing files
$sql = 'SELECT `id`, `name`, `mime`, `size`, `created` FROM `file`';
$result = $dbLink->query($sql);
 
// Check if it was successfull
if($result) {
    // Make sure there are some files in there
    if($result->num_rows == 0) {
        echo '<p>There are no files in the database</p>';
    }
    else {
        // Print the top of a table
        echo '<table width="100%">
                <tr>
                    <td><b>Name</b></td>
                    <td><b>Mime</b></td>
                    <td><b>Size (bytes)</b></td>
                    <td><b>Created</b></td>
                    <td><b>&nbsp;</b></td>
                </tr>';
 
        // Print each file
        while($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>{$row['name']}</td>
                    <td>{$row['mime']}</td>
                    <td>{$row['size']}</td>
                    <td>{$row['created']}</td>
                    <td><a href='get_file.php?id={$row['id']}'>Download</a></td>
                </tr>";
        }
 
        // Close table
        echo '</table>';
    }
 
    // Free the result
    $result->free();
}
else
{
    echo 'Error! SQL query failed:';
    echo "<pre>{$dbLink->error}</pre>";
}
 
// Close the mysql connection
$dbLink->close();
?>
 
            
  </body>

</html>