
	   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AMU</title>
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
<?php
$ATA = mysql_connect("localhost","root","");
if (!$ATA)
{
die('Could not connect: ' . mysql_error());
}
mysql_select_db("fleet", $ATA);
			  if (isset($_GET['m_id']))
	{
	include('config.php');
$m_id=$_GET['m_id'];
$sql="DELETE FROM message WHERE m_id='$m_id'";
if (!mysql_query($sql,$ATA))
{
die('Error: ' . mysql_error());
}
else
echo "<h1 align = center><b><i>"."1 message successfully deleted!!"."</i></b></h1>";
echo"<a href='mmessage.php'><h1 align=center>Back</h2></a>";
mysql_close($ATA);
}
?>