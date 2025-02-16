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
    if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "company") {
        die("Unauthorized access.");
    }

    $company_email = $_SESSION["email"];
    $student_email = $_POST["student_email"];
    $comments = $_POST["feedback_comments"];
    $rating = $_POST["performance_rating"];
    $status = $_POST["application_status"];
    $feedback_file = null;

    if (!empty($_FILES["feedback_file"]["name"])) {
        $target_dir = "uploads/";
        $feedback_file = $target_dir . basename($_FILES["feedback_file"]["name"]);
        move_uploaded_file($_FILES["feedback_file"]["tmp_name"], $feedback_file);
    }

    $stmt = $conn->prepare("INSERT INTO feedback (student_email, company_email, comments, performance_rating, application_status, feedback_file) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $student_email, $company_email, $comments, $rating, $status, $feedback_file);

    if ($stmt->execute()) {
        echo "Feedback successfully submitted!";
    } else {
        echo "Error submitting feedback.";
    }

    $stmt->close();
    $conn->close();
}
?>