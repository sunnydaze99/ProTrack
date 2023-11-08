<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $teamNum = $_POST["team_number"];
    // $meetTime = $_POST["meeting_time"];
    

    // Create a database connection (replace with your actual database credentials).
    $dsn = "mysql:host=localhost;dbname=protrack!";
    $username = "root";
    $password = "";

    try {
        $db = new PDO($dsn, $username, $password);

        // // Insert user data into the users table.
        // $sql = "INSERT INTO project plan (team_number, meeting_time) VALUES (:num, :timee)";
        // $stmt = $db->prepare($sql);
        // $stmt->execute([
        //     ':num' => $teamNum,
        //     ':timee' => $meetTime,
        // ]);

        // Redirect to a success page or perform other actions as needed.
        header("Location: studentdash.html");
        exit;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}