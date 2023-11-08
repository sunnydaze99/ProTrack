<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentName = $_POST["professorName"];
    $studentEmail = $_POST["professorEmail"];
    $studentPassword = $_POST["professorPassword"];
    // Hash the password for security.
    $hashedPassword = password_hash($professorPassword, PASSWORD_DEFAULT);

    // Create a database connection (replace with your actual database credentials).
    $dsn = "mysql:host=localhost;dbname=protrack!";
    $username = "root";
    $password = "";

    try {
        $db = new PDO($dsn, $username, $password);

        // Insert user data into the users table.
        $sql = "INSERT INTO pusers (professorName, professorEmail, professorPassword) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':name' => $studentName,
            ':email' => $studentEmail,
            ':password' => $hashedPassword,
        ]);

        // Redirect to a success page or perform other actions as needed.
        header("Location: instructorview.html");
        exit;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}