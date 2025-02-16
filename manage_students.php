<?php
session_start();
require 'db_connection.php'; // Ensure this file exists

if (!isset($_SESSION['email'])) {
    die("Error: User not logged in.");
}

// Fetch all students from the users table (assuming role = 'student')
$sql = "SELECT username, email FROM users WHERE role = 'student'";
$result = $conn->query($sql);

echo "<table border='1' width='100%'>";
echo "<tr>
        <th>Student Name</th>
        <th>Email</th>
      </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>No students registered.</td></tr>";
}
