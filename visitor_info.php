<?php
session_start();
require_once "dbconnect.php"; // Include your dbconnect file for database connection

// Redirect to dashboard if no visitor ID is found
if (!isset($_SESSION['visitor_id'])) {
    header("Location: visitor_dashboard.php");
    exit();
}

$visitor_id = $_SESSION['visitor_id'];
$exit_time = date("Y-m-d H:i:s"); // Current time

// Fetch visitor information from the database
$sql = "SELECT v.visitor_name, v.visitor_phone, p.PatientName, p.PatientID, p.PatientRoom, v.VisitingTime 
        FROM visitors v
        JOIN patients p ON v.PatientID = p.PatientID
        WHERE v.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $visitor_id);
$stmt->execute();
$result = $stmt->get_result();
$visitor = $result->fetch_assoc();

$visitor_name = $visitor['visitor_name'] ?? '';
$visitor_phone = $visitor['visitor_phone'] ?? '';
$patient_name = $visitor['PatientName'] ?? '';
$patient_id = $visitor['PatientID'] ?? '';
$patient_room = $visitor['PatientRoom'] ?? '';
$visiting_time = strtotime($visitor['VisitingTime']); // Convert to timestamp

// Calculate duration
$current_time = time();
$duration = $current_time - $visiting_time; // Duration in seconds
$duration_minutes = floor($duration / 60); // Convert to minutes

// Process form data when updating or exiting
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_info"])) {
        $visitor_name = trim($_POST["visitor_name"]);
        $visitor_phone = trim($_POST["visitor_phone"]);

        $sql = "UPDATE visitors SET visitor_name = ?, visitor_phone = ? WHERE id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssi", $visitor_name, $visitor_phone, $visitor_id);
            if ($stmt->execute()) {
                echo "<div class='success-message'>Information updated successfully.</div>";
            } else {
                echo "<div class='error-message'>Error updating information.</div>";
            }
            $stmt->close();
        }
    } elseif (isset($_POST["exit"])) {
        $sql = "UPDATE visitors SET exit_time = ? WHERE id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $exit_time, $visitor_id);
            if ($stmt->execute()) {
                echo "<div class='success-message'>You have successfully checked out.</div>";
                session_destroy(); // End session after exiting
                header("Location: visitor_dashboard.php");
                exit();
            } else {
                echo "<div class='error-message'>Error processing exit.</div>";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Info</title>
    <link rel="stylesheet" href="stylevisit.css"> <!-- Link to your CSS file -->
</head>
<body>
<header>
    <h1>Visitor Information</h1>
</header>
<div class="container">
    <h2>Manage Your Visit</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Visitor Name</label>
            <input type="text" name="visitor_name" value="<?php echo htmlspecialchars($visitor_name); ?>" required>
        </div>
        <div>
            <label>Visitor Phone</label>
            <input type="text" name="visitor_phone" value="<?php echo htmlspecialchars($visitor_phone); ?>" required>
        </div>
        <div>
            <label>Patient Name</label>
            <input type="text" value="<?php echo htmlspecialchars($patient_name); ?>" readonly>
        </div>
        <div>
            <label>Patient ID</label>
            <input type="text" value="<?php echo htmlspecialchars($patient_id); ?>" readonly>
        </div>
        <div>
            <label>Patient Room</label>
            <input type="text" value="<?php echo htmlspecialchars($patient_room); ?>" readonly>
        </div>
        <div>
            <label>Duration (minutes)</label>
            <input type="text" value="<?php echo $duration_minutes; ?>" readonly>
        </div>
        
        <div class="nav-button-container">
            <input type="submit" name="update_info" value="Update Information">
            <input type="submit" name="exit" value="Exit" class="btn-logout">
        </div>
    </form>

    <!-- Include buttons to return to the dashboard or logout -->
    <div class="nav-button-container">
        <a href="visitor_dashboard.php" class="button">Back to Dashboard</a>
        <a href="logout.php" class="button btn-logout">Logout</a>
    </div>
</div>
<footer>
    <p>&copy; 2024 Hospital Visitor Management System</p>
</footer>
</body>
</html>
