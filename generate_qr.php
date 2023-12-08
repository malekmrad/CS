<?php
require_once 'vendor/autoload.php';

use endroid\code\qrcode;

// Get the secret key and email from the query parameters
$secret = $_GET['secret'] ?? '';
$email = $_GET['email'] ?? '';

// Check if the secret key or email is missing
if (!$secret || !$email) {
    // Handle the case where the secret key or email is missing
    echo 'Error: Secret key or email is missing.';
    exit;
}

// Combine secret key and email for QR code content
$qrCodeContent = "Secret: $secret\nEmail: $email";

// Create a QR code
$qrCode = new QrCode($qrCodeContent);

// Set the HTTP header to indicate that this is an image
header('Content-Type: ' . $qrCode->getContentType());

// Output the QR code image
echo $qrCode->writeString();
