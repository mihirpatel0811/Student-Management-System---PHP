<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../db_conn.php');

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conform_password = $_POST['conform_password'];

    if ($password == $conform_password) {
        // Prepare the SQL statement
        $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Bind parameters
        $stmt->bind_param("sss", $username, $email, $hashedPassword); // Use $hashedPassword here

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header('Location: ../user/login.php');
            exit(); // Always exit after a header redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Password and Confirm Password are not the same.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>

    <!--CSS Style Link-->
    <link rel="stylesheet" href="../css/register_form.css">

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
            <h1>Register Form</h1><br>
            <form action="../user/register.php" method="POST">
                <label for="username">Username : </label><br>
                <input type="text" name="username" placeholder="Username" required><br><br>
                <label for="email">Email : </label><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                <label for="password">Password : </label><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <label for="conform_password">Confirm Password : </label><br>
                <input type="password" name="conform_password" placeholder="Confirm Password" required><br><br>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <br><a href="../user/login.php">Login here</a></p>
        </main>
    </section>
    <script src="../js/script.js"></script>
</body>

</html>