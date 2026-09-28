<?php
// Start the session
session_start();

// Prevent browser caching of this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Check authentication
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Include the database connection file
include('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserName = $_SESSION['username'];
    $Password = $_POST['Password'];
    $newPassword = $_POST['newPassword'];

    // Fetch the hashed password from the database
    $query = "SELECT Password FROM User_registration WHERE UserName = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $UserName);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_password);
            $stmt->fetch();

            // Verify the old password
            if (password_verify($Password, $db_password)) {
                // Hash the new password
                $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_query = "UPDATE User_registration SET Password = ? WHERE UserName = ?";
                $update_stmt = $conn->prepare($update_query);

                if ($update_stmt) {
                    $update_stmt->bind_param("ss", $hashed_password, $UserName);
                    if ($update_stmt->execute()) {
                        // Destroy session completely
                        $_SESSION = array(); // Clear session data

                        // Delete session cookie
                        if (ini_get("session.use_cookies")) {
                            $params = session_get_cookie_params();
                            setcookie(
                                session_name(),
                                '',
                                time() - 42000,
                                $params["path"],
                                $params["domain"],
                                $params["secure"],
                                $params["httponly"]
                            );
                        }

                        // Destroy the session
                        session_destroy();

                        // Force redirect using PHP headers
                        header("Location: logout.php");
                        exit(); // Stop script execution immediately
                    } else {
                        die("Error updating password: " . $update_stmt->error);
                    }
                    $update_stmt->close();
                } else {
                    die("Error preparing update statement: " . $conn->error);
                }
            } else {
                die("Invalid old password.");
            }
        } else {
            die("User not found.");
        }
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }
} else {
    die("Invalid request.");
}

// Close the database connection
$conn->close();
?>