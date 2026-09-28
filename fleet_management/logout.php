<?php
// Start the session
session_start();

// Clear the session token from localStorage (via JavaScript)
echo '<script>localStorage.removeItem("sessionToken");</script>';

// Destroy the session
session_unset();
session_destroy();

// Redirect to the login page
header("Location: index.html");
exit();
?>