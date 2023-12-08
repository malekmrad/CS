<?php
require_once 'db_connection.php';
require_once 'vendor/autoload.php';
require_once 'lib/PHPGangsta/GoogleAuthenticator.php';

use OTPHP\TOTP;
use Endroid\QrCode\QrCode;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verifyPassword = $_POST['verify_password'];

    // Check if passwords match
    if ($password !== $verifyPassword) {
        echo 'Error: Passwords do not match.';
        exit;
    }

    // Check if the password contains special characters, lowercase, uppercase, and numbers
    if (!preg_match('/^(?=.*[!@#$%^&*(),.?":{}|<>])(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        echo 'Error: Password must contain at least one special character, one lowercase letter, one uppercase letter, and one number.';
        exit;
    }

    // Hash the password (you can use the method from previous examples)
    $hashedPassword = hashPassword($password);

    // Create a secret key for Google Authenticator
    $totp = TOTP::create();
    $totp->setLabel($email); // Use the email as the label
    $secret = $totp->getSecret();

    // Debugging statements
    $otpauth = 'otpauth://totp/'.rawurlencode("$email:$fname").'?secret='.rawurlencode($secret).'&email='.rawurlencode($email);
    // Store the user information and the secret key in the database
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password, secret_key) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $lname, $email, $hashedPassword, $secret);

    if ($stmt->execute()) {
        // Display QR Code, Secret Key, and User Email for Google Authenticator
       
        
        // Retrieve the user's email from the database
        $emailQuery = "SELECT email FROM users WHERE id = LAST_INSERT_ID()";
        $result = $conn->query($emailQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userEmail = $row['email'];

        } else {
            echo 'Error retrieving user email from the database.';
        }

    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="jquery.qrcode-0.7.0.js"></script>

    <script>
        function checkPasswordRequirements() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("verify_password").value;

            // Check password requirements
            if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*(),.?\:{}|<>]).{8,}/.test(password)) {
                alert("Password must meet the requirements:\n" +
                    "- At least one digit (0-9)\n" +
                    "- At least one lowercase letter\n" +
                    "- At least one uppercase letter\n" +
                    "- At least one special character\n" +
                    "- Minimum length of 8 characters");
                return false;
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                alert("Passwords do not match. Please make sure they match.");
                return false;
            }

            return true; // Passwords meet the requirements
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="" method="post" onsubmit="return checkPasswordRequirements();">
            <!-- Your existing form fields -->
            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname" required>

            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*(),.?\:{}|<>]).{8,}" title="Password must contain at least one digit, one lowercase letter, one uppercase letter, one special character, and be at least 8 characters long" required>

            <label for="verify_password">Verify Password:</label>
            <input type="password" name="verify_password" id="verify_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*(),.?\:{}|<>]).{8,}" title="Password must contain at least one digit, one lowercase letter, one uppercase letter, one special character, and be at least 8 characters long" required onpaste="return false;">

            <input type="submit" value="Sign Up">
        </form>
        <pre><?= $otpauth ?></pre>
        <div id="qrcode"></div>
    </div>

    <script type="text/javascript">
        $('#qrcode').html('').qrcode({
            text: '<?= $otpauth ?>',
            ecLevel: 'L',
            size: 200,
            render: 'image',
        });
    </script>
</body>
</html>