<?php
session_start();

// Check if user_email exists in the session
if (!isset($_SESSION['user_email'])) {
    die("Error: User not logged in. Please log in first.");
}

$company_email = $_SESSION['user_email'];

$conn = new mysqli("localhost", "root", "", "internship_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<form action="assign_task.php" method="POST">
    <label>Student Email:</label>
    <select name="student_email" required>
        <?php include 'fetch_applicants.php'; ?>
    </select><br>

    <label>Task Name:</label>
    <input type="text" name="task_name" required><br>

    <label>Task Description:</label>
    <textarea name="task_description" required></textarea><br>

    <label>Due Date:</label>
    <input type="date" name="due_date" required><br>

    <button type="submit">Assign Task</button>
</form>

