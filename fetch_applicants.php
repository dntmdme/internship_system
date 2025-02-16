<?php
$company_email = $_SESSION['user_email'];

$conn = new mysqli("localhost", "root", "", "internship_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT student_email FROM applications WHERE company_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $company_email);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<option value='" . htmlspecialchars($row['student_email']) . "'>" . htmlspecialchars($row['student_email']) . "</option>";
}

$stmt->close();
$conn->close();
?>
