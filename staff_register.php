<?php
include('dbconnect.php');
session_start();

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Basic validation
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) { // Check for minimum password length
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $sql = "SELECT * FROM users WHERE Username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Insert new user without hashing the password
            $sql = "INSERT INTO users (Username, Password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $password); // Store the password as plain text

            if ($stmt->execute()) {
                // Store success message in session
                $_SESSION['success'] = "Registration successful.";
                $registrationSuccess = true; // Flag to indicate successful registration
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Staff</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>
    <div class="login-container"> <!-- Ensure this class is used for consistent styling with index -->
        <h2>Register Staff</h2>
        <form action="staff_register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit" name="register">Register</button>
        </form>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php elseif (isset($registrationSuccess)): ?>
            <div class="success-message">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?><br>
                You can now log in. <br>
                <a href="staff_login.php">Go to Login</a>
            </div>
        <?php endif; ?>
        <div class="register-link">
            <p>Already have an account? <a href="staff_login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
