<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Create a database connection (replace with your actual database credentials).
    $hostname = "localhost";
    $username = "root";
		$password = "";
    $database = "protrack!";

    try {
      $con = new mysqli($hostname, $username, $password, $database);

      if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
      }
      
      $inputUsername = $_POST["uname"];
      $inputPassword = $_POST["psw"];

      // Prepare and execute a query for the professor table
      $queryProfessor = "SELECT * FROM pusers WHERE professorName = ? AND professorPassword = ?";
      $stmtProfessor = $con->prepare($queryProfessor);
      if (!$stmtProfessor) {
        die("Error in prof query preparation: " . $con->error);
      }
      $stmtProfessor->bind_param("ss", $inputUsername, $inputPassword);
      if (!$stmtProfessor->execute()) {
        die("Error in query execution: " . $stmtProfessor->error);
      }
      $resultProfessor = $stmtProfessor->get_result();

      // Prepare and execute a query for the student table
      $queryStudent = "SELECT * FROM susers WHERE studentName = ? AND studentPassword = ?";
      $stmtStudent = $con->prepare($queryStudent);
      if (!$stmtStudent) {
        die("Error in stud query preparation: " . $con->error);
      }
      $stmtStudent->bind_param("ss", $inputUsername, $inputPassword);
      if (!$stmtStudent->execute()) {
        die("Error in query execution: " . $stmtStudent->error);
      }
      
      $resultStudent = $stmtStudent->get_result();

      // Check if the username and password exist in either table
      if ($resultProfessor->num_rows > 0) {
        echo "Login successful as a professor.";
        header("Location: instructordash.html");
      } elseif ($resultStudent->num_rows > 0) {
        echo "Login successful as a student.";
        header("Location: studentdash.html");
      } else {
        echo "Login failed. Invalid username or password.";
      }
      exit;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }   
    $stmt->closeCursor();
    $con->close(); 
}