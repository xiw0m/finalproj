<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'applicant') {
    header("Location: login.php?role=applicant");
    exit();
}

// Fetch job details from the database
$job_id = $_GET['job_id']; // Get the job ID from the URL
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

// Fetch job details
$job_query = $conn->prepare("SELECT * FROM job_postings WHERE id = ?");
$job_query->bind_param("i", $job_id);
$job_query->execute();
$job_result = $job_query->get_result();
$job = $job_result->fetch_assoc();

if (!$job) {
    echo "Job not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - FindHire</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Apply for <?php echo $job['title']; ?></h1>
        <form action="process_application.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
            <label for="cover_letter">Cover Letter:</label>
            <textarea id="cover_letter" name="cover_letter" required></textarea>
            <label for="resume">Upload Resume (PDF only):</label>
            <input type="file" id="resume" name="resume" accept=".pdf" required>
            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>