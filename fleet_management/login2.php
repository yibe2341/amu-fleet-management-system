<?php
session_start();
// Include the database connection file
include("config.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $UserName = mysqli_real_escape_string($conn, $_POST['UserName']);
    $Password = mysqli_real_escape_string($conn, $_POST['Password']);
    $usertype = mysqli_real_escape_string($conn, $_POST['usertype']);

    // Validate inputs
    if (empty($UserName) || empty($Password) || empty($usertype)) {
        $_SESSION['error'] = "Please fill all fields!";
        header("Location: index.php");
        exit();
    }

    // Query the database
    $query = "SELECT UserName, Password, Role FROM User_registration WHERE UserName = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $UserName);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_username, $db_password, $db_role);
            $stmt->fetch();

            // Verify password (assuming passwords are hashed in the database)
            if (password_verify($Password, $db_password) && $usertype === $db_role) {
                // Generate a unique token for this session
                $token = bin2hex(random_bytes(16)); // Random 32-character token
                $_SESSION['username'] = $db_username;
                $_SESSION['role'] = $db_role;
                $_SESSION['token'] = $token; // Store the token in the session

                // Redirect based on user role
                switch ($db_role) {
                    case "Admin":
                        header("Location: admin.php");
                        break;
                    case "Manager":
                        header("Location: manager.php");
                        break;
                    case "Driver":
                        header("Location: driver.php");
                        break;
                    case "User":
                        header("Location: user.php");
                        break;
                    case "Scheduler":
                        header("Location: scheduler.php");
                        break;
                    case "Mechanic":
                        header("Location: mechanic.php");
                        break;
                    case "Police":
                        header("Location: police.php");
                        break;
                    default:
                        $_SESSION['error'] = "Invalid user role!";
                        header("Location: index.html");
                        break;
                }
                exit(); // Ensure no further code is executed after redirect
            } else {
                $_SESSION['error'] = "Invalid username, password, or user role!";
                header("Location: index.html");
            }
        } else {
            $_SESSION['error'] = "User not found!";
            header("Location: index.html");
        }

        $stmt->close();
    } else {
        die("Database query error: " . $conn->error);
    }
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: index.html");
}

// Close the database connection
$conn->close();
?>