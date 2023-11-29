<?php
// Step 1: Connect to the database
$hostname = "localhost";
$username = "root";
$password = "";
$database = "protrack!";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Retrieve data from the database
$sql = "SELECT * FROM syllabus";
$result = $conn->query($sql);

// Step 3: Display data in HTML
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
            margin: 0 30px; /* Add some margin between cards */
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
    <script type="text/javascript" src="isp.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.nav-toggle').click(function (e) {
                e.preventDefault();
                $("html").toggleClass("openNav");
                $(".nav-toggle").toggleClass("active");
            });

            // Object to store class information
            var classData = {};

            // Function to handle the "Add Class" button click
            $('#addClassButton').click(function () {
                // Prompt the user to enter the class name
                var className = prompt("Enter the class name:");

                // Check if the user entered a class name
                if (className !== null && className.trim() !== "") {
                    // Generate a unique ID for the class (in this example, using the current timestamp)
                    var classId = "class_" + Date.now();

                    // Add class information to the object
                    classData[classId] = {
                        name: className,
                        details: "Class details go here."
                    };

                    // Create a new list item for the class with rounded square styling
                    var newClassItem = $('<li>').text(className).attr('id', classId).addClass('rounded-square');

                    // Append the new class item to the list
                    $('#classList').append(newClassItem);

                    // Add click event to redirect to the class details page
                    newClassItem.click(function () {
                        // Redirect to the class details page
                        window.location.href = 'class_page.html?classId=' + classId;
                    });

                    // Optionally, you can add more styling or functionality here

                    // Alert with the added class information (you can remove this if not needed)
                    alert("Class added!\nClass Name: " + className + "\nClass ID: " + classId);
                } else {
                    alert("Class name cannot be empty. Please try again.");
                }
            });
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
                    <a href="instructordash.html" style="margin-top: 20px;">Dashboard</a>
                    <ul class="sub-menu-dropdown">
                        <li><a href="create_syllabus.html">Create Syllabus</a></li>
                        <li><a href="student_projects.html">Student Projects</a></li>
                        <li><a href="">Account</a></li>
                        <li><a href="#">Settings</a></li>
                    </ul>
                </li>
            
                <li><a href="#">Messages</a><span class="icon"><i class="fa fa-envelope"></i></span></li>
        
                </ul>
    
            </div>
        </nav>
    </div>
    <div class="new-wrapper">
    
        <div id="main-contents"> 
            <h1 style="margin: 40px">Student Projects</h1>
    
            <div class="card">
                <h2 class="card-title">Project 1</h2>
                <p class="card-text">blahhhh......</p>
                <a href="#" class="card-button">View Project</a>
            </div>
        <div>
            <!-- Add Class button -->
            <button id="addClassButton">Add Class</button>

            <!-- List to display added classes -->
            <ul id="classList"></ul>
        </div>
        </div>
    
    </div>
</body>
</html>