<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require_once 'config.php';

$requests = [];
$error = '';
$success = '';
$current_user = $_SESSION['username'];

// Handle Delete Request
if (isset($_GET['delete'])) {
    $request_id = $conn->real_escape_string($_GET['delete']);
    
    try {
        $check_query = "SELECT * FROM request WHERE Driver_id = ? AND Driver_Name = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ss", $request_id, $current_user);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $delete_query = "DELETE FROM request WHERE Driver_id = ? AND Driver_Name = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("ss", $request_id, $current_user);
            if ($delete_stmt->execute()) {
                $_SESSION['status_message'] = "Request deleted successfully";
                $_SESSION['status_type'] = 'success';
                header("Location: view_requests.php");
                exit();
            }
        }
    } catch (Exception $e) {
        $error = "Database error: Please try again";
    }
}

// Retrieve status messages
$status_message = $_SESSION['status_message'] ?? '';
$status_type = $_SESSION['status_type'] ?? '';
unset($_SESSION['status_message']);
unset($_SESSION['status_type']);

// Get requests
try {
    $query = "SELECT * FROM request WHERE Driver_Name = ? ORDER BY Date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $current_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $requests = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $error = "Error loading requests";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Maintenance Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2e7d32;
            --primary-dark: #1b5e20;
            --danger: #dc3545;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: var(--light-gray);
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: var(--primary);
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        h1 {
            margin: 20px 0;
            color: var(--dark-gray);
        }
        
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid var(--primary);
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger);
        }
        
        .request-list {
            display: grid;
            gap: 20px;
        }
        
        .request-card {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 20px;
            transition: transform 0.2s;
        }
        
        .request-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .request-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .request-id {
            font-weight: 600;
            color: var(--primary);
        }
        
        .request-date {
            color: #6c757d;
            font-size: 0.9em;
        }
        
        .info-row {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .problem-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-edit {
            background-color: var(--primary);
            color: white;
            text-decoration: none;
        }
        
        .btn-delete {
            background-color: var(--danger);
            color: white;
            text-decoration: none;
        }
        
        .btn-new {
            background-color: var(--primary);
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }
        
        .no-requests {
            text-align: center;
            padding: 40px 20px;
            background-color: var(--white);
            border-radius: 8px;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: var(--white);
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            float: right;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h2>AMU Fleet Management</h2>
            <div>
                <span>Welcome, <?php echo htmlspecialchars($current_user); ?></span>
                <a href="logout.php" style="color: white; margin-left: 15px;">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>My Maintenance Requests</h1>
        
        <?php if (!empty($status_message)): ?>
            <div class="alert alert-<?php echo $status_type; ?>">
                <i class="fas <?php echo $status_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <?php echo htmlspecialchars($status_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($requests)): ?>
            <div class="request-list">
                <?php foreach ($requests as $request): ?>
                <div class="request-card">
                    <div class="request-header">
                        <span class="request-id">Request #<?php echo htmlspecialchars($request['Driver_id']); ?></span>
                        <span class="request-date"><?php echo date('M j, Y', strtotime($request['Date'])); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Vehicle:</span>
                        <span><?php echo htmlspecialchars($request['PlateNo']); ?> (<?php echo htmlspecialchars($request['Vehicle_Type']); ?>)</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Mechanic:</span>
                        <span><?php echo htmlspecialchars($request['Mechanic_Name'] ?? 'Not assigned'); ?></span>
                    </div>
                    
                    <div class="problem-section">
                        <div class="info-label">Problem Description:</div>
                        <p><?php echo nl2br(htmlspecialchars($request['Problem_of_vehicle'])); ?></p>
                    </div>
                    
                    <div class="actions">
                        <button onclick="showEditForm(<?php echo htmlspecialchars(json_encode($request)); ?>)" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <a href="view_requests.php?delete=<?php echo $request['Driver_id']; ?>" class="btn btn-delete" 
                           onclick="return confirm('Delete request #<?php echo htmlspecialchars($request['Driver_id']); ?>?')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-requests">
                <h3><i class="far fa-folder-open"></i> No maintenance requests found</h3>
                <p>You haven't submitted any maintenance requests yet.</p>
                <a href="requestmaintenance1.php" class="btn-new">
                    <i class="fas fa-plus-circle"></i> Create New Request
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="hideEditForm()">&times;</button>
            <h2>Edit Maintenance Request</h2>
            <form id="editRequestForm" method="post" action="update_request.php">
                <input type="hidden" name="request_id" id="editRequestId">
                
                <div class="form-group">
                    <label for="editDriverId">Driver ID</label>
                    <input type="text" name="Driver_id" id="editDriverId" required>
                </div>
                
                <div class="form-group">
                    <label for="editDriverName">Driver Name</label>
                    <input type="text" name="Driver_Name" id="editDriverName" required>
                </div>
                
                <div class="form-group">
                    <label for="editMechanicName">Mechanic Name</label>
                    <input type="text" name="Mechanic_Name" id="editMechanicName" required>
                </div>
                
                <div class="form-group">
                    <label for="editPlateNo">Plate Number</label>
                    <input type="text" name="PlateNo" id="editPlateNo" required>
                </div>
                
                <div class="form-group">
                    <label for="editVehicleType">Vehicle Type</label>
                    <input type="text" name="Vehicle_Type" id="editVehicleType" required>
                </div>
                
                <div class="form-group">
                    <label for="editProblem">Problem Description</label>
                    <textarea name="Problem_of_vehicle" id="editProblem" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="hideEditForm()">Cancel</button>
                    <button type="submit" class="btn btn-submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Edit form functions
        function showEditForm(request) {
            document.getElementById('editRequestId').value = request.Driver_id;
            document.getElementById('editDriverId').value = request.Driver_id;
            document.getElementById('editDriverName').value = request.Driver_Name;
            document.getElementById('editMechanicName').value = request.Mechanic_Name || '';
            document.getElementById('editPlateNo').value = request.PlateNo;
            document.getElementById('editVehicleType').value = request.Vehicle_Type;
            document.getElementById('editProblem').value = request.Problem_of_vehicle;
            
            document.getElementById('editModal').style.display = 'flex';
        }

        function hideEditForm() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Handle form submission
        document.getElementById('editRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('update_request.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update request'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the request');
            });
        });

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideEditForm();
            }
        });
    </script>
</body>
</html>