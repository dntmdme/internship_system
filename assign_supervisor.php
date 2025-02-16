<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supervisor_email = $_POST['supervisor_email'];
    $student_email = $_POST['student_email'];

    if (!empty($supervisor_email) && !empty($student_email)) {
        $stmt = $conn->prepare("INSERT INTO supervisor_assignments (supervisor_email, student_email) VALUES (?, ?)");
        $stmt->bind_param("ss", $supervisor_email, $student_email);

        if ($stmt->execute()) {
            echo "Supervisor assigned successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Please provide both student and supervisor emails.";
    }
}

$conn->close();
?>