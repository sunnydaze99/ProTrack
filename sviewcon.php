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
        
    // Retrieve the list of professors from the database
    $sql = "SELECT professorId, professorName FROM pusers";
    $result = $conn->query($sql);

    // Check if there are professors
    if ($result->num_rows > 0) {
        // Output data for each professor as an option in the dropdown
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row["professorId"]}'>{$row["professorName"]}</option>";
        }
    } else {
        echo "<option value=''>Professor could not be found, make sure they are registered with ProTrack!</option>";
    }

    // Continue with the rest of the script...

    // Retrieve form data
    $teamNumber = $_POST['team_number'];
    $meetingTime = $_POST['meeting_time'];
    $meetingPlace = $_POST['meeting_place'];
    $professorName = $_POST['professorName'];

    // Insert data into the project_plans table 
    $sql = "INSERT INTO project_plans (team_number, meeting_time, meeting_place, professorName)
            VALUES ('$teamNumber', '$meetingTime', '$meetingPlace', '$professorName')";

    if ($conn->query($sql) === TRUE) {
        $planId = $conn->insert_id;

        // Insert team members data into the team_members table
        $membersData = $_POST['members'];
        foreach ($membersData as $member) {
            $name = $member['name'];
            $studentId = $member['student_id'];
            $contact = $member['contact'];
            $description = $member['description'];

            $sql = "INSERT INTO team_members (plan_id, name, student_id, contact, description)
                    VALUES ($planId, '$name', '$studentId', '$contact', '$description')";
            $conn->query($sql);
        }

        // Insert deliverables data into the deliverables table
        $deliverablesData = $_POST['deliverables'];
        foreach ($deliverablesData as $deliverable) {
            $task = $deliverable['task'];
            $item = $deliverable['item'];
            $phase = $deliverable['phase'];
            $memberResponsible = $deliverable['member_responsible'];
            $mode = $deliverable['mode'];
            $comment = $deliverable['comment'];

            $sql = "INSERT INTO deliverables (plan_id, task, item, phase, member_responsible, mode, comment)
                    VALUES ($planId, '$task', '$item', '$phase', '$memberResponsible', '$mode', '$comment')";
            $conn->query($sql);
        }

        echo "Project plan created successfully";
        header("Location: studentdash.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}