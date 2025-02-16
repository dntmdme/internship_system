<?php
session_start();
$error_message = isset($_SESSION["login_error"]) ? $_SESSION["login_error"] : "";
$email_input = isset($_SESSION["email_input"]) ? $_SESSION["email_input"] : "";

// Clear session error after displaying
unset($_SESSION["login_error"]);
unset($_SESSION["email_input"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Error</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 100px; }
        .error { color: red; font-size: 18px; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 15px; background: blue; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: darkblue; }
    </style>
</head>
<body>
    <h1>Login Error</h1>
    <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    
    <!-- Back to Login Page with Pre-Filled Email -->
    <a href="index.html?email=<?php echo urlencode($email_input); ?>" class="btn">Back to Login Page</a>
</body>
</html>