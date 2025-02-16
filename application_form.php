<?php
// Database connection
$servername = "localhost";
$username = "root";  // Change if using a different MySQL user
$password = "";      // Change if a password is set
$database = "internship_system";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST["student_name"];
    $email = $_POST["email"];
    $course = $_POST["course"];
    $position = $_POST["position"];

    // Handle file upload
    $cv = $_FILES["cv"];
    $cv_name = $cv["name"];
    $cv_tmp_name = $cv["tmp_name"];
    $cv_size = $cv["size"];
    $cv_error = $cv["error"];

    // Ensure CV is a PDF
    $cv_ext = pathinfo($cv_name, PATHINFO_EXTENSION);
    if (strtolower($cv_ext) !== "pdf") {
        die("Error: Only PDF files are allowed.");
    }

    // Move the uploaded file
    $cv_new_name = uniqid("CV_", true) . "." . $cv_ext;
    $cv_destination = "uploads/" . $cv_new_name;
    if (!move_uploaded_file($cv_tmp_name, $cv_destination)) {
        die("Error uploading file.");
    }

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO applications (student_name, email, course, position, cv_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $student_name, $email, $course, $position, $cv_destination);

    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
}

$conn->close();
?>