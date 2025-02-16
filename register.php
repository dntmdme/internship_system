<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "internship_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $age = trim($_POST["age"]);
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Validate required fields
    if (empty($username) || empty($email) || empty($age) || empty($password) || empty($role)) {
        die("Error: All fields are required.");
    }

    // Check if email already exists
    $check_email_sql = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Error: Email already exists. Please use another email.'); window.location.href = 'index.html';</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $insert_sql = "INSERT INTO users (username, email, age, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssiss", $username, $email, $age, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Registration Successful</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; margin: 100px; }
                    .success { color: green; font-size: 18px; }
                </style>
                <meta http-equiv='refresh' content='3;url=index.html'> 
            </head>
            <body>
                <h1 class='success'>You're successfully registered, redirecting...</h1>
            </body>
            </html>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
