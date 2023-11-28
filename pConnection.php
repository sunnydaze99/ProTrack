<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = "localhost";
    $username = "root";
	$password = "";
    $database = "protrack!";

    $conn = new mysqli($hostname, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $professorName = $_POST['professorName'];
    $professorEmail = $_POST['professorEmail'];
    $professorPassword = $_POST['professorPassword'];

    // Hash the password
    $hashedPassword = password_hash($professorPassword, PASSWORD_DEFAULT);

    // Insert data into the professors table
    $sql = "INSERT INTO pusers (professorName, professorEmail, professorPassword)
            VALUES ('$professorName', '$professorEmail', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        echo "Instructor registration successful";
        header("Location: instructorview.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}