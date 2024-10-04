<?php
include('dbconnect.php');
session_start();

if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $visitorID = $_GET['id'];

    // Fetch the visitor's details before deleting
    $sqlFetch = "SELECT * FROM visitors WHERE VisitorID='$visitorID'";
    $result = $conn->query($sqlFetch);
    
    if ($result && $result->num_rows > 0) {
        // Fetch visitor's data
        $visitor = $result->fetch_assoc();

        // Verify each field exists
        $visitorName = $visitor['VisitorName'] ?? 'N/A';
        $visitorPhone = $visitor['VisitorPhone'] ?? 'N/A';
        $patientID = $visitor['PatientID'] ?? null; // Ensure this is set correctly
        $patientName = $visitor['PatientName'] ?? 'N/A';
        $visitingTime = $visitor['VisitingTime'] ?? 'N/A';
        $exitTime = $visitor['ExitTime'] ?? 'N/A';

        // Insert visitor's data into the deleted_visitors table
        $sqlInsert = "INSERT INTO deleted_visitors (VisitorID, VisitorName, VisitorPhone, PatientID, PatientName, VisitingTime, ExitTime)
                      VALUES ('$visitorID', '$visitorName', '$visitorPhone', 
                              '$patientID', '$patientName', '$visitingTime', 
                              '$exitTime')";

        // Check if PatientID is null or not
        if ($patientID === null) {
            echo "Error: PatientID is not set.";
            exit();
        }

        if ($conn->query($sqlInsert) === TRUE) {
            // Now delete the visitor from the visitors table
            $sqlDelete = "DELETE FROM visitors WHERE VisitorID='$visitorID'";
            
            if ($conn->query($sqlDelete) === TRUE) {
                echo "Visitor deleted and moved to history successfully.";
            } else {
                echo "Error deleting visitor: " . $conn->error;
            }
        } else {
            echo "Error moving visitor to history: " . $conn->error;
        }
    } else {
        echo "Visitor not found.";
    }

    // Redirect back to the dashboard
    header("Location: dashboard.php");
    exit();
}
?>
