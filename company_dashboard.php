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

// Ensure company is logged in
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "company") {
    die("Access denied.");
}

// Get logged-in company email
$company_email = $_SESSION["email"];

// Fetch applications for this company
$sql = "SELECT student_name, student_email, cv_file FROM applications WHERE company_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $company_email);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1'>";
echo "<tr><th>Student Name</th><th>Email</th><th>CV</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row["student_name"]) . "</td>
            <td>" . htmlspecialchars($row["student_email"]) . "</td>
            <td><a href='" . htmlspecialchars($row["cv_file"]) . "' download>Download CV</a></td>
          </tr>";
}
echo "</table>";

$stmt->close();
$conn->close();
?>