<?php
require 'db_connection.php'; // Connect to database

$sql = "SELECT * FROM users WHERE role = 'company'";
$result = $conn->query($sql);


echo "<table border='1' width='100%'>";
echo "<tr><th>Username</th><th>Email</th></tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>No companies registered yet.</td></tr>";
}

echo "</table>";
$conn->close();
?>