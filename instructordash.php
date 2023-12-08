<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

@include 'config.php';

if (!isset($_SESSION['id'])) {
  echo "Error: User ID not set in the session.";
  exit;
}

$_SESSION['professorID'] = $_SESSION['id'];
print_r($_SESSION);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyA7HJN15u7ffIehXW1lPxpe2FZKbI" crossorigin="anonymous">
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
    <title>Instructor View</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
        <button href="" class="hamburger open-panel nav-toggle"></button>
        <nav role="navigation" class="menu" id="menu-dropdown">
    
            <a href="welcome.html" class="logotype">ProTrack!</a>
        
            <div class="overflow-container">
    
                <ul class="menu-dropdown">
            
                <li class="menu-hasdropdown">
                    <a href="instructordash.php" style="margin-top: 20px;">Dashboard</a>
                    <ul class="sub-menu-dropdown">
                    <li><a href="create_syllabus.php">Create Syllabus</a></li>
                        <li><a href="view_student_projects.php">Student Projects</a></li>
                        <li><a href="prof_account.php">Account</a></li>
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
            <h1 style="padding-bottom: 20px;">Instructor Dashboard</h1>
            <div class="card">
                <h2 class="card-title">Student Projects</h2>
                <p class="card-text">Explore student projects and assignments.</p>
                <a href="view_student_projects.php" class="card-button">Go to Student Projects</a>
            </div>
            <div class="card">
                <h2 class="card-title">Create Syllabus</h2>
                <p class="card-text">Make a new syllabus for a new project</p>
                <a href="create_syllabus.php" class="card-button">Go to Student Projects</a>
            </div>
        </div>
    </div>
</body>
</html>