<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "internship_system";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "student") {
    die("Unauthorized access.");
}

$student_email = $_SESSION["email"];
$sql = "SELECT company_email, comments, performance_rating, application_status, feedback_file FROM feedback WHERE student_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1'width='100%'>";
echo "<tr>
        <th>Company</th>
        <th>Comments</th>
        <th>Rating</th>
        <th>Status</th>
        <th>Report</th>
    </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["company_email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["comments"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["performance_rating"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["application_status"]) . "</td>";
        
        if (!empty($row["feedback_file"])) {
            echo "<td><a href='" . htmlspecialchars($row["feedback_file"]) . "' download>Download</a></td>";
        } else {
            echo "<td>No Report</td>";
        }
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No feedback yet.</td></tr>";
}

echo "</table>";

$stmt->close();
$conn->close();
?>