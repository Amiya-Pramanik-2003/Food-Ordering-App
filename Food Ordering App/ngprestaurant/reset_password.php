<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = $_GET['token'];

    // Validate the token
    $stmt = $conn->prepare("SELECT email FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo "Invalid or expired token.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password and update the user's record
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    
    if ($stmt->execute()) {
        echo "Password successfully updated!";
        // Optionally redirect to login page
        header("Location: login.html");
        exit();
    } else {
        echo "Error updating password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <form action="reset_password.php" method="post">
      <h2>Reset Password</h2>
      <input type="hidden" name="email" value="<?php echo $email; ?>">
      <div class="input-field">
        <input type="password" name="password" required>
        <label>Enter new password</label>
      </div>
      <div class="input-field">
        <input type="password" name="confirm_password" required>
        <label>Confirm new password</label>
      </div>
      <button type="submit">Reset Password</button>
    </form>
  </div>
</body>
</html>
