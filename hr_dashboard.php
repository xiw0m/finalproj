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

// Fetch job postings created by the HR
$hr_id = $_SESSION['user_id'];
$job_postings = $conn->query("SELECT * FROM job_postings WHERE hr_id = $hr_id");

// Fetch applications for the HR's job postings
$applications = $conn->query("SELECT a.*, j.title, u.username FROM applications a 
                               JOIN job_postings j ON a.job_id = j.id 
                               JOIN users u ON a.user_id = u.id 
                               WHERE j.hr_id = $hr_id");

// Fetch messages sent to the HR
$messages = $conn->query("SELECT m.*, u.username FROM messages m 
                           JOIN users u ON m.applicant_id = u.id 
                           WHERE m.hr_id = $hr_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard - FindHire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .options {
            margin-bottom: 20px;
        }

        .options h3 {
            margin-top: 0;
        }

        .options a {
            text-decoration: none;
            color: #337ab7;
        }

        .options a:hover {
            color: #23527c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .logout {
            text-align: right;
            margin-top: 20px;
        }

        .logout a {
            text-decoration: none;
            color: #337ab7;
        }

        .logout a:hover {
            color: #23527c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <h2>HR Dashboard</h2>

        <div class="options">
            <h3>Options</h3>
            <a href="job_post.php" class="btn">Write a Job Post</a>
            <h3>Manage Applications</h3>
            <table>
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $applications->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>
                                <form action="process_application.php" method="POST">
                                    <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="accept">Accept</button>
                                    <button type="submit" name="action" value="reject">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h3>Messages from Applicants</h3>
            <table>
                <thead>
                <tr>
                        <th>Applicant</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($msg = $messages->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $msg['username']; ?></td>
                            <td><?php echo $msg['message']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="logout">
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>