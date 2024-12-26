<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../db_conn.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $connection->prepare("SELECT password FROM users WHERE username = ?");

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();

    // Store the result
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows == 1) {
        // Bind the result to a variable
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header('Location: ../student/student.php');
            exit(); // Always exit after a header redirect
        } else {
            echo "Invalid Username or Password";
        }
    } else {
        echo "Invalid Username or Password";
    }

    // Close the statement
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>

    <!--CSS Style Link-->
    <link rel="stylesheet" href="../css/login_form.css">

    <style>
        .logo {
            text-transform: uppercase;
            background-image: linear-gradient(-225deg,
                    rgb(0, 238, 255) 10%,
                    rgb(43, 255, 0) 29%,
                    rgb(255, 255, 0) 67%,
                    rgb(255, 0, 234) 100%);
            background-size: auto auto;
            background-clip: border-box;
            background-size: 200% auto;
            color: #fff;
            background-clip: text;
            /*text-fill-color: transparent;**/
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textclip 2s linear infinite;
            display: inline-block;
        }

        @keyframes textclip {
            to {
                background-position: 200% center;
            }
        }
    </style>

    <!--Icon Link-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <header class="header">
        <a href="#" class="logo">Student Management System</a>

        <nav class="navbar">
            <a href="../layout/app.php">Home</a>
            <a href="../student/student.php">Student</a>
            <a href="../course/course.php">Course</a>
            <a href="#contact">Contact</a>
            <a href="#" id="username-link">
                <i class='bx bxs-user'></i><span id="username"></span>
            </a>
        </nav>
    </header>
    <section class="body">
        <main class="main">
            <h1>Login Form</h1><br>
            <form action="../user/login.php" method="POST">
                <label for="username">Username:</label><br>
                <input type="text" name="username" placeholder="Username" required><br><br>
                <label for="password">Password:</label><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="../user/register.php">Register here</a></p>
        </main>
    </section>
    <script src="../js/script.js"></script>
</body>

</html>