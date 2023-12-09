Objective:
  The main objective of this project is to design and implement a secure website, focusing on fundamental security measures to protect against common vulnerabilities.
  The project incorporates several key security features to safeguard user data and enhance the overall security posture of the web application.

Implemented Security Measures:
  1-HTTPS Implementation:
    Configured a secure HTTPS connection for the web server.
    Generated SSL certificate using OpenSSL, including public and private keys.
  2-Prevention of SQL Injection:
    Implemented measures to prevent SQL injection attacks.
    Removed any PHP extensions and sensitive data from the URL.
    Utilized ".htaccess" to redirect any URL manipulations to a 404 Error page.
  3-Form Input Validation:
    Implemented strict validation rules for form inputs.
    Required the use of lowercase and uppercase letters, numbers, and special characters.
    Disabled pasting in the "Verify Password" field for enhanced security.
  4-Database Security:
    Configured PHPMyAdmin with a secure database password.
    Stored user account information in the database with hashed passwords using HMAC (Hash-based Message Authentication Code).
  5-Session Security:
    Assigned a unique secret key to each session for added security.
  6-Two-Factor Authentication (2FA):
    Integrated Google Authenticator for two-factor authentication.
    Users are required to scan a QR code during account creation.
    The Google Authenticator app generates a unique code that must be entered during login (changes every minute).

Conclusion:
The implementation of these security measures significantly enhances the overall security of the website.
By securing the communication channel, preventing common web vulnerabilities, and incorporating additional layers such as 2FA, the project aims to create a robust and resilient web application against potential threats.

This project not only addresses basic security concerns but also provides a foundation for further security enhancements and continuous improvement.
