<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Include the configuration file
require_once 'config.php';

// Check if request ID is provided
if (!isset($_GET['id'])) {
    header("Location: view_requests.php");
    exit();
}

$request_id = $conn->real_escape_string($_GET['id']);

// Get the request details
$request = [];
try {
    $query = "SELECT * FROM request WHERE id = ? AND driver_username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $request_id, $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: view_requests.php");
        exit();
    }
    
    $request = $result->fetch_assoc();
    $stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: view_requests.php");
    exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- Same head content as requestmaintenance1.php -->
    <!-- ... -->
</head>
<body>
    <!-- Same header as requestmaintenance1.php -->
    <!-- ... -->

    <!-- Main Content Section -->
    <div id="templatemo_content_panel">
        <div id="templatemo_content_section">
            <div id="templatemo_content_left">
                <!-- Same left column as requestmaintenance1.php -->
                <!-- ... -->
            </div>
            
            <div id="templatemo_content_right">
                <div class="right_column_section_title">
                    Maintenance Request Details
                </div>
                
                <div style="background-color: rgba(58, 94, 58, 0.6); border-radius: 10px; padding: 25px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                        <div>
                            <strong>Request ID:</strong> <?php echo htmlspecialchars($request['id']); ?>
                        </div>
                        <div class="request-status status-<?php echo strtolower($request['status']); ?>">
                            Status: <?php echo htmlspecialchars($request['status']); ?>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <strong>Driver ID:</strong><br>
                            <?php echo htmlspecialchars($request['Driver_id']); ?>
                        </div>
                        <div>
                            <strong>Driver Name:</strong><br>
                            <?php echo htmlspecialchars($request['Driver_Name']); ?>
                        </div>
                        <div>
                            <strong>Mechanic Name:</strong><br>
                            <?php echo htmlspecialchars($request['Mechanic_Name']); ?>
                        </div>
                        <div>
                            <strong>Vehicle Type:</strong><br>
                            <?php echo htmlspecialchars($request['Vehicle_Type']); ?>
                        </div>
                        <div>
                            <strong>Plate Number:</strong><br>
                            <?php echo htmlspecialchars($request['PlateNo']); ?>
                        </div>
                        <div>
                            <strong>Date Submitted:</strong><br>
                            <?php echo date('M j, Y g:i A', strtotime($request['request_date'])); ?>
                        </div>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <strong>Problem Description:</strong><br>
                        <div style="background-color: rgba(0, 0, 0, 0.3); padding: 15px; border-radius: 5px; margin-top: 10px;">
                            <?php echo nl2br(htmlspecialchars($request['Problem_of_vehicle'])); ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($request['admin_notes'])): ?>
                    <div style="margin-top: 20px;">
                        <strong>Admin Notes:</strong><br>
                        <div style="background-color: rgba(0, 0, 0, 0.3); padding: 15px; border-radius: 5px; margin-top: 10px;">
                            <?php echo nl2br(htmlspecialchars($request['admin_notes'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div style="margin-top: 30px; text-align: center;">
                        <a href="view_requests.php" class="btn btn-submit">
                            <i class="fas fa-arrow-left"></i> Back to Requests
                        </a>
                        
                        <?php if ($request['status'] == 'Pending'): ?>
                            <a href="requestmaintenance1.php?edit=<?php echo $request['id']; ?>" class="btn btn-submit">
                                <i class="fas fa-edit"></i> Edit Request
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Same footer as requestmaintenance1.php -->
    <!-- ... -->
</body>
</html>
<?php
$conn->close();
?>