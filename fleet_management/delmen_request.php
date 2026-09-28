<html>
<body>
<?php
//if(isset($_POST['Driver_id'])){
//$Driver_id=$_POST['Driver_id'];
$a=mysql_connect("localhost","root","");
if(!$a){die('not connet'.mysql_error());}
mysql_select_db("fleet",$a);
if(isset($_GET['Driver_id'])){
$Driver_id=$_GET['Driver_id'];
$query="DELETE FROM request WHERE Driver_id='$Driver_id'";
mysql_query($query,$a);
if(!$query)
{
echo '<script type="text/javascript">alert("not deleted");window.location=\'mrequest-view.php\';</script>'.mysql_error();

}
else
echo '<script type="text/javascript">alert("Are you sure to delete?!");window.location=\'mrequest-view.php\';</script>';

}
?>