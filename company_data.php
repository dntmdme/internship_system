<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    die("Error: User not logged in. Please log in first.");
}

$company_email = $_SESSION['user_email'];

$servername = "localhost";
$username = "root";
$password = "";
$database = "internship_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tasks assigned by the logged-in company
$stmt = $conn->prepare("SELECT student_email, task_name, task_file, due_date, status FROM tasks WHERE assigned_by = ?");
$stmt->bind_param("s", $company_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Assigned Tasks</h2>
<table border="1" width="100%">
    <tr>
        <th>Student Email</th>
        <th>Task Name</th>
        <th>Task Description (File)</th>
        <th>Due Date</th>
        <th>Status</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["student_email"]); ?></td>
                <td><?php echo htmlspecialchars($row["task_name"]); ?></td>
                <td>
                    <?php if (!empty($row["task_file"])): ?>
                        <a href="<?php echo htmlspecialchars($row["task_file"]); ?>" download>Download</a>
                    <?php else: ?>
                        No File
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row["due_date"]); ?></td>
                <td><?php echo htmlspecialchars($row["status"]); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">No tasks assigned yet.</td></tr>
    <?php endif; ?>
</table>

<?php $conn->close(); ?>