<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "internship_system";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Fetch user data
        $stmt->bind_result($id, $username, $hashed_password, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Store user data in session
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;

            // Debugging output
            // echo "Detected Role: " . $role; exit(); 

            // Redirect based on user role
            if ($role === "student") {
                header("Location: student.html");
                exit();
            } elseif ($role === "company") {
                header("Location: company.html");
                exit();
            } elseif ($role === "admin") {
                header("Location: admin.html");
                exit();
            } elseif ($role === "supervisor") {
                header("Location: supervisor.html");
                exit();
            } else {
                die("Error: Invalid role.");
            }
        } else {
            $_SESSION["login_error"] = "Incorrect password";
            header("Location: login_error.php");
            exit();
        }
    } else {
        $_SESSION["login_error"] = "Email not registered";
        header("Location: login_error.php");
        exit();
    }
}
?>