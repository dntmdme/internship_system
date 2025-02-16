<?php
session_start();
require 'db_connection.php';

// Check if supervisor is logged in
if (!isset($_SESSION['email'])) {
    die("Error: Supervisor not logged in.");
}

$supervisor_email = $_SESSION['email'];

// Fetch students assigned to this supervisor
$sql = "SELECT sa.student_email, u.username, a.company_name 
        FROM supervisor_assignments sa
        JOIN users u ON sa.student_email = u.email
        LEFT JOIN applications a ON sa.student_email = a.student_email";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1' width='100%'>";
echo "<tr>
        <th>Student Name</th>
        <th>Student Email</th>
        <th>Company Applied</th>
    </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["student_email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["company_name"] ?: "Not Assigned") . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No students assigned yet.</td></tr>";
}

echo "</table>";

$stmt->close();
$conn->close();
?>