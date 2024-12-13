<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    header("Location: login.php?role=hr");
    exit();
}

// Database connection parameters
$host = 'localhost';
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
$title = $_POST['title'];
$description = $_POST['description'];
$company_name = $_POST['company_name'];
$location = $_POST['location'];
$hr_id = $_SESSION['user_id']; // Get the HR ID from the session

// Prepare and bind
$stmt