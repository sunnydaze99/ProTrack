<?php
@include 'config.php';

session_start();

// Assuming you have stored professor ID during login
$studentID = $_SESSION['id'];

// Check if professor ID is set
if (empty($studentID)) {
    die("Error: Professor ID not set.");
}

// Fetch list of professors from the database
$professorsQuery = "SELECT id, name FROM users WHERE user_type = 'instructor'";
$professorsResult = $conn->query($professorsQuery);
$professors = $professorsResult->fetch_all(MYSQLI_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $teamNumber = $_POST['team_number'];
    $professorID = $_POST['professor_id'];
    $meetingTime = $_POST['meeting_time'];
    $meetingPlace = $_POST['meeting_place'];

    // Insert into project_plan table
    $insertProjectPlanQuery = "INSERT INTO project_plans(team_number, professor_id, meeting_time, meeting_place) VALUES (?, ?, ?, ?)";
    $insertProjectPlanStmt = $conn->prepare($insertProjectPlanQuery);
    if (!$insertProjectPlanStmt) {
        die('Error in preparing the insert statement: ' . $conn->error);
    }
    $insertProjectPlanStmt->bind_param("siss", $teamNumber, $professorID, $meetingTime, $meetingPlace);

    if ($insertProjectPlanStmt->execute()) {
        $projectPlanID = $conn->insert_id; // Get the auto-generated project_plan_id

        // Insert into team_members table
        foreach ($_POST['team_members'] as $teamMember) {
            $insertTeamMemberQuery = "INSERT INTO team_members (project_id, name, student_id, contact, description) VALUES (?, ?, ?, ?, ?)";
            $insertTeamMemberStmt = $conn->prepare($insertTeamMemberQuery);
            $insertTeamMemberStmt->bind_param("isiss", $projectPlanID, $teamMember['name'], $teamMember['student_id'], $teamMember['contact'], $teamMember['description']);
            $insertTeamMemberStmt->execute();
        }

        // Insert into deliverables table
        foreach ($_POST['deliverables'] as $deliverable) {
            $insertDeliverableQuery = "INSERT INTO deliverables (project_id, task, item, phase, member_responsible, mode, comment) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertDeliverableStmt = $conn->prepare($insertDeliverableQuery);
            $insertDeliverableStmt->bind_param("issssss", $projectPlanID, $deliverable['task'], $deliverable['item'], $deliverable['phase'], $deliverable['member_responsible'], $deliverable['mode'], $deliverable['comment']);
            $insertDeliverableStmt->execute();
        }

         // Insert into student_project table
         $insertStudentProjectQuery = "INSERT INTO student_project (student_id, project_id, project_plan_id) VALUES (?, ?, ?)";
         $insertStudentProjectStmt = $conn->prepare($insertStudentProjectQuery);
         $insertStudentProjectStmt->bind_param("iii", $studentID, $projectPlanID, $projectPlanID);
         $insertStudentProjectStmt->execute();

        echo "Project Plan created successfully";
        header("Location: studentdash.php");
        exit;
    } else {
        echo "Error: " . $insertProjectPlanStmt->error;
    }

    $insertProjectPlanStmt->close();
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

        document.addEventListener('DOMContentLoaded', function () {
    const professorDropdown = document.getElementById('professor_id');
    const professorFilter = document.getElementById('professor_filter');

    // Create an array to store original professor options
    const originalProfessors = Array.from(professorDropdown.options);

    // Add event listener for input changes
    professorFilter.addEventListener('input', function () {
        const filterText = this.value.toUpperCase();

        // Clear existing options
        professorDropdown.innerHTML = '';

        // Filter and add matching options
        originalProfessors.forEach(function (professor) {
            const optionText = professor.text.toUpperCase();
            if (optionText.includes(filterText)) {
                professorDropdown.add(professor);
            }
        });
    });
});

        
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
            
            <!-- Professor Filter -->
            <div class="dropdown-wrapper">
                <label for="professor_filter">Filter Professors:</label>
                <input type="text" id="professor_filter" placeholder="Type to filter professors">
            </div>

            <label for="professor_id">Select Professor:</label>
            <select id="professor_id" name="professor_id" required>
                <?php foreach ($professors as $professor): ?>
                    <option value="<?= $professor['id']; ?>"><?= $professor['name']; ?></option>
                <?php endforeach; ?>
            </select>

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
                    <input type="text" id="item_1" name="item_1">
                    <label for="phase_1">Phase:</label>
                    <input type="text" id="phase_1" name="phase_1">
                    
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