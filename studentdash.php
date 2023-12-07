<?php
@include 'config.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'config.php';

if (!isset($_SESSION['id'])) {
  echo "Error: User ID not set in the session.";
  exit;
}

$_SESSION['student_id'] = $_SESSION['id'];

// if (isset($_SESSION['id'])) {
//     $_SESSION['student_id'] = $_SESSION['id'];
// } else {
//     echo "User ID not set in the session.";
//     header('location:login.php');
// }

//print_r($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="idashstyle.css">
    <style>
        .card-container {
            text-align: center;
        }
        .card {
            display: inline-block;
            width: 250px; /* Set a fixed width for each card */
            margin: 0 10px; /* Add some margin between cards */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card-title {

            font-size: 1.5em;
            margin: 10px;
        }

        .card-text {
            color: #555;
            height: 100px;
            margin: 15px;
        }

        .card-button {
            display: block;
            width: 100%;
            padding: 10px;
            text-align: center;
            background-color: #ff90e8;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .card-button:hover {
            background-color: #ff90e8;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
    </style>
    <title>Student View</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.nav-toggle').click(function (e) {
                e.preventDefault();
                $("html").toggleClass("openNav");
                $(".nav-toggle").toggleClass("active");
            });

            // Function to handle the "Join Class" button click
            $('#joinClassButton').click(function () {
                // Prompt the user to enter the class ID
                var classId = prompt("Enter the class ID:");

                // Check if the user entered a class ID
                if (classId !== null && classId.trim() !== "") {
                    // Redirect to the student class details page with the entered class ID
                    window.location.href = 'student_class_page.html?classId=' + classId;
                } else {
                    alert("Class ID cannot be empty. Please try again.");
                }
            });
        });
    </script>
</head>
<body>
    <div class="primary-nav">
        <button href="" class="hamburger open-panel nav-toggle"></button>
        <nav role="navigation" class="menu" id="menu-dropdown">
    
            <a href="welcome.html" class="logotype">ProTrack!</a>
        
            <div class="overflow-container">
    
                <ul class="menu-dropdown">
            
                <li class="menu-hasdropdown">
                    <a href="studentdash.php" style="margin-top: 20px;">Dashboard</a>
                    <ul class="sub-menu-dropdown">
                        <li><a href="student_projects.php">Projects</a></li>
                        <li><a href="#">Account</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Messages</a><span class="icon"><i class="fa-thin fa-envelope" style="color: #777;"></i></span></li>
                    </ul>
                </li>        
                </ul>
    
            </div>
        </nav>
    </div>
    
    <div class="new-wrapper">
    
        <div id="main">
    
        <div id="main-contents"> 
            <h1 style="padding-bottom: 20px;">Student Dashboard</h1>
            <div class="card">
                <h2 class="card-title">Your Projects</h2>
                <p class="card-text">View your Projects</p>
                <a href="student_projects.php" class="card-button">Go to Student Projects</a>
            </div>
            <div class="card">
                <h2 class="card-title">Create a new project</h2>
                <p class="card-text"><i style="color: black;" class="fa-duotone fa-plus"></i></p>
                <a href="create_project.php" class="card-button">Enter</a>
            </div>

            <!-- Join Class button -->
            <button id="joinClassButton">Join Class</button>

            <!-- List to display added classes -->
            <ul id="classList"></ul>
        </div>
    </div>
</body>
</html>