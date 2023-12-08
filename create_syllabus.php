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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data with proper validation and sanitation
    $projectTitle = filter_var($_POST['project_title'], FILTER_SANITIZE_STRING);
    $totalPoints = filter_var($_POST['total_points'], FILTER_VALIDATE_INT);
    $learningOutcomes = filter_var($_POST['learning_outcomes'], FILTER_SANITIZE_STRING);
    $projectDescription = filter_var($_POST['project_description'], FILTER_SANITIZE_STRING);
    $requirements_title = json_encode(array_map('htmlspecialchars', $_POST['requirements_title']));
    $requirements = json_encode(array_map('htmlspecialchars', $_POST['requirements']));
    $numPhases = json_encode(array_map('htmlspecialchars', $_POST['num_phases']));
    $rubrics_title = json_encode(array_map('htmlspecialchars', $_POST['rubrics_title']));
    $rubrics = json_encode(array_map('htmlspecialchars', $_POST['rubrics']));
    $generatedKey = $_POST['generated_key'];



    // Check if the professorID exists in the users table
    if (isset($_SESSION['professorID'])) {
      $professorID = $_SESSION['professorID'];
        // Use prepared statement for the insert query
        $insertQuery = "INSERT INTO syllabus (project_title, total_points, learning_outcomes, project_description, requirements_title, requirements, num_phases, rubrics_title, rubrics, generated_key, professorID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
  

        // Bind parameters to the prepared statement
        $insertStmt->bind_param("ssssssssssi", $projectTitle, $totalPoints, $learningOutcomes, $projectDescription, $requirements_title, $requirements, $numPhases, $rubrics_title, $rubrics, $generatedKey, $professorID);

        if ($insertStmt->execute()) {
            // Database insertion successful
            $_SESSION['formSubmitted'] = true;
            header("Location: view_student_projects.php");
            exit;
        } else {
            // Database insertion failed
            echo "Error: " . $insertStmt->error;
            // Log the error to a file or store it in a database
        }

        $insertStmt->close();
    } else {
      // professorID is not set in the session
      echo "Error: ProfessorID is not set.";
      exit;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyA7HJN15u7ffIehXW1lPxpe2FZKbI" crossorigin="anonymous">
    <link rel="stylesheet" href="idashstyle.css">
    <link rel="stylesheet" href="ivstyle.css">
    <title>Instructor View</title>
    <script type="text/javascript" src="script.js"></script>
    <script>
    $('.nav-toggle').click(function(e) {
  
        e.preventDefault();
        $("html").toggleClass("openNav");
        $(".nav-toggle").toggleClass("active");
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
            
                <li><a href="#">Messages</a><span class="icon"><i class="fa fa-envelope"></i></span></li>
        
                </ul>
    
            </div>
        </nav>
    </div>
    
    <div class="new-wrapper">
        <div id="main">
        <div id="main-contents"> 
            <h1>Create Syllabus</h1>
            <br>

            <div class="container">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-2">
                          <label for="ptitle">Project Title</label>
                        </div>
                        <div class="col-10">
                          <input type="text" id="ptitle" name="project_title" placeholder="Project Title.." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                          <label for="tpoints">Total Points</label>
                        </div>
                        <div class="col-10">
                          <input type="number" id="tpoints" name="total_points" placeholder="# of total course points..." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                          <label for="outcomes">Learning Outcomes</label>
                        </div>
                        <div class="col-10">
                          <input type="text" id="outcomes" name="learning_outcomes" placeholder="Learning Outcomes.." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                          <label for="pdescrip">Project Description</label>
                        </div>
                        <div class="col-10">
                          <input type="text" id="pdescrip" name="project_description" placeholder="Project Description.." required>
                        </div>
                    </div><br>
                    <div class="row">
                      <div class="col-2">
                          <label for="req">Requirements</label>
                      </div>
                      <div class="col-10">
                          <table id="editableTable1">
                              <tr>
                                  <th contenteditable="true">
                                      <textarea name="requirements_title[]"></textarea>
                                  </th>
                              </tr>
                              <tr>
                                  <td contenteditable="true">
                                      <textarea name="requirements[]"></textarea>
                                  </td>
                              </tr>
                          </table><br>
                          <button type="button" onclick="addColumn1()">Add Column</button>
                          <button type="button" onclick="addRow1()">Add Row</button>       
                      </div>
                  </div><br>
                    <div class="row">
                      <div class="col-2">
                        <label for="req">Number of Phases</label>
                      </div>
                      <div class="col-10">
                        <table id="editableTable2">
                          <tr>
                            <th contenteditable="true">
                              <input type="text" name="num_phases[]" required>
                            </th>
                          </tr>
                        </table><br>
                        <button type="button" onclick="addColumn2()">Add Column</button>       
                      </div>
                    </div><br>
        
                    <div class="row">
                      <div class="col-2">
                        <label for="req">Assignment Rubrics</label>
                      </div>
                      <div class="col-10">
                        <table id="editableTable3">
                          <tr>
                            <th contenteditable="true">
                              <textarea name="rubrics_title[]" rows="4"></textarea>
                            </th>
                          </tr>
                          <tr>
                            <td contenteditable="true">
                              <textarea name="rubrics[]" rows="4"></textarea>
                            </td>
                          </tr>
                        </table><br>
                        <button type="button" onclick="addColumn3()">Add Column</button>
                        <button type="button" onclick="addRow3()">Add Row</button>       
                      </div>
                  </div><br>
                  <div class="row">
                    <div class="col-2">
                      <label for="req">Generate Key</label>
                    </div>
                  <div class="col-10">
                      <button type="button" onclick="generateKey()">Generate Key</button>
                      <input type="text" id="generatedKey" name="generated_key" readonly>
                  </div>
                  </div>
                  <div class="row">
                    <input type="submit" value="Submit" id="submitButton">
                  </div>
                    
                </form>
            </div>
        </div>
        
    
        </div>
    
    </div>
</body>
</html>

