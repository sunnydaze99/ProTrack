<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'config.php';

if (!isset($_SESSION['id'])) {
    echo "Error: User ID not set in the session.";
    exit;
}
$studentID = $_SESSION['id'];

// Use prepared statement to avoid SQL injection
$sql = "SELECT project_plans.*, COUNT(student_project.student_id) AS student_count 
        FROM project_plans 
        LEFT JOIN student_project ON project_plans.id = student_project.project_plan_id 
        WHERE project_plans.professor_id = ? 
        GROUP BY project_plans.id";
// Prepare the statement
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind the parameter
    $stmt->bind_param("i", $_SESSION['id']);

    // Execute the statement
    $result = $stmt->execute();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="idashstyle.css">
    <style>
        .card-container {
            text-align: center;
        }
        .card {
            display: inline-block;
            width: 250px; /* Set a fixed width for each card */
            margin: 0 10px; /* Add some margin between cards */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card-title {

            font-size: 1.5em;
            margin: 10px;
        }

        .card-text {
            color: #555;
            height: 100px;
            margin: 15px;
        }

        .card-button {
            display: block;
            width: 100%;
            padding: 10px;
            text-align: center;
            background-color: #ff90e8;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .card-button:hover {
            background-color: #ff90e8;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
    </style>
    <title>Student View</title>
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
    </script>
</head>
<body>
    <div class="primary-nav">
        <button href="" class="hamburger open-panel nav-toggle"></button>
        <nav role="navigation" class="menu" id="menu-dropdown">
    
            <a href="welcome.html" class="logotype">ProTrack!</a>
        
            <div class="overflow-container">
    
                <ul class="menu-dropdown">
            
                <li class="menu-hasdropdown">
                    <a href="studentdash.php" style="margin-top: 20px;">Dashboard</a>
                    <ul class="sub-menu-dropdown">
                        <li><a href="student_projects.php">Projects</a></li>
                        <li><a href="create_gantt_chart.html">Gantt</a></li>
                        <li><a href="#">Account</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Messages</a><span class="icon"><i class="fa-thin fa-envelope" style="color: #777;"></i></span></li>
                    </ul>
                </li>        
                </ul>
    
            </div>
        </nav>
    </div>
    <div class="new-wrapper">
        <div id="main">
            <div id="main-contents">
                <h1>Your Projects</h1>
                <?php
                    if ($result) {
                        // Get the result set
                        $result = $stmt->get_result();

                        // Output project plan cards within the main contents section
                        echo '<div id="main-contents">';
                        while ($row = $result->fetch_assoc()) {
                            $projectPlanId = $row['id'];
                            $teamNumber = $row['team_number'];
                            $meetingTime = $row['meeting_time'];
                            $meetingPlace = $row['meeting_place'];
                            $studentCount = $row['student_count'];

                            // Output project plan card
                            echo "<div class='card'>";
                            echo "<h2 class='card-title'>Team $teamNumber</h2>";
                            echo "<p class='card-text'>Meeting Time: $meetingTime</p>";
                            echo "<p class='card-text'>Meeting Place: $meetingPlace</p>";
                            echo "<p>Student Count: $studentCount</p>";  // Display the student count
                            echo "<a href='view_project_plan.php?project_plan_id=$projectPlanId' class='card-button'>View Project Plan</a>";
                            echo "</div>";
                        }
                        echo '</div>';
                    } else {
                        // Handle the case where the query execution failed
                        echo "Error executing query: " . $stmt->error;
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    // Handle the case where the statement preparation failed
                    echo "Error preparing statement: " . $conn->error;
                }

                // Close the connection
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>
