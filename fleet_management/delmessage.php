<?php
//if(isset($_POST['userid'])){
//$userid=$_POST['userid'];
$a=mysql_connect("localhost","root","");
if(!$a){die('not connet'.mysql_error());}
mysql_select_db("fleet",$a);
if(isset($_GET['M_id'])){
$M_id=$_GET['M_id'];
$query="DELETE FROM message WHERE M_id='$M_id'";
mysql_query($query,$a);
if(!$query)
{
echo '<script type="text/javascript">alert("not deleted");window.location=\'mmessage.php\';</script>'.mysql_error();
//header 'window:location.php';
}
else
echo '<script type="text/javascript">alert("Are you sure to delete?!");window.location=\'mmessage.php\';</script>';
//include("search.php");
}
?>