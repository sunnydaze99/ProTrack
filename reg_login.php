<?php
@include 'config.php';

session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = trim($_POST['password']);

    $select = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($select);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password'])) {
                    //echo 'User Type: ' . $row['user_type'];

                    $_SESSION['id'] = $row['id'];
                    $_SESSION['user_type'] = $row['user_type'];

                    if ($row['user_type'] == 'instructor') {
                        header('Location: pviewcon.php');
                        exit;
                    } elseif ($row['user_type'] == 'student') {
                        header('Location: sviewcon.php');
                        exit;
                    }
                } else {
                    $error[] = 'Incorrect email or password!';
                }
            } else {
                $error[] = 'Incorrect email or password!';
            }
        } else {
            $error[] = 'Error executing the query: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $error[] = 'Error in query preparation: ' . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginstyle.css">
    <title>ProTrack Login Page</title>
</head>
<body>
    <form action="" method="post">
      <?php
        if(isset($error)){
          foreach($error as $error){
              echo '<span class="error-msg">'.$error.'</span>';
          };
        };
        ?>
        <div class="imgcontainer">
          <a href="welcome.html"><img src="2.png" alt="Logo" class="logo"></a>
        </div>
      
        <div class="container" style="font-family: manti-sans-bold;;">
          <label for="email"><b>Email</b></label>
          <input type="email" id="email" name="email" placeholder="Enter Email" required>
      
          <label for="password"><b>Password</b></label>
          <input type="password" id="password" name="password" placeholder="Enter Password" required>

          <input type="submit" name="submit" value="Login" class="form-btn">
          <label>
            <input type="checkbox" checked="checked" name="remember"> Remember me
          </label><br>
          <p>Don't have an account? <a href="welcome.html">Register now</a></p>
        </div>
      </form>
</body>
</html>