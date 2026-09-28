<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$current_user = $_SESSION['username'];
$request_id = $conn->real_escape_string($_POST['request_id']);

try {
    // Verify request belongs to current user
    $check_query = "SELECT * FROM request WHERE Driver_id = ? AND Driver_Name = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $request_id, $current_user);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Request not found or no permission']);
        exit();
    }

    // Update request
    $update_query = "UPDATE request SET 
                    Driver_id = ?,
                    Driver_Name = ?,
                    Mechanic_Name = ?,
                    PlateNo = ?,
                    Vehicle_Type = ?,
                    Problem_of_vehicle = ?
                    WHERE Driver_id = ? AND Driver_Name = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssss", 
        $_POST['Driver_id'],
        $_POST['Driver_Name'],
        $_POST['Mechanic_Name'],
        $_POST['PlateNo'],
        $_POST['Vehicle_Type'],
        $_POST['Problem_of_vehicle'],
        $request_id,
        $current_user
    );
    
    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Request updated successfully!";
        $_SESSION['status_type'] = 'success';
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}