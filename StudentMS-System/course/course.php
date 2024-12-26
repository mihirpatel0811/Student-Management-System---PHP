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

// Initialize the search variable
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query 
if ($search) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("
        SELECT * FROM courses
        WHERE course_name LIKE CONCAT('%', ?, '%') 
        OR course_code LIKE CONCAT('%', ?, '%')
    ");
    $stmt->bind_param("ss", $search, $search);
} else {
    $stmt = $conn->prepare("
        SELECT * FROM courses
    ");
}

$stmt->execute();
$result = $stmt->get_result();
//$result = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard</title>
    <link rel="stylesheet" href="../css/main_dashboard.css">
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
            <a href="../student/student.php">Student</a>
            <a href="../course/course.php" class="active">Course</a>
            <a href="../user/profile.php" id="username-link" class="active">
                <i class='bx bxs-user'></i> <?php echo $_SESSION['username']; ?></span>
            </a>
            <a href="../user/logout.php"><i class='bx bx-log-out'></i> Logout</a>
        </nav>
    </header>
    <div class="body">
        <main class="main-course">
            <h1>Course Dashboard</h1>
            <div class="search-bar">
                <form action="../course/course.php" method="GET">
                    <input type="text" name="search" placeholder="Search students..." required>
                    <button type="submit"><i class='bx bx-search-alt'></i> Search</button>
                    <button class="reset" type="button" onclick="resetSearch()"><i class='bx bx-refresh'></i></button>
                    <a href="../course/create.php"><i class='bx bx-plus'></i> Add Student</a>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Name</th>
                        <th>Course Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0; // Initialize the counter
                    while ($row = $result->fetch_assoc()):
                        $i++; // Increment the counter for each row
                    ?>
                        <tr>
                            <td style="text-align: center"><?php echo $i; ?></td>
                            <!-- <td><?php echo $row['course_id']; ?></td> -->
                            <td><?php echo $row['course_name']; ?></td>
                            <td><?php echo $row['course_code']; ?></td>
                            <td>
                                <a href="../course/show.php?id=<?php echo $row['course_id']; ?>" class="show"><i class='bx bx-show'></i> Show</a>
                                <a href="../course/edit.php?id=<?php echo $row['course_id']; ?>" class="edit"><i class='bx bxs-edit'></i> Edit</a>
                                <a href="../course/delete.php?id=<?php echo $row['course_id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this course?');">
                                    <i class='bx bxs-trash'></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        function resetSearch() {
            // Clear the search input and reload the page
            window.location.href = "../course/course.php"; // Redirect to the course page without search
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>