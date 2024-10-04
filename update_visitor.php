<?php
include('dbconnect.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit();
}

// Initialize message variables
$successMessage = '';
$errorMessage = '';

// Check if the 'id' is set in the URL
if (isset($_GET['id'])) {
    $visitorID = $_GET['id'];
} else {
    $errorMessage = "Error: Visitor ID not provided.";
    exit();
}

if (isset($_POST['update'])) {
    $visitorID = $_POST['visitorid'];
    $visitorName = $_POST['visitorname'];
    $visitorPhone = $_POST['visitorphone'];
    $visitingTime = $_POST['visitingtime'];

    $sql = "UPDATE visitors SET VisitorName='$visitorName', VisitorPhone='$visitorPhone', 
            VisitingTime='$visitingTime' WHERE VisitorID='$visitorID'";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Visitor updated successfully.";
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
}

// Fetch visitor data for the form
$result = $conn->query("SELECT * FROM visitors WHERE VisitorID='$visitorID'");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Visitor</title>
    <link rel="stylesheet" href="css/styleupdate.css">
</head>
<body>

<header>
    <h1>Visitor Management System</h1>
</header>

<div class="container">
    <div class="content">
        <h2>Update Visitor</h2>

        <!-- Display success or error messages inside the box -->
        <?php if ($successMessage): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php elseif ($errorMessage): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form action="update_visitor.php?id=<?php echo $visitorID; ?>" method="POST">
            <input type="hidden" name="visitorid" value="<?php echo $row['VisitorID']; ?>">
            
            <label for="visitorname">Visitor Name:</label>
            <input type="text" id="visitorname" name="visitorname" value="<?php echo $row['VisitorName']; ?>" required><br>

            <label for="visitorphone">Visitor Phone:</label>
            <input type="text" id="visitorphone" name="visitorphone" value="<?php echo $row['VisitorPhone']; ?>" required><br>

            <label for="visitingtime">Visiting Time:</label>
            <input type="datetime-local" id="visitingtime" name="visitingtime" 
                   value="<?php echo date('Y-m-d\TH:i', strtotime($row['VisitingTime'])); ?>" required><br>

            <button type="submit" name="update">Update Visitor</button>
        </form>
        
        <!-- Centered Back to Dashboard Button -->
        <div class="btn-container" style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </div>
</div>

<footer>
    <p>Hospital Visitor Management System by LZ</p>
</footer>

</body>
</html>
