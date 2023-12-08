<?php
require_once 'db_connection.php';
require_once 'vendor/autoload.php'; // Path to your autoload.php

use OTPHP\TOTP;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $otpCode = $_POST['otp_code'];

    // Retrieve user information from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        // Verify the password
        if (verifyPassword($password, $user['password'])) {
            // Verify the Google Authenticator code
            $totp = TOTP::create();
            $totp->setLabel($email); // Use the email as the label
            $isValidOtp = $totp->verify($otpCode);

            if ($isValidOtp) {
                // Successful login
                echo 'Login successful!';
            } else {
                echo 'Invalid Google Authenticator code.';
            }
        } else {
            echo 'Invalid password.';
        }
    } else {
        echo 'User not found.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="otp_code">Google Authenticator Code:</label>
            <input type="text" name="otp_code" id="otp_code" required><br>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
