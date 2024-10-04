<?php
include('dbconnect.php');
session_start();

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $visitorName = $_POST['visitor_name'];
    $visitorPhone = $_POST['visitor_phone'];

    // Insert visitor info into the database without hashing the password
    $sql = "INSERT INTO visitors (Username, Password, VisitorName, VisitorPhone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $password, $visitorName, $visitorPhone);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Registration successful! You can now log in."; // Set success message
        header("Location: visitor_register.php"); // Redirect to the same page to display message
        exit(); // Stop execution after redirection
    } else {
        $error = "Registration failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Registration</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>
    <div class="login-container">
        <h2>Create Visitor Account</h2>
        <?php 
        // Display success message if set
        if (isset($_SESSION['success_message'])) {
            echo "<div class='success-message' style='color: green;'>".$_SESSION['success_message']."</div>";
            unset($_SESSION['success_message']); // Clear the message after displaying
        }
        ?>
        <form action="visitor_register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="text" name="visitor_name" placeholder="Your Name" required><br>
            <input type="text" name="visitor_phone" placeholder="Your Phone" required><br>
            <button type="submit" name="register">Register</button>
        </form>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Links to Visitor and Staff Login -->
        <div class="login-redirect">
            <p>Have a visitor account? <a href="visitor_login.php">Log in here</a></p>
            <p>Are you a staff? <a href="index.php">Login to staff</a></p>
        </div>
    </div>
</body>
</html>
