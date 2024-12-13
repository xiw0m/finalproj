<?php
$role = isset($_GET['role']) ? $_GET['role'] : 'applicant';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FindHire</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Register as <?php echo ucfirst($role); ?></h1>
        <form action="process_registration.php" method="POST">
            <input type="hidden" name="role" value="<?php echo $role; ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php?role=<?php echo $role; ?>">Login here</a></p>
    </div>
</body>
</html>