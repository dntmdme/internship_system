<?php
session_start(); // Start the session

// ✅ Ensure user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    die("Error: User not logged in. Please log in first.");
}

// ✅ Ensure only students can access this page
if ($_SESSION['role'] !== 'student') {
    die("Error: Only students can access this page.");
}

// ✅ Get student email from session
$student_email = $_SESSION['email'];

// ✅ Connect to database
$conn = new mysqli("localhost", "root", "", "internship_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Fetch only tasks assigned to this student
$sql = "SELECT * FROM tasks WHERE student_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Display tasks in a table
echo "<table border='1' width='100%'>";
echo "<tr>
        <th>Task Name</th>
        <th>Assigned By</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Task File</th>
        <th>Submit Report</th>
    </tr>";

// ✅ If tasks exist, display them
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["task_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["assigned_by"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["due_date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";

        // ✅ Task file download link (Fix: Ensure correct file path)
        if (!empty($row["task_file"])) {
            echo "<td><a href='uploads/" . htmlspecialchars($row["task_file"]) . "' download>Download</a></td>";
        } else {
            echo "<td>No file provided</td>";
        }

        // ✅ Submit report form
        echo "<td>
                <form action='submit_report.php' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='task_id' value='" . $row["id"] . "'>
                    <input type='file' name='report_file' accept='.pdf,.doc,.docx' required>
                    <button type='submit'>Submit</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No tasks assigned yet.</td></tr>";
}

echo "</table>";

// ✅ Close database connection
$stmt->close();
$conn->close();
?>