<?php

@include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $cpass = trim($_POST['cpassword']);
    $user_type = 'student';

    $select = "SELECT * FROM users WHERE email = '$email' AND password = '$pass'";
    $result = mysqli_query($conn, $select);

    if (!$result) {
        $error[] = 'Error in query: ' . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($result) > 0) {
            $error[] = 'User already exists!';
        } else {
            if (!password_verify($cpass, $pass)) {
                $error[] = 'Passwords do not match!';
            } 
            else {
                // Using prepared statement
                $insert = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert);

                if ($stmt) {
                    $stmt->bind_param("ssss", $name, $email, $pass, $user_type);

                    if ($stmt->execute()) {
                        header('location: reg_login.php');
                        exit;
                    } else {
                        $error[] = 'Error executing the query: ' . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $error[] = 'Error preparing the statement: ' . $conn->error;
                }
            }
        }
    }

    // Output errors
    if (isset($error)) {
        foreach ($error as $err) {
            echo '<span class="error-msg">' . $err . '</span>';
        }
    }
};

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .registration-container {
            max-width: 500px; /* Increase the max-width for a larger window */
            margin: 0 auto;
            padding: 30px; /* Increase the padding for more spacing */
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #ff66e8;
            color: #fff;
            padding: 10px;
            margin-top: 20px; /* Increase the margin for a bigger gap */
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <form action="" method="post">
            <h1>Student Registration</h1>
            <?php
                if(isset($error)){
                    foreach($error as $error){
                        echo '<span class="error-msg">'.$error.'</span>';
                    };
                };
            ?>
            <label for="studentName">Name:</label>
            <input type="text" id="studentName" name="name" required placeholder="enter your name">

            <label for="studentEmail">Email:</label>
            <input type="email" id="studentEmail" name="email" required placeholder="enter your email">

            <label for="studentPassword">Password:</label>
            <input type="password" id="studentPassword" name="password" required placeholder="enter your password">

            <label for="studentCPassword">Confirm Password:</label>
            <input type="password" name="cpassword" required placeholder="confirm your password">

            <input type="submit" name="submit" value="Register" class="form-btn">
            <p>already have an account? <a href="login.php">login now</a></p>
        </form>
    </div>
</body>
</html>