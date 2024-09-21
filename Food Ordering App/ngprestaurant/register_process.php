<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect and sanitize input data
$user = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$pass = $_POST['password'];
$confirm_pass = $_POST['confirm-password'];

// Validate password match
if ($pass !== $confirm_pass) {
    echo "Passwords do not match.";
    exit();
}

// Hash the password
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ssss", $user, $email, $phone, $hashed_pass);

// Execute and check if successful
if ($stmt->execute()) {
    // Redirect to login page
    header("Location: login.html");
    exit(); // Make sure to exit after redirect
} else {
    echo "Error: " . $stmt->error;
}
// Close connections
$stmt->close();
$conn->close();
?>
