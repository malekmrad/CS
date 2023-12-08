<?php
$servername = "localhost";
$username = "root";
$password = "elmjoujmin";
$database = "cs";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function hashPassword($password) {
    // Use a strong hashing algorithm such as bcrypt
    $options = [
        'cost' => 12, // Adjust the cost according to your security needs
    ];
    return password_hash($password, PASSWORD_BCRYPT, $options);
}

// Function to verify a password against a hashed version
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}
?>
