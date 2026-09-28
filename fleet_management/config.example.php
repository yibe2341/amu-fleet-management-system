<?php
$servername = "localhost";
$username = "YOUR_DATABASE_USERNAME";
$password = "YOUR_DATABASE_PASSWORD";
$dbname = "fleet";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname, 3306);
    $conn->set_charset("utf8mb4");
    $conn->select_db($dbname);
} catch (mysqli_sql_exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
