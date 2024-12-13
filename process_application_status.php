<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    header("Location: login.php?role=hr");
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
        // Fetch applicant's email to send notification
        $applicant_sql = "SELECT u.email FROM applications a JOIN users u ON a.user_id = u.id WHERE a.id = ?";
        $applicant_stmt = $conn->prepare($applicant_sql);
        $applicant_stmt->bind_param("i", $application_id);
        $applicant_stmt->execute();
        $applicant_result = $applicant_stmt->get_result();

        if ($applicant_result->num_rows > 0) {
            $applicant_row = $applicant_result->fetch_assoc();
            $applicant_email = $applicant_row['email'];

            // Send notification email
            $subject = "Application Status Update";
            $message = "Your application has been " . ($new_status === 'accepted' ? "accepted" : "rejected") . ".";
            $headers = "From: no-reply@findhire.com";

            if (mail($applicant_email, $subject, $message, $headers)) {
                echo "Application status updated successfully and notification sent to the applicant.<br>";
            } else {
                echo "Application status updated, but failed to send notification email.<br>";
            }
        } else {
            echo "Application status updated, but applicant email not found.<br>";
        }

        echo '<a href="hr_dashboard.php">Back to Dashboard</a>';
    } else {
        echo "Error updating application status: " . $stmt->error;
    }

    // Close the applicant statement
    $applicant_stmt->close();
    // Close the status update statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>