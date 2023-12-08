<?php
@include 'config.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'config.php';

if (!isset($_SESSION['id'])) {
  echo "Error: User ID not set in the session.";
  exit;
}
$_SESSION['student_id'] = $_SESSION['id'];


if (!isset($_GET['key'])) {
    echo "Error: Project key not provided.";
    exit;
}

$projectKey = $_GET['key'];

// Validate the project key against the database
$stmt = $conn->prepare("SELECT * FROM syllabus WHERE generated_key = ?");
if ($stmt) {
    $stmt->bind_param("s", $projectKey);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Project key is valid, get the syllabus details
            $syllabusDetails = $result->fetch_assoc();

            // Redirect to a page where you display the syllabus details to the student
            header("Location: student_projects.php?syllabus_id=" . $syllabusDetails['syllabus_id']);
            exit;
        } else {
            echo "Error: Invalid project key.";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error: Unable to prepare statement.";
}
?>