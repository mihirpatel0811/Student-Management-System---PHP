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
    $stmt = $conn->prepare("
        SELECT s.*, c.course_name 
        FROM students s 
        JOIN courses c ON s.course_id = c.course_id
        WHERE s.student_name LIKE CONCAT('%', ?, '%')
        OR c.course_name LIKE CONCAT('%', ?, '%')
        OR s.grno LIKE CONCAT('%', ? ,'%')
        OR s.enrollment_no LIKE CONCAT('%', ?, '%')
        OR s.city LIKE CONCAT('%', ?, '%')
        OR s.gender LIKE CONCAT('%', ? ,'%')
    ");
    // Bind only 6 parameters
    $stmt->bind_param("ssssss", $search, $search, $search, $search, $search, $search);
} else {
    // No search, show all rows
    $stmt = $conn->prepare("
        SELECT s.*, c.course_name 
        FROM students s 
        JOIN courses c ON s.course_id = c.course_id
    ");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <header class="header">
        <a href="#" class="logo">Student Management System</a>

        <nav class="navbar">
            <a href="../student/student.php" class="active">Student</a>
            <a href="../course/course.php">Course</a>
            <a href="../user/profile.php" id="username-link" class="active">
                <i class='bx bxs-user'></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a>
            <a href="../user/logout.php" onclick="return confirm('Are you sure you want to logout?');"><i class='bx bx-log-out'></i> Logout</a>
        </nav>
    </header>
    <div class="body">
        <main class="main">
            <h1>Student Dashboard</h1>
            <div class="search-bar">
                <form action="../student/student.php" method="GET">
                    <input type="text" name="search" placeholder="Search students..." required>
                    <button type="submit"><i class='bx bx-search-alt'></i> Search</button>
                    <button class="reset" type="button" onclick="resetSearch()"><i class='bx bx-refresh'></i></button>
                    <a href="../student/create.php"><i class='bx bx-plus'></i> Add Student</a>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25px;">No.</th> <!-- Added a header for the index -->
                        <!-- <th style="width: 25px;">Student ID</th> -->
                        <th style="width: 25px; font-size:2rem;">Name</th>
                        <th style="width: 25px;">Course</th>
                        <th>GR No.</th>
                        <th>Enrollment No.</th>
                        <th style="width: 50px;">Date of Birth</th>
                        <th>Age</th>
                        <th style="width: 30px; font-size:2rem;">Email ID</th>
                        <th style="width: 25px;">City</th>
                        <th style="width: 25px;">Gender</th>
                        <th>Mobile No.</th>
                        <th style="text-align: center; font-size: 2.5rem;" width="300px">Action</th>
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
                            <!-- <td style="text-align: center"><?php echo htmlspecialchars($row['student_id']); ?></td> -->
                            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['grno']); ?></td>
                            <td><?php echo htmlspecialchars($row['enrollment_no']); ?></td>
                            <td><?php $date_of_birth = new DateTime($row['date_of_birth']);
                                echo $date_of_birth->format('d-m-Y'); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['city']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobile_no']); ?></td>
                            <td style="width:fit-content;">
                                <a href="../student/show.php?id=<?php echo $row['student_id']; ?>" class="show"><i class='bx bx-show'></i> Show</a>
                                <a href="../student/edit.php?id=<?php echo $row['student_id']; ?>" class="edit"><i class='bx bxs-edit'></i> Edit</a>
                                <a href="../student/delete.php?id=<?php echo $row['student_id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this student?');">
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
            window.location.href = "../student/student.php"; // Redirect to the course page without search
        }
    </script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>