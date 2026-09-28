<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo 'false'; // User is not logged in
} else {
    echo 'true'; // User is logged in
}
?>