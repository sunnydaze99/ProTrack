<?php
@include 'config.php';

session_start();

// Assuming you have stored professor ID during login
$professorID = $_SESSION['id'];

// Check if professor ID is set
if (empty($professorID)) {
    die("Error: Professor ID not set.");
}
//echo "Professor ID: " . $professorID;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $projectTitle = filter_var($_POST['project_title'], FILTER_SANITIZE_STRING);
    $totalPoints = $_POST['total_points'];
    $learningOutcomes = $_POST['learning_outcomes'];
    $projectDescription = $_POST['project_description'];
    $requirements_title = json_encode($_POST['requirements_title']); // Assuming requirements is an array
    $requirements = json_encode($_POST['requirements']);
    $numPhases = json_encode($_POST['num_phases']);
    $rubrics_title = json_encode($_POST['rubrics_title']);
    $rubrics = json_encode($_POST['rubrics']);
    $generatedKey = $_POST['generated_key'];

    
    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO syllabus (project_title, total_points, learning_outcomes, project_description, requirements_title, requirements, num_phases, rubrics_title, rubrics, generated_key, professorID)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error in query preparation: " . $conn->error);
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("ssssssssssi", $projectTitle, $totalPoints, $learningOutcomes, $projectDescription, $requirements_title, $requirements, $numPhases, $rubrics_title, $rubrics, $generatedKey, $professorID);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Syllabus created successfully";
        header("Location: instructordash.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
        
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyA7HJN15u7ffIehXW1lPxpe2FZKbI" crossorigin="anonymous">
    <link rel="stylesheet" href="ivstyle.css">
    <title>Instructor View</title>
    <script type="text/javascript" src="script.js"></script>
</head>

<body>
    <h1 style="text-align: center;">Syllabus</h1><br>
    <p style="margin-left: 40px;">Hello Course Instructor! Please fill out your course requirements below to create a syllabus.</p>
    
    <div class="container">
        <form action="" method="post">
            <div class="row">
                <div class="col-2">
                  <label for="ptitle">Project Title</label>
                </div>
                <div class="col-10">
                  <input type="text" id="ptitle" name="project_title" placeholder="Project Title..">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                  <label for="tpoints">Total Points</label>
                </div>
                <div class="col-10">
                  <input type="number" id="tpoints" name="total_points" placeholder="# of total course points...">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                  <label for="outcomes">Learning Outcomes</label>
                </div>
                <div class="col-10">
                  <input type="text" id="outcomes" name="learning_outcomes" placeholder="Learning Outcomes..">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                  <label for="pdescrip">Project Description</label>
                </div>
                <div class="col-10">
                  <input type="text" id="pdescrip" name="project_description" placeholder="Project Description..">
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
                        <textarea name="requirements_title[]" ></textarea>
                      </th>
                    </tr>
                    <tr>
                      <td contenteditable="true">
                        <textarea name="requirements[]" ></textarea>
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
                      <input type="text" name="num_phases[]">
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
  </body>
</html>