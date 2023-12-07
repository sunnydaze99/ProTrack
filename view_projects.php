<?php
// Retrieve the project ID from the URL
$projectId = $_GET['id'];

@include 'config.php';

// Check if project_id is set in the URL
if ($stmt) {
    $stmt->bind_param("i", $projectId);

    if ($stmt->execute()) {
        $result = $stmt->get_result(); 
        if ($result->num_rows > 0) {
            $projectDetails = $result->fetch_assoc();
            $decodedRequirementsTitle = json_decode($requirements_title, true);
            $decodedRequirements = json_decode($requirements, true);
            $decodedNumPhases = json_decode($numPhases, true);
            $decodedRubricsTitle = json_decode($rubrics_title, true);
            $decodedRubrics = json_decode($rubrics, true);

            // Display syllabus details (you can customize this based on your structure)
            echo "<h1>{$syllabus['project_title']}</h1>";
            echo "<p>{$syllabus['total_points']}</p>";
            echo "<p>{$syllabus['learning_outcomes']}</p>";
            echo "<p>{$syllabus['project_description']}</p>";

            // ... Display other syllabus details ...
            echo "Decoded Requirements Title: ";
            print_r($decodedRequirementsTitle);
            echo "Decoded Requirements: ";
            print_r($decodedRequirements);
            echo "Decoded Num Phases: ";
            print_r($decodedNumPhases);
            echo "Decoded Rubrics Title: ";
            print_r($decodedRubricsTitle);
            echo "Decoded Rubrics: ";
            print_r($decodedRubrics);
            echo "<p>{$syllabus['generated_key']}</p>";

            // Check for students under this project
            $studentCount = 0;  // You should fetch this count from your database
            if ($studentCount > 0) {
                echo "<p>Students enrolled in this project:</p>";
                // Display student information (you need to fetch this from your database)
            } else {
                echo "<p>No students are currently enrolled in this project.</p>";
            }
        } else {
            echo "<p>Project not found.</p>";
        }
    } else {
        echo "<p>Error fetching project details.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Project ID not provided.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (rest of the head section) ... -->
</head>
<body>
    <h1>Project Details</h1>

    <h2>Project Title: <?php echo htmlspecialchars($projectDetails['project_title']); ?></h2>
    <p>Total Points: <?php echo htmlspecialchars($projectDetails['total_points']); ?>
        Project Description: <?php echo htmlspecialchars($projectDetails['project_description']); ?>
        Learning Outcomes: <?php echo htmlspecialchars($projectDetails['learning_outcomes']); ?>
    </p>
    <h2>Requirements</h2>
    <table>
        <tr>
            <th>Requirements Title</th>
            <td><?php echo htmlspecialchars($projectDetails['requirements_title']); ?></td>
        </tr>
        <tr>
            <th>Requirements</th>
            <td><?php echo htmlspecialchars($projectDetails['requirements']); ?></td>
        </tr>
    </table>
    <h2>Phases</h2>
    <table>
        <tr>
            <th>Phases</th>
            <td><?php echo htmlspecialchars($projectDetails['num_phases']); ?></td>
        </tr>
    </table>
    <h2>Assignment Rubrics</h2>
    <table>
        <tr>
            <th>Rubrics Title</th>
            <td><?php echo htmlspecialchars($projectDetails['rubrics_title']); ?></td>
        </tr>
        <tr>
            <th>Rubric</th>
            <td><?php echo htmlspecialchars($projectDetails['rubrics']); ?></td>
        </tr>
    </table>
    <p>Generate a Key: <?php echo htmlspecialchars($projectDetails['generate_key']); ?></p>


    <!-- Display other details in a similar way -->

</body>

</html>