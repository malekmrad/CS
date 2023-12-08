<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome!</h2>
        <!-- Display user information or any other content as needed -->
        <p>You are logged in.</p>
        <a href="login">Logout</a>
    </div>
</body>
</html>
