<?php
session_start();
$conn = new mysqli("localhost", "root", "", "internship_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Ensure student is logged in
if (!isset($_SESSION["email"])) {
    die("Error: User not logged in. Please log in first.");
}

$student_email = $_SESSION["email"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["report_file"])) {
    $task_id = $_POST["task_id"];
    $file_name = basename($_FILES["report_file"]["name"]);
    $target_dir = "uploads/reports/";
    $target_file = $target_dir . uniqid() . "_" . $file_name;

    // ✅ Ensure uploads folder exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // ✅ Move uploaded file
    if (move_uploaded_file($_FILES["report_file"]["tmp_name"], $target_file)) {
        // ✅ Update database with file path
        $sql = "UPDATE tasks SET report_file = ?, status = 'In Progress' WHERE id = ? AND student_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $target_file, $task_id, $student_email);

        if ($stmt->execute()) {
            echo "Report submitted successfully!";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>>
