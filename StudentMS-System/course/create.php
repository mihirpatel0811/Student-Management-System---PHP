<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../user/login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'studentms_system');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO `courses` (`course_name`, `course_code`) VALUES (?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ss", $course_name, $course_code);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect after successful insertion
        header("Location: ../course/course.php");
        exit(); // Ensure no further code is executed
    } else {
        // Handle execution error
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    echo "New record created successfully";
    header("Location: ../course/course.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course </title>
    <link rel="stylesheet" href="../css/create_dashboard.css">
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
            font-size: 2.5rem;
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
            <a href="#">Student</a>
            <a href="../course/course.php" class="active">Course</a>
            <a href="../user/profile.php" id="username-link" class="active">
                <i class='bx bxs-user'></i> <?php echo $_SESSION['username']; ?></span>
            </a>
            <a href="#"><i class='bx bx-log-out'></i> Logout</a>
        </nav>
    </header>
    <section class="body">
        <main class="main-course">
            <h1>Add Course Details</h1><br><br>
            <form action="../course/create.php" method="POST">
                <div class="form-group">
                    <label for="course_name">Course Name : </label>
                    <input type="text" name="course_name" placeholder="Course Name" required>
                </div>

                <div class="form-group">
                    <label for="course_code">Course Code : </label>
                    <input type="text" name="course_code" placeholder="course Code" required>
                </div>

                <button type="submit"><i class='bx bx-save'></i> Save</button>
                <a href="../course/course.php"><i class='bx bx-arrow-back'></i> Back</a>
            </form>
        </main>
    </section>
</body>

</html>

<?php
$conn->close();
?>