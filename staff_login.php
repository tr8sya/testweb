<?php
include('dbconnect.php');
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user based on username
    $sql = "SELECT * FROM users WHERE Username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password (change to password_verify if using hashed passwords)
        if ($password == $row['Password']) {
            $_SESSION['UserID'] = $row['UserID'];
            header("Location: dashboard.php");
            exit();
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
    <title>Staff Login</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>


    <!-- Main container for login -->
    <div class="login-container">
        <h2>Staff Login</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>

    <!-- Extra options container -->
    <div class="extra-options">
        <div class="visitor-signin">
            <p>Not a staff? <a href="visitor_login.php">Sign in as Visitor</a></p>
        </div>

        <div class="register-link">
            <p>Don't have an account? <a href="staff_register.php">Register New Staff</a></p>
        </div>
    </div>

    <!-- Help Icon -->
    <a href="help.php" class="help-icon">
        <img src="help.png" alt="Help Icon">
    </a>
</body>
</html>
