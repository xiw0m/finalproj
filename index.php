<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindHire - Job Application System</title>
<style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

h1 {
    color: #333;
    margin-bottom: 10px;
}

p {
    color: #666;
    margin-bottom: 20px;
}

.role-selection {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn {
    display: inline-block;
    padding: 10px 15px;
    font-size: 16px;
    color: white;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

@media (max-width: 600px) {
    .container {
        width: 95%;
    }
}
</style>

</head>
<body>
    <div class="container">
        <h1>Welcome to FindHire</h1>
        <p>Your one-stop solution for job applications and recruitment.</p>
        <p>Please choose your role to get started:</p>
        <div class="role-selection">
            <a href="register.php?role=applicant" class="btn">Register as Applicant</a>
            <a href="register.php?role=hr" class="btn">Register as HR</a>
            <a href="login.php?role=applicant" class="btn">Login as Applicant</a>
            <a href="login.php?role=hr" class="btn">Login as HR</a>
        </div>
    </div>
</body>
</html>