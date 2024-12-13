<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hr') {
    header("Location: login.php?role=hr");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Post - FindHire</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Create Job Post</h1>
        <form action="process_job_post.php" method="POST">
            <label for="title">Job Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="description">Job Description:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" required>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
            <button type="submit">Post Job</button>
        </form>
    </div>
</body>
</html>