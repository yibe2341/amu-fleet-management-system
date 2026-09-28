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
<img src="wou arm.jpg.png" alt="AMU Logo" style="width: 180px; height: 120px; float: right;" />
        <img src="wou arm.jpg.png" alt="AMU Logo" style="width: 180px; height: 120px; float: left;" />
    
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

<div id="templatemo_content_panel">
	<div id="templatemo_content_section">   
                 
    	<div id="templatemo_content_left">
        
        	<div id="login_section">
            	<div id="login_section_top"></div>
                <div id="login_section_title">Vehicle Manager </div>
                <div id="login_section_middle">
                	
                            <div class="form_row">
                            	
                           
                            </div>
                            <div class="form_row">
                            <a href="#"></a><br />	
                                
                            </div>
                            
                    </form>
                    <a href="#">LOGOUT</a><br />
                    </div>
            
                 <?php
// Make sure an ID was passed
if(isset($_GET['id'])) {
// Get the ID
    $id = intval($_GET['id']);
 
    // Make sure the ID is in fact a valid ID
    if($id <= 0) {
        die('The ID is invalid!');
    }
    else {
        // Connect to the database
        $fleet = new mysqli('localhost', 'root', '', 'fleet');
        if(mysqli_connect_errno()) {
            die("MySQL connection failed: ". mysqli_connect_error());
        }
 
        // Fetch the file information
        $query = "
            SELECT `mime`, `name`, `size`, `data`
            FROM `file`
            WHERE `id` = {$id}";
        $result = $fleet->query($query);
 
        if($result) {
            // Make sure the result is valid
            if($result->num_rows == 1) {
            // Get the row
                $row = mysqli_fetch_assoc($result);
 
                // Print headers
                header("Content-Type: ". $row['mime']);
                header("Content-Length: ". $row['size']);
                header("Content-Disposition: attachment; filename=". $row['name']);
 
                // Print data
                echo $row['data'];
            }
            else {
                echo 'Error! No image exists with that ID.';
            }
 
            // Free the mysqli resources
            @mysqli_free_result($result);
        }
        else {
            echo "Error! Query failed: <pre>{$dbLink->error}</pre>";
        }
        @mysqli_close($fleet);
    }
}
else {
    echo 'Error! No ID was passed.';
}
?>
            
            </body>
<!--  GC scit 2007 --> 
</html>