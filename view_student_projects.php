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

// Use prepared statement to avoid SQL injection
$sql = "SELECT syllabus.*, COUNT(student_project.student_id) AS student_count FROM syllabus LEFT JOIN student_project ON syllabus.syllabus_id = student_project.project_id WHERE syllabus.professorID = ? GROUP BY syllabus.syllabus_id";

// Prepare the statement
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind the parameter
    $stmt->bind_param("i", $_SESSION['id']);

    // Execute the statement
    $result = $stmt->execute();

//     if ($result) {
//         // Get the result set
//         $result = $stmt->get_result();

//         // Output syllabus cards within the main contents section
//         echo '<div id="main-contents">';
//         while ($row = $result->fetch_assoc()) {
//             $syllabusId = $row['syllabus_id'];
//             $projectTitle = $row['project_title'];
//             $projectDescription = $row['project_description'];
//             $studentCount = $row['student_count'];

//             // Output syllabus card
//             echo "<div class='card'>";
//             echo "<h2 class='card-title'>$projectTitle</h2>";
//             echo "<p class='card-text'>$projectDescription</p>";
//             echo "<p>Student Count: $studentCount</p>";  // Display the student count
//             echo "<a href='view_projects.php?syllabus_id=$syllabusId' class='card-button'>View Project</a>";
//             echo "</div>";
//         }
//         echo '</div>';
//     } else {
//         // Handle the case where the query execution failed
//         echo "Error executing query: " . $stmt->error;
//     }

//     // Close the statement
//     $stmt->close();
// } else {
//     // Handle the case where the statement preparation failed
//     echo "Error preparing statement: " . $conn->error;
// }

// // Close the connection
// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyA7HJN15u7ffIehXW1lPxpe2FZKbI" crossorigin="anonymous">
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

            // Object to store class information
            var classData = {};

            // Function to handle the "Add Class" button click
            $('#addClassButton').click(function () {
                // Prompt the user to enter the class name
                var className = prompt("Enter the class name:");

                // Check if the user entered a class name
                if (className !== null && className.trim() !== "") {
                    // Generate a unique ID for the class (in this example, using the current timestamp)
                    var classId = "class_" + Date.now();

                    // Add class information to the object
                    classData[classId] = {
                        name: className,
                        details: "Class details go here."
                    };

                    // Create a new list item for the class with rounded square styling
                    var newClassItem = $('<li>').text(className).attr('id', classId).addClass('rounded-square');

                    // Append the new class item to the list
                    $('#classList').append(newClassItem);

                    // Add click event to redirect to the class details page
                    newClassItem.click(function () {
                        // Redirect to the class details page
                        window.location.href = 'class_page.html?classId=' + classId;
                    });

                    // Optionally, you can add more styling or functionality here

                    // Alert with the added class information (you can remove this if not needed)
                    alert("Class added!\nClass Name: " + className + "\nClass ID: " + classId);
                } else {
                    alert("Class name cannot be empty. Please try again.");
                }
            });
        });
    </script>
</head>
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
    <div class="new-wrapper">
        <div id="main">
        <div id="main-contents"> 
            <h1 style="padding-bottom: 20px;">Student Projects</h1>   
            <?php
                if ($result) {
                    // Get the result set
                    $result = $stmt->get_result();

                    // Output syllabus cards within the main contents section
                    echo '<div id="main-contents">';
                    while ($row = $result->fetch_assoc()) {
                        $syllabusId = $row['syllabus_id'];
                        $projectTitle = $row['project_title'];
                        $projectDescription = $row['project_description'];
                        $studentCount = $row['student_count'];

                        // Output syllabus card
                        echo "<div class='card'>";
                        echo "<h2 class='card-title'>$projectTitle</h2>";
                        echo "<p class='card-text'>$projectDescription</p>";
                        echo "<p>Student Count: $studentCount</p>";  // Display the student count
                        echo "<a href='view_projects.php?syllabus_id=$syllabusId' class='card-button'>View Project</a>";
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
</body>
</html>