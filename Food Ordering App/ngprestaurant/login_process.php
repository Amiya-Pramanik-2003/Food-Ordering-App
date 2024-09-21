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
$email = $_POST['email'];
$pass = $_POST['password'];

// Prepare and execute query
$stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Check if email exists
if ($stmt->num_rows === 0) {
    echo "No user found with this email.";
    $stmt->close();
    $conn->close();
    exit();
}

// Bind result
$stmt->bind_result($hashed_pass);
$stmt->fetch();
$stmt->close();

// Verify password
if (password_verify($pass, $hashed_pass)) {
    // Redirect to the main page
    header("Location: main.html"); // Replace with your main page URL
    exit();
} else {
    echo "Invalid password.";
}

// Close connections
$conn->close();
?>
