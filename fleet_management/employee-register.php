<?php
// Include the database connection file
include("config.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Print the contents of $_POST
    print_r($_POST);

    // Retrieve and sanitize form data
    $User_id = mysqli_real_escape_string($conn, $_POST['User_id']);
    $First_Name = mysqli_real_escape_string($conn, $_POST['First_Name']);
    $Last_Name = mysqli_real_escape_string($conn, $_POST['Last_Name']);
    $Sex = mysqli_real_escape_string($conn, $_POST['Sex']);
    $Role = mysqli_real_escape_string($conn, $_POST['Role']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $Mobile_No = mysqli_real_escape_string($conn, $_POST['Mobile_No']);
    $UserName = mysqli_real_escape_string($conn, $_POST['UserName']);
    $Password = mysqli_real_escape_string($conn, $_POST['Password']);
    $Confirmpassword = isset($_POST['ConfirmPassword']) ? mysqli_real_escape_string($conn, $_POST['ConfirmPassword']) : '';
    // Validate inputs
    if (empty($User_id) || empty($First_Name) || empty($Last_Name) || empty($Sex) || empty($Role) || empty($Email) || empty($Mobile_No) || empty($UserName) || empty($Password) || empty($Confirmpassword)) {
        die("Please fill all fields.");
    }

    // Check if passwords match
    if ($Password != $Confirmpassword) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashed_password = password_hash($Password, PASSWORD_DEFAULT);

    // Check if User_id already exists
    $query = "SELECT * FROM User_registration WHERE User_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $User_id);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                die("Sorry! This user is already registered.");
            } else {
                // Proceed with registration logic here
                // Insert data into the database
                $sql = "INSERT INTO User_registration (User_id, First_Name, Last_Name, Sex, Role, Email, Mobile_No, UserName, Password) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("sssssssss", $User_id, $First_Name, $Last_Name, $Sex, $Role, $Email, $Mobile_No, $UserName, $hashed_password);
                    if ($stmt->execute()) {
                        echo "User registered successfully!";
                        // Redirect to another page (e.g., admin.html)
                        header("Location: admin.php");
                        exit();
                    } else {
                        die("Error: " . $stmt->error);
                    }
                    $stmt->close();
                } else {
                    die("Error: " . $conn->error);
                }
            }
        } else {
            die("Error executing statement: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }

    // Close the database connection
    $conn->close();
} else {
    die("Invalid request.");
}
?>