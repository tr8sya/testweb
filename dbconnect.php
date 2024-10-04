<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = "";     // Password for your MySQL, leave empty if default
$dbname = "hospital_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
