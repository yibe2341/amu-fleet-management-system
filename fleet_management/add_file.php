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
          AMU UNIVERSITY FLEET MANAGEMENT SYSTEM
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
                 <li><a href="#">NotifyExitPermission</a></li>
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
                <div id="login_section_bottom"></div>
            </div>
            
           	<div id="left_column_section">
            	<div id="left_column_section_top"></div>
                <div id="left_column_section_title">Popular Posts</div>
                <div id="left_column_section_middle">
                  <div class="popular_post">
                	<h1>&raquo; Aliquam pretium porta odio</h1>
                        <p>Duis vitae velit. Ut ultricies. Fusce sollici tudin nisl a lectus.</p>
                    </div>
                  <div class="popular_post">
                <h1>&raquo; Donec accumsan urna non</h1>
                        <p>Pellen tesque odio. Pellen tesque habitant morbi tristique.</p>
                    </div>
                    
                  <div class="popular_post">
                <h1>&raquo; Mauris et elit quis mauris</h1>
                        <p>Aliq uam elit risus, volutpat quis, ma ttis ac, elementum eget.</p>
                    </div>
                    
                  <div class="popular_post">
                <h1>&raquo; Cras pretium sem sed odio</h1>
                        <p>Qui sque rhon cus nulla quis sem. Mau ris quis nulla sed ipsum.</p>
                    </div>
                </div>
            </div> 
            
        </div> <!-- end of content left -->
        
        <div id="templatemo_content_right">
        
        	<div class="right_column_section">
            	
                <div class="right_column_section_title">
	                vehicle Registration Form
                </div>
                <div class="right_column_section_body">            	
                    <div class="image_box">
                        
                    </div>
                    <div class="post_body">
            <?php
// Check if a file has been uploaded
if(isset($_FILES['uploaded_file'])) {
    // Make sure the file was sent without errors
    if($_FILES['uploaded_file']['error'] == 0) {
        // Connect to the database
        $fleet = new mysqli('localhost', 'root', '', 'fleet');
        if(mysqli_connect_errno()) {
            die("MySQL connection failed: ". mysqli_connect_error());
        }
 
        // Gather all required data
        $name = $fleet->real_escape_string($_FILES['uploaded_file']['name']);
        $mime = $fleet->real_escape_string($_FILES['uploaded_file']['type']);
        $data = $fleet->real_escape_string(file_get_contents($_FILES  ['uploaded_file']['tmp_name']));
        $size = intval($_FILES['uploaded_file']['size']);
 
        // Create the SQL query
        $query = "
            INSERT INTO `file` (
                `name`, `mime`, `size`, `data`, `created`
            )
            VALUES (
                '{$name}', '{$mime}', {$size}, '{$data}', NOW()
            )";
 
        // Execute the query
        $result = $fleet->query($query);
 
        // Check if it was successfull
        if($result) {
            echo 'Success! Your file was successfully added!';
        }
        else {
            echo 'Error! Failed to insert the file'
               . "<pre>{$fleet->error}</pre>";
        }
    }
    else {
        echo 'An error accured while the file was being uploaded. '
           . 'Error code: '. intval($_FILES['uploaded_file']['error']);
    }
 
    // Close the mysql connection
    $fleet->close();
}
else {
    echo 'Error! A file was not sent!';
}
 
// Echo a link back to the main page
echo '<p>Click <a href="list_files.php">here</a> to go back</p>';
?>
            
       
                        </div>
                    </div>
	                <div class="cleaner">&nbsp;</div>
				</div>           	
			</div>
          
	             

        
      
     
     
    	
    </div> <!-- end of footer section -->
</div> <!-- end of footer panel -->
<div align=center> </div></body>
<!--  GC scit 2007 --> 
</html>