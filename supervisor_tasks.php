<?php
session_start(); // ✅ Start the session

// ✅ Check if the company is logged in
if (!isset($_SESSION['email'])) {
    die("Error: User not logged in. Please log in first.");
}

$company_email = $_SESSION['email']; // ✅ Get logged-in company's email

$conn = new mysqli("localhost", "root", "", "internship_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Fetch tasks assigned by this company
$sql = "SELECT * FROM tasks WHERE assigned_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $company_email);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Display tasks in a table
echo "<table border='1' width='100%'>";
echo "<tr>
        <th>Student Email</th>
        <th>Task Name</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Report File</th>
        <th>Update Status</th>
      </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["student_email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["task_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["due_date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";

        // ✅ Show report file if uploaded
        if (!empty($row["report_file"])) {
            echo "<td><a href='" . htmlspecialchars($row["report_file"]) . "' download>Download</a></td>";
        } else {
            echo "<td>No report submitted</td>";
        }

        // ✅ Update task status
        echo "<td>
                <form action='update_task_status.php' method='POST'>
                    <input type='hidden' name='task_id' value='" . $row["id"] . "'>
                    <select name='status'>
                        <option value='New'>New</option>
                        <option value='In Progress'>In Progress</option>
                        <option value='Completed'>Completed</option>
                    </select>
                    <button type='submit'>Update</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No tasks assigned yet.</td></tr>";
}

echo "</table>";

$stmt->close();
$conn->close();
?>