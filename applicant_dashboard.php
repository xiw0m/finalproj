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

// Fetch active job postings
$sql = "SELECT jp.id, jp.title, jp.description, u.username AS hr_username 
        FROM job_postings jp 
        JOIN users u ON jp.hr_id = u.id 
        WHERE jp.status = 'active'";

$result = $conn->query($sql);

// Fetch HR users for messaging
$hr_sql = "SELECT id, username FROM users WHERE role = 'hr'";
$hr_result = $conn->query($hr_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Applicant Dashboard</h1>
    <h2>Available Job Postings</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Description</th>
                    <th>Posted By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['hr_username']); ?></td>
                        <td>
                            <form action="process_application.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                <textarea name="cover_letter" placeholder="Describe why you are the best applicant for this role." required></textarea>
                                <input type="file" name="resume" accept=".pdf" required>
                                <button type="submit">Apply</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No job postings available at the moment.</p>
    <?php endif; ?>

    <h2>Send a Message to HR</h2>
    <form action="process_message.php" method="POST">
        <select name="hr_id" required>
            <option value="">Select HR</option>
            <?php while ($hr_row = $hr_result->fetch_assoc()): ?>
                                <option value="<?php echo $hr_row['id']; ?>"><?php echo htmlspecialchars($hr_row['username']); ?></option>
            <?php endwhile; ?>
        </select>
        <textarea name="message" placeholder="Type your message here..." required></textarea>
        <button type="submit">Send Message</button>
    </form>

    <a href="logout.php">Logout</a>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>