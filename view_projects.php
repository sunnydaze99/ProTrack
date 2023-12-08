<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'config.php';

if (!isset($_SESSION['id'])) {
  echo "Error: User ID not set in the session.";
  exit;
}
$_SESSION['professorID'] = $_SESSION['id'];

// Retrieve the syllabus ID from the URL
$syllabusId = $_GET['syllabus_id'];
// Check if syllabusId is set in the URL
if ($syllabusId === null) {
    echo "<p>Syllabus ID not provided.</p>";
    exit;
}

// Fetch student information
$studentQuery = "SELECT users.name AS student_name, users.email AS student_email FROM users JOIN student_project ON users.id = student_project.student_id WHERE student_project.project_id = $syllabusId";
$studentResult = $conn->query($studentQuery);

// Check if the query executed successfully
if ($studentResult === false) {
    echo "Error executing student query: " . $conn->error;
    exit; // Stop execution if there's an error
}

// Assuming you have a prepared statement for retrieving syllabus details
$stmt = $conn->prepare("SELECT * FROM syllabus WHERE syllabus_id = ?");
if ($stmt) {    
    $stmt->bind_param("i", $syllabusId);
    if ($stmt->execute()) {
        $result = $stmt->get_result(); 
        if ($result->num_rows > 0) {
            $syllabusDetails = $result->fetch_assoc();
        } else {
            echo "<p>Syllabus not found.</p>";
        }
    } else {
        echo "<p>Error fetching syllabus details.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Failed to prepare the SQL statement.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyA7HJN15u7ffIehXW1lPxpe2FZKbI" crossorigin="anonymous">    <link rel="stylesheet" href="idashstyle.css">
    <title>Instructor View</title>
    <script type="text/javascript" src="isp.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.nav-toggle').click(function (e) {
                e.preventDefault();
                $("html").toggleClass("openNav");
                $(".nav-toggle").toggleClass("active");
            });

        });
    </script></head>
<body>
<div class="primary-nav">
        <button href="#" class="hamburger open-panel nav-toggle">
            <span class="screen-reader-text">Menu</span>
        </button>
        <nav role="navigation" class="menu">
    
            <a href="welcome.html" class="logotype">ProTrack!</a>
        
            <div class="overflow-container">
    
                <ul class="menu-dropdown">
            
                <li class="menu-hasdropdown">
                    <a href="instructordash.php" style="margin-top: 20px;">Dashboard</a>
                    <ul class="sub-menu-dropdown">
                    <li><a href="create_syllabus.php">Create Syllabus</a></li>
                        <li><a href="view_student_projects.php">Student Projects</a></li>
                        <li><a href="#">Account</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Messages</a><span class="icon"><i class="fa-thin fa-envelope" style="color: #777;"></i></span></li>
                    </ul>
                </li>    
                
                </ul>
    
            </div>
        </nav>
    </div>
    <a href="view_student_projects.php" style="position: absolute; top: 10px; left: 10px; font-size: 24px; color: black;">
    <i class="fas fa-arrow-left"></i>
</a>
    <div class="new-wrapper">
        <div id="main">
        <div id="main-contents"> 
            <!-- <h1>Project Details</h1> -->
            <h3>Project Title: <?php echo "{$syllabusDetails['project_title']}"; ?></h2>
            <p>Total Points: <?php echo"{$syllabusDetails['total_points']}";?> <br>
                Project Description: <?php echo"{$syllabusDetails['project_description']}";?><br>
                Learning Outcomes: <?php echo"{$syllabusDetails['learning_outcomes']}";?><br>
            </p>
            <h3>Requirements</h3>
            <table>
                <tr>
                    <th>Requirements Title</th>
                    <td><?php echo htmlspecialchars($syllabusDetails['requirements_title']); ?></td>
                </tr>
                <tr>
                    <th>Requirements</th>
                    <td><?php echo htmlspecialchars($syllabusDetails['requirements']); ?></td>
                </tr>
            </table>
            <h3>Phases</h3>
            <table>
                <tr>
                    <th>Phases</th>
                    <td><?php echo htmlspecialchars($syllabusDetails['num_phases']); ?></td>
                </tr>
            </table>
            <h3>Assignment Rubrics</h3>
            <table>
                <tr>
                    <th>Rubrics Title</th>
                    <td><?php echo htmlspecialchars($syllabusDetails['rubrics_title']); ?></td>
                </tr>
                <tr>
                    <th>Rubric</th>
                    <td><?php echo htmlspecialchars($syllabusDetails['rubrics']); ?></td>
                </tr>
            </table><br>
            <p>Project Key: <?php echo"{$syllabusDetails['generated_key']}"; ?></p>

            <?php
                if ($studentResult->num_rows > 0) {
                    echo "<p>Students enrolled in this syllabus:</p>";
                    // Display student information in a table or list
                    // You can customize this based on your needs
                    echo "<table>";
                    echo "<tr><th>Student Name</th><th>Student Email</th><th>Other Details</th></tr>";

                    while ($studentRow = $studentResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$studentRow['student_name']}</td>";
                        echo "<td>{$studentRow['student_email']}</td>";
                        echo "<td>Other details...</td>";  // Add other details as needed
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>No students are currently enrolled in this syllabus.</p>";
                }            
            ?>
        
        </div>
    </div>        <!-- Display other details in a similar way -->

</body>

</html>