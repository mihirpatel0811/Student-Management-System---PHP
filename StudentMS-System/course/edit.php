<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../user/login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'studentms_system');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the course_id is set
if (!isset($_GET['id'])) {
    echo "Error: course ID is required.";
    exit;
}

$course_id = $_GET['id'];

// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the course record
if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
} else {
    echo "Error: Course not found.";
    exit;
}

// Handle form submission for updating course details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $course_name = isset($_POST['course_name']) ? $_POST['course_name'] : $course['course_name'];
    $course_code = isset($_POST['course_code']) ? $_POST['course_code'] : $course['course_code'];

    // Prepare the SQL query to update the course details
    $stmt = $conn->prepare("UPDATE courses SET course_name = ?, course_code = ? WHERE course_id = ?");
    $stmt->bind_param("ssi", $course_name, $course_code, $course_id);

    if ($stmt->execute()) {
        header("Location: ../course/course.php");
        exit();
    } else {
        echo "Error updating Course.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
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
        }

        @keyframes textclip {
            to {
                background-position: 200% center;
            }
        }
    </style>
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
            <h1>Edit Course Details</h1><br><br>
            <form action="../course/edit.php?id=<?php echo $course['course_id']; ?>" method="POST">
                <div class="form-group">
                    <label for="course_name">Course Name : </label>
                    <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="course_code">Course Code : </label>
                    <input type="text" name="course_code" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
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