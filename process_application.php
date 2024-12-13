<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    header("Location: login.php?role=hr");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $application_id = $_POST['application_id'];
    $action = $_POST['action'];

    // Validate action
    if ($action !== 'accept' && $action !== 'reject') {
        die("Error: Invalid action.");
    }

    // Update application status
    $new_status = ($action === 'accept') ? 'accepted' : 'rejected';
    $sql = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $application_id);

    if ($stmt->execute()) {
        echo "Application status updated successfully.<br>";
        echo '<a href="hr_dashboard.php">Back to Dashboard</a>';
    } else {
        echo "Error updating application status: " . $stmt->error;
    }

    // Close the status update statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>