<?php
session_start();
require 'db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    die("Error: User not logged in.");
}

$user_email = $_SESSION['email'];  // This could be supervisor or company

// Get form data
$student_email = $_POST['student_email'];
$task_name = $_POST['task_name'];
$due_date = $_POST['due_date'];
$task_file = $_FILES['task_file']['name'];

// Check if the logged-in user is a supervisor or a company
$sql_check_role = "SELECT role FROM users WHERE email = ?";
$stmt_role = $conn->prepare($sql_check_role);
$stmt_role->bind_param("s", $user_email);
$stmt_role->execute();
$result_role = $stmt_role->get_result();
$user_role = $result_role->fetch_assoc()['role'];
$stmt_role->close();

// Check if the student is assigned to this supervisor OR applied to this company
if ($user_role == "supervisor") {
    // Supervisor can only assign tasks to students assigned to them
    $sql_check = "SELECT * FROM supervisor_assignments WHERE supervisor_email = ? AND student_email = ?";
} elseif ($user_role == "company") {
    // Company can only assign tasks to students who applied to them
    $sql_check = "SELECT * FROM applications WHERE company_email = ? AND student_email = ?";
} else {
    die("Error: Invalid user role.");
}

$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $user_email, $student_email); // FIXED LINE
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$stmt_check->close();

if ($result_check->num_rows == 0) {
    die("Error: You are not authorized to assign a task to this student.");
}

// Upload Task File
$target_dir = "uploads/";
$target_file = $target_dir . basename($task_file);
move_uploaded_file($_FILES["task_file"]["tmp_name"], $target_file);

// Insert Task into Database
$sql_insert = "INSERT INTO tasks (student_email, assigned_by, task_name, due_date, task_file, status)
               VALUES (?, ?, ?, ?, ?, 'New')";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("sssss", $student_email, $user_email, $task_name, $due_date, $task_file);

if ($stmt_insert->execute()) {
    echo "Task assigned successfully.";
} else {
    echo "Error: Could not assign task.";
}

$stmt_insert->close();
$conn->close();
?>
