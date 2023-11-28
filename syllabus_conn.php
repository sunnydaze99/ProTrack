<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a database connection (replace with your actual database credentials).
    $hostname = "localhost";
    $username = "root";
	$password = "";
    $database = "protrack!";

    $conn = new mysqli($hostname, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    // Retrieve form data
    $projectTitle = $_POST['project_title'];
    $totalPoints = $_POST['total_points'];
    $learningOutcomes = $_POST['learning_outcomes'];
    $projectDescription = $_POST['project_description'];
    $requirements_title = json_encode($_POST['requirements_title']); // Assuming requirements is an array
    $requirements = json_encode($_POST['requirements']);
    $numPhases = json_encode($_POST['num_phases']);
    $rubrics_title = json_encode($_POST['rubrics_title']);
    $rubrics = json_encode($_POST['rubrics']);
    $generatedKey = $_POST['generated_key'];

    
    // Insert data into the syllabus table
    $sql = "INSERT INTO syllabus (project_title, total_points, learning_outcomes, project_description, requirements_title, requirements, num_phases, rubrics_title, rubrics, generated_key)
            VALUES ('$projectTitle', '$totalPoints', '$learningOutcomes', '$projectDescription', '$requirements_title','$requirements', '$numPhases', '$rubrics_title','$rubrics', '$generatedKey')";

    if ($conn->query($sql) === TRUE) {
        echo "Syllabus created successfully";
        header("Location: student_projects.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
        
}
?>