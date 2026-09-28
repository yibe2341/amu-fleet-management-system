<html>
<body>
<?php

$con = mysql_connect("localhost","root","");
if($_SERVER["REQUEST_METHOD"] == "POST")
{
$first_name = ($_POST['first_name']);
$last_name = ($_POST['last_name']);
$email = ($_POST['email']);
$telephone = ($_POST['telephone']);
$comments = ($_POST['comments']);

if (!$con)
  {

  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("fleet", $con);
$query="SELECT * FROM comment ";
 $ok=mysql_query($query);
$count=mysql_num_rows($ok);
 if($count!=0)
 {
 echo '<script type="text/javascript">alert("Sorry! This user already contacting us.");window.location=\'contactus.html\';</script>';
}
else
{
$sql="INSERT INTO comment  VALUES('$_POST[first_name]','$_POST[last_name]','$_POST[email]','$_POST[telephone]','$_POST[comments]')";
if (!mysql_query($sql,$con))

  {

  die('Error:'. mysql_error());

  }
}
echo '<script type="text/javascript">alert("Thank you for contacting us.");window.location=\'contactus.html\';</script>';
}
mysql_close($con)

?>

</body>

</html>
