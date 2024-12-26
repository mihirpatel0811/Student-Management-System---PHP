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

// Check if the student_id is set
if (!isset($_GET['id'])) {
    echo "Error: Student ID is required.";
    exit;
}

$student_id = $_GET['id'];

// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the student record
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "Error: Student not found.";
    exit;
}

// Handle form submission for updating student details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $student_name = $_POST['student_name'] ?? null;
    $course_id = $_POST['course_id'] ?? null;
    $grno = $_POST['grno'] ?? null;
    $enrollment_no = $_POST['enrollment_no'] ?? null;
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $age = $_POST['age'] ?? null;
    $email = $_POST['email'] ?? null;
    $city = $_POST['city'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $mobileno = $_POST['mobile_no'] ?? null;

    // Prepare the SQL query to update the student details
    $stmt = $conn->prepare("UPDATE students SET student_name = ?, course_id = ?, grno = ?, enrollment_no = ?, date_of_birth = ?, age = ?, email = ?, city = ?, gender = ?, mobile_no = ? WHERE student_id = ?");
    $stmt->bind_param("siisssssssi", $student_name, $course_id, $grno, $enrollment_no, $date_of_birth, $age, $email, $city, $gender, $mobileno, $student_id);

    if ($stmt->execute()) {
        header("Location: ../student/student.php");
        exit();
    } else {
        echo "Error updating student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../css/create.css">
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
            <a href="../student/student.php" class="active">Student</a>
            <a href="#">Course</a>
            <a href="../user/profile.php" id="username-link" class="active">
                <i class='bx bxs-user'></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a>
            <a href="#"><i class='bx bx-log-out'></i> Logout</a>
        </nav>
    </header>
    <section class="body">
        <main class="main" style="width: 950px;">
            <h1>Edit Student Details</h1><br>
            <form action="../student/edit.php?id=<?php echo $student_id; ?>" method="POST">
                <div class="form-group">
                    <label for="student_name">Student Name:</label>
                    <input type="text" name="student_name" value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="course_id">Course:</label>
                        <select name="course_id" required>
                            <?php
                            // Fetch courses for the dropdown
                            $course_stmt = $conn->prepare("SELECT * FROM courses");
                            $course_stmt->execute();
                            $courses = $course_stmt->get_result();
                            while ($course = $courses->fetch_assoc()) {
                                $selected = ($course['course_id'] == $student['course_id']) ? 'selected' : '';
                                echo "<option value='{$course['course_id']}' $selected>{$course['course_name']}</option>";
                            }
                            $course_stmt->close();
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="grno">GR No:</label>
                        <input type="text" name="grno" value="<?php echo htmlspecialchars($student['grno']); ?>" required>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="enrollment_no">Enrollment No:</label>
                        <input type="text" name="enrollment_no" value="<?php echo htmlspecialchars($student['enrollment_no']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" required>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($student['city']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select name="gender" id="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo (isset($student['gender']) && $student['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo (isset($student['gender']) && $student['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mobileno">Mobile No:</label>
                    <input type="text" id="mobileno" name="mobile_no" value="<?php echo htmlspecialchars($student['mobile_no'] ?? ''); ?>" required>
                </div>

                <button type="submit"><i class='bx bx-save'></i> Save</button>
                <a href="../student/student.php"><i class='bx bx-arrow-back'></i> Back</a>
            </form>
        </main>
    </section>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>