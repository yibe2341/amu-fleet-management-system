
<?php
//session_start();
if(!isset($__SESSION['submit']))
{
$M_id=$_POST['M_id'];
$From=$_POST['From'];
$To=$_POST['To'];
$Date=$_POST['Date'];
$Message=$_POST['Message'];
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("fleet", $con);



mysql_query("insert into  message values('$M_id','$From','$To','$Date','$Message')"); 

$mess="Successfully send...";


mysql_close($con);

include('massage.php');
}

?> 




