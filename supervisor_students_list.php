<?php
session_start();
require 'db_connection.php';

// Ensure supervisor is logged in
if (!isset($_SESSION['email'])) {
    die("Error: Supervisor not logged in.");
}

$supervisor_email = $_SESSION['email'];

// Correct SQL Query
$sql = "SELECT sa.student_email, u.username AS student_name, u.email, 
               IFNULL(a.company_email, 'Not Assigned') AS company_applied  -- Get company email from applications
        FROM supervisor_assignments sa
        JOIN users u ON sa.student_email = u.email
        LEFT JOIN applications a ON u.email = a.student_email  -- Fetch company email from applications table
        WHERE sa.supervisor_email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $supervisor_email);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are results
if ($result->num_rows == 0) {
    echo "<p>No students assigned yet.</p>";
} else {
    echo "<table border='1' width='100%'>";
    echo "<tr>
            <th>Student Name</th>
            <th>Student Email</th>
            <th>Company Applied</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["student_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["student_email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["company_applied"]) . "</td>";  // Shows company email now
        echo "</tr>";
    }

    echo "</table>";
}

$stmt->close();
$conn->close();
?>