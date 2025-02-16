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

// Ensure the user is logged in as a student
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    die("Unauthorized access");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_email = $_SESSION["email"];
    $company_email = $_POST["company_email"];

    // Handle file upload
    $cv_file = "";
    if (isset($_FILES["cv_file"]) && $_FILES["cv_file"]["error"] == 0) {
        $cv_dir = "uploads/";
        if (!is_dir($cv_dir)) {
            mkdir($cv_dir, 0777, true);
        }
        $cv_file = $cv_dir . basename($_FILES["cv_file"]["name"]);
        move_uploaded_file($_FILES["cv_file"]["tmp_name"], $cv_file);
    }

    $stmt = $conn->prepare("INSERT INTO applications (student_email, company_email, cv_file, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $student_email, $company_email, $cv_file);

    if ($stmt->execute()) {
        header("Location: student.html?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>