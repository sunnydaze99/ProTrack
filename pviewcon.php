<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectTitle = $_POST["projecttitle"];
    $totalPoints = $_POST["totalpoints"];
    $learnOut = $_POST["learningoutcomes"];
    $projDesc = $_POST["projectdescription"];
    

    // Create a database connection (replace with your actual database credentials).
    $dsn = "mysql:host=localhost;dbname=protrack!";
    $username = "root";
    $password = "";

    try {
        $db = new PDO($dsn, $username, $password);

        // Insert user data into the users table.
        $sql = "INSERT INTO syllabus (projecttitle, totalpoints, learningoutcomes, projectdescription) VALUES (:title, :points, :outcome, :descrip)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':title' => $projectTitle,
            ':points' => $totalPoints,
            ':outcome' => $learnOut,
            ':descrip' => $projDesc,
        ]);

        // Redirect to a success page or perform other actions as needed.
        header("Location: instructordash.html");
        exit;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}