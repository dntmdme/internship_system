<?php
require 'db_connection.php'; // Connect to database

$sql = "SELECT * FROM users WHERE role = 'supervisor'";
$result = $conn->query($sql);

echo "<table border='1' width='100%'>";
echo "<tr><th>Username</th><th>Email</th><th>Assign to Student</th></tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "<td>
                <form action='assign_supervisor.php' method='POST'>
                    <input type='hidden' name='supervisor_email' value='" . htmlspecialchars($row["email"]) . "'>
                    <input type='email' name='student_email' placeholder='Enter student email' required>
                    <button type='submit'>Assign</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No supervisors registered yet.</td></tr>";
}

echo "</table>";
$conn->close();
?>