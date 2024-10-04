<?php
include('dbconnect.php');
session_start();

// Set the default timezone to Malaysia (UTC+8)
date_default_timezone_set('Asia/Kuala_Lumpur');

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit();
}

// Check if visitor ID is provided
if (isset($_GET['id'])) {
    $visitorID = $_GET['id'];
    $exitTime = date('Y-m-d H:i:s'); // Get the current Malaysian time

    // Prepare the SQL statement to update the exit time
    $sql = "UPDATE visitors SET ExitTime = ? WHERE VisitorID = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("si", $exitTime, $visitorID);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Visitor exit time recorded successfully."; // Set success message
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error; // Capture error message
        }
        
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing statement: " . $conn->error; // Capture preparation error
    }

    // Redirect back to the dashboard
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request."; // Handle case when ID is not provided
    header("Location: dashboard.php");
    exit();
}
?>
