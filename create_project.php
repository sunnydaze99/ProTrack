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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyA7HJN15u7ffIehXW1lPxpe2FZKbI" crossorigin="anonymous">
    <link rel="stylesheet" href="idashstyle.css">
    <link rel="stylesheet" href="ivstyle.css">
    <title>Student View</title>
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
                    <a href="studentdash.php" style="margin-top: 20px;">Dashboard</a>
                    <ul class="sub-menu-dropdown">
                        <li><a href="student_projects.php">Projects</a></li>
                        <li><a href="create_gantt_chart.html">Gantt</a></li>
                        <li><a href="#">Account</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Messages</a><span class="icon"><i class="fa-thin fa-envelope" style="color: #777;"></i></span></li>
                </ul>
    
            </div>
        </nav>
    </div>
    
    <div class="new-wrapper">
        <div id="main">
        <div id="main-contents"> 
            <h1>Create Project</h1>
            <br>
            <div class="container">
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