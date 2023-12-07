<?php
@include 'config.php';

session_start();

// Check if the student is logged in
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Redirect to login or handle accordingly
    header('Location: login.php');
    exit;
}

// Extract student's ID
$studentID = $_SESSION['id'];

// Fetch list of professors from the database
$professorsQuery = "SELECT id, name FROM users WHERE user_type = 'instructor'";
$professorsResult = $conn->query($professorsQuery);
$professors = $professorsResult->fetch_all(MYSQLI_ASSOC);

// Fetch list of students from the database
$studentsQuery = "SELECT id, name FROM users WHERE user_type = 'student'";
$studentsResult = $conn->query($studentsQuery);
$students = $studentsResult->fetch_all(MYSQLI_ASSOC);

// Fetch list of students associated with the current project
$projectStudentsQuery = "SELECT u.id, u.name FROM users u
                         JOIN team_members tm ON u.id = tm.student_id
                         WHERE tm.project_id = ?";

$projectStudentsStmt = $conn->prepare($projectStudentsQuery);
$projectStudentsStmt->bind_param("i", $projectPlanID);
$projectStudentsStmt->execute();
$projectStudentsResult = $projectStudentsStmt->get_result();
$projectStudents = $projectStudentsResult->fetch_all(MYSQLI_ASSOC);


//debug
//echo "Student ID: " . $studentID;

//project plan submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $teamNumber = $_POST['team_number'];
    $professorID = $_POST['professor_id'];

    // Insert into project_plan table
    $insertQuery = "INSERT INTO project_plans(team_number, professor_id) VALUES (?, ?)";
    //debug
    echo $insertQuery;
    $insertStmt = $conn->prepare($insertQuery);
    if (!$insertStmt) {
        die('Error in preparing the insert statement: ' . $conn->error);
    }
    $insertStmt->bind_param("si", $teamNumber, $professorID);

    if ($insertStmt->execute()) {
        $projectPlanID = $conn->insert_id; // Get the auto-generated project_plan_id

        // Insert into team_members table
        for ($i = 1; isset($_POST['name' . $i]); $i++) {
            $name = $_POST['name' . $i];
            $studentID = $_POST['student_id' . $i];
            $contact = $_POST['contact' . $i];
            $description = $_POST['description' . $i];

            $insertTeamMemberQuery = "INSERT INTO team_members (project_plan_id, name, student_id, contact, description) VALUES (?, ?, ?, ?, ?)";
            $insertTeamMemberStmt = $conn->prepare($insertTeamMemberQuery);
            $insertTeamMemberStmt->bind_param("isiss", $projectPlanID, $name, $studentID, $contact, $description);
            $insertTeamMemberStmt->execute();
        }

        // Insert into deliverables table
        for ($i = 1; isset($_POST['task' . $i]); $i++) {
            $task = $_POST['task' . $i];
            $item = $_POST['item' . $i];
            $phase = $_POST['phase' . $i];
            $memberResponsible = $_POST['member_responsible' . $i];
            $mode = $_POST['mode' . $i];
            $comment = $_POST['comment' . $i];

            $insertDeliverableQuery = "INSERT INTO deliverables (project_plan_id, task, item, phase, member_responsible, mode, comment) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertDeliverableStmt = $conn->prepare($insertDeliverableQuery);
            $insertDeliverableStmt->bind_param("issssss", $projectPlanID, $task, $item, $phase, $memberResponsible, $mode, $comment);
            $insertDeliverableStmt->execute();
        }

        echo "Project Plan created successfully";
        header("Location: studentdash.php");
        exit;
    } else {
        echo "Error: " . $insertStmt->error;
    }

    $insertStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 80%; /* Set to a percentage of the viewport width */
            max-width: 1000px; /* Optional maximum width */
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-top: 20px;
        }

        label, input, textarea {
            display: block;
            margin: 10px 0;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #ff3333;
        }
        /* Style for the dropdown arrow */
        .dropdown-wrapper {
            position: relative;
            display: inline-block;
        }

        .dropdown-wrapper input {
            padding-right: 20px; /* Adjust the padding as needed */
        }

        .dropdown-wrapper::after {
            content: "\25BC"; /* Unicode character for a downward-pointing triangle */
            font-size: 12px; /* Adjust the font size as needed */
            position: absolute;
            top: 50%;
            right: 5px; /* Adjust the right position as needed */
            transform: translateY(-50%);
            pointer-events: none; /* Ensures the arrow doesn't interfere with text input */
        }
    </style>
    <script>
        function addRow(sectionId) {
            const section = document.getElementById(sectionId);
            const newRow = document.querySelector(`.${sectionId}-row`).cloneNode(true);
            newRow.className = `${sectionId}-row`;
            newRow.querySelectorAll('input[type="text"], textarea').forEach(input => {
                input.value = ''; // Clear input values in the cloned row
            });

            // Add a delete button
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.classList.add("delete-button");
            deleteButton.onclick = function () {
                deleteRow(this, sectionId);
            };
            newRow.appendChild(deleteButton);

            section.appendChild(newRow);
        }

        function deleteRow(button, sectionId) {
            const row = button.parentNode;
            const section = document.getElementById(sectionId);
            section.removeChild(row);
        }

        // Your JavaScript code for professor dropdown filtering
        // document.addEventListener('DOMContentLoaded', function () {
        //     document.getElementById('professor_filter').addEventListener('input', function() {
        //         var filterText = this.value.toUpperCase();
        //         var select = document.getElementById('professor_id');
        //         var options = select.getElementsByTagName('option');

        //         for (var i = 0; i < options.length; i++) {
        //             var optionText = options[i].text.toUpperCase();
        //             if (optionText.includes(filterText)) {
        //                 options[i].style.display = '';
        //             } else {
        //                 options[i].style.display = 'none';
        //             }
        //         }
        //     });
        // });
        
    </script>
</head>
<body>
    <div class="container">
        <h1>Project Plan</h1>
        <form action="" method="post">
            <!-- Team Information -->
            <h2>Team Information</h2>
            <label for="team_number">Team Number:</label>
            <input type="text" id="team_number" name="team_number" required placeholder="Assigned by your instructor">
            
            <!-- <div class="dropdown-wrapper"> -->
                <label for="professor_id">Select Professor:</label>
                <!-- <input type="text" id="professor_filter" placeholder="Filter Professors"> -->
                <select id="professor_id" name="professor_id" required>
                    <?php foreach ($professors as $professor): ?>
                        <option value="<?= $professor['id']; ?>"><?= $professor['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            <!-- </div> -->
            <!-- <input type="text" id="professor_id" name="professor_id" required placeholder="Type in you professor's name"> -->

            <!-- Team Members -->
            <h2>Team Members</h2>
            <div id="members">
                <div class="members-row">
                    <label for="name_1">Name:</label>
                    <input type="text" id="name_1" name="name_1"  placeholder="First and Last Name">
                    
                    <label for="student_id_1">Select Student:</label>
                    <select id="student_id_1" name="student_id_1" >
                        <?php foreach ($students as $student): ?>
                            <?php if ($student['id'] != $studentID): ?>
                                <option value="<?= $student['id']; ?>"><?= $student['name']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" id="student_id_1" name="student_id_1" required placeholder="Type in a student's ID"> -->
                    
                    <label for="contact_1">Contact:</label>
                    <input type="text" id="contact_1" name="contact_1" placeholder="Any contact info">
                    <label for="description_1">Description:</label>
                    <textarea id="description_1" name="description_1" rows="4"></textarea>
                    <button type="button" onclick="addRow('members')">Add Member</button>
                </div>
            </div>

            <!-- Meeting Time -->
            <h2>Meeting Time</h2>
            <label for="meeting_time">Meeting Time:</label>
            <input type="text" id="meeting_time" name="meeting_time">

            <!-- Meeting Place -->
            <h2>Meeting Place</h2>
            <label for="meeting_place">Meeting Place:</label>
            <input type="text" id="meeting_place" name="meeting_place">

            <!-- Deliverables -->
            <h2>Deliverables</h2>
            <div id="deliverables">
                <div class="deliverables-row">
                    <label for="task_1">Task:</label>
                    <input type="text" id="task_1" name="task_1">
                    <label for="item_1">Item:</label>
                    <input type="text" id="item_1" name="item_1" >
                    <label for="phase_1">Phase:</label>
                    <input type="text" id="phase_1" name="phase_1" >
                    

                    <label for="member_responsible_1">Member Responsible:</label>
                        <select id="member_responsible_1" name="member_responsible_1">
                            <?php foreach ($projectStudents as $student): ?>
                                <option value="<?= $student['id']; ?>"><?= $student['name']; ?></option>
                            <?php endforeach; ?>
                        </select>   

                    <label for="mode_1">Mode:</label>
                    <input type="text" id="mode_1" name="mode_1">
                    <label for="comment_1">Comment:</label>
                    <textarea id="comment_1" name="comment_1" rows="4"></textarea>
                    <button type="button" onclick="addRow('deliverables')">Add Deliverable</button>
                </div>
            </div>

            <!-- Submit Button -->
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>