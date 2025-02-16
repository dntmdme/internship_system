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

// Ensure the user is logged in as a company
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "company") {
    die("Unauthorized access");
}

$company_email = $_SESSION["email"]; // Fetching logged-in company email

// Fetch applications for this company
$sql = "SELECT student_email, cv_file, created_at FROM applications WHERE company_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $company_email);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1' width='100%'>
        <tr>
            <th>Student Email</th>
            <th>CV File</th>
            <th>Applied On</th>
        </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["student_email"]) . "</td>
                <td><a href='" . htmlspecialchars($row["cv_file"]) . "' download>Download CV</a></td>
                <td>" . htmlspecialchars($row["created_at"]) . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3'>No applications found.</td></tr>";
}

echo "</table>";

$stmt->close();
$conn->close();
?>