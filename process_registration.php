<?php
// Database connection parameters
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'findhire';
$user = 'your_db_username'; // Replace with your database username
$pass = 'your_db_password'; // Replace with your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashed_password, $role);

// Execute the statement
if ($stmt->execute()) {
    echo "Registration successful! You can now <a href='login.php?role=$role'>login</a>.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>