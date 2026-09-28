<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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

<div id="templatemo_content_panel">
	<div id="templatemo_content_section">   
                 
    	<div id="templatemo_content_left">
        
        	<div id="login_section">
            	<div id="login_section_top"></div>
                <div id="login_section_title">Vehicle Scheduler </div>
                <div id="login_section_middle">
                	
                            <div class="form_row">
                            	
                           
                            </div>
                            <div class="form_row">
                            <a href="#"></a><br />	
                                
                            </div>
                            
                    </form>
                   
                    </div>
                <div id="login_section_bottom"></div>
            </div>
            
            
        </div> <!-- end of content left -->
        
        <div id="templatemo_content_right">
        
        	<div class="right_column_section">
            	
                <div class="right_column_section_title">
	                
                </div>
                <div class="right_column_section_body">            	
                    <div class="image_box">
                        
                    </div>
                    <div class="post_body">
                


<html>
<body>
<?php

$con = mysql_connect("localhost","root","");
if($_SERVER["REQUEST_METHOD"] == "POST")
{

$Plate_no = ($_POST['Plate_no']);
$Start_time = ($_POST['Start_time']);
$Return_time = ($_POST['Return_time']);
$Permission = ($_POST['Permission']);
$Date =date('d-m-Y');

if (!$con)
  {

  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("fleet", $con);
$query="SELECT * FROM permission WHERE Return_time='$Return_time' and Start_time='$Start_time'";
 $ok=mysql_query($query);
$count=mysql_num_rows($ok);
 if($count!=0)
 {
 echo"Sorry! This permission already sent";
}
else
{
$sql="insert into permission values('','$Plate_no','$Start_time','$Return_time','$Permission','$Date')";
if (!mysql_query($sql,$con))

  {

  die('Error:'. mysql_error());

  }

echo "permission successfully sent";
}
}

mysql_close($con)

?>

</body>

</html>
</body>

</html>