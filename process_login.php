<?php
// Database connection parameters
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'findhire';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

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

// Prepare and execute the query to find the user
$stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify the password
    if (password_verify($password, $user['password'])) {
        // Check if the role matches
        if ($user['role'] === $role) {
            // Successful login
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'applicant') {
                header("Location: applicant_dashboard.php"); // Redirect to applicant dashboard
            } else {
                header("Location: hr_dashboard.php"); // Redirect to HR dashboard
            }
            exit();
        } else {
            // Role mismatch
            echo "You are not authorized to log in as a " . ucfirst($role) . ".";
        }
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Invalid username or password.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>