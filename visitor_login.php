<?php
include('dbconnect.php');
session_start();

$error = ''; // Variable to hold error messages

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user based on username from the visitors table
    $sql = "SELECT * FROM visitors WHERE Username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password (change to password_verify if you're using hashed passwords)
        if ($password == $row['Password']) {
            $_SESSION['VisitorID'] = $row['VisitorID']; // Store Visitor ID in session
            $_SESSION['Username'] = $row['Username']; // Store Username in session for dashboard
            
            // Redirect to visitor dashboard
            header("Location: visitor_dashboard.php");
            exit(); // Stop further script execution
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Login</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>
    <div class="login-container">
        <h2>Visitor Login</h2>
        <form action="visitor_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="visitor-signin">
            <p>Not registered? <a href="visitor_register.php">Create an account</a></p>
        </div>
    </div>
</body>
</html>
