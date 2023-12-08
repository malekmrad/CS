<?php
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_email, $db_password);

    if ($stmt->fetch() && password_verify($password, $db_password)) {
        // Start a session and store user ID
        session_start();
        $_SESSION["user_id"] = $user_id;

        header("Location: welcome");
        exit();
    } else {
        echo "Invalid login credentials";
    }

    $stmt->close();
    $conn->close();
}
?>
