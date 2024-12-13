<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'applicant') {
    header("Location: login.php?role=applicant");
    exit();
}

// Database connection parameters
$host = 'localhost';
$db = 'findhire';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hr_id = $_POST['hr_id'];
    $message = $_POST['message'];
    $applicant_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Validate that HR ID is provided
    if (empty($hr_id)) {
        die("Error: No HR selected.");
    }

    // Insert message into the database
    $sql = "INSERT INTO messages (applicant_id, hr_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $applicant_id, $hr_id, $message);

    if ($stmt->execute()) {
        echo "Message sent successfully!<br>";
        echo '<a href="applicant_dashboard.php">Back to Dashboard</a>';
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>