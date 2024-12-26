<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../user/login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'studentms_system');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    $mobileno = $_POST['mobileno'] ?? null;

    // Check if any required fields are missing
    if (
        is_null($student_name) || is_null($course_id) || is_null($grno) || is_null($enrollment_no) ||
        is_null($date_of_birth) || is_null($age) || is_null($email) || is_null($city) ||
        is_null($gender) || is_null($mobileno)
    ) {
        echo "Error: All fields are required.";
        exit; // Stop further execution
    }

    // Check if the course_id exists in the courses table
    $course_check = $conn->prepare("SELECT COUNT(*) FROM courses WHERE course_id = ?");
    $course_check->bind_param("s", $course_id);
    $course_check->execute();
    $course_check->bind_result($count);
    $course_check->fetch();
    $course_check->close();

    if ($count == 0) {
        echo "Error: The selected course does not exist.";
        exit; // Stop further execution
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO students (student_name, course_id, grno, enrollment_no, date_of_birth, age, email, city, gender, mobile_no) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters (age is an integer)
    $stmt->bind_param("ssisssssss", $student_name, $course_id, $grno, $enrollment_no, $date_of_birth, $age, $email, $city, $gender, $mobileno);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect after successful insertion
        header("Location: ../student/student.php");
        exit(); // Ensure no further code is executed
    } else {
        // Handle execution error
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    echo "New record created successfully";
    header("Location: ../student/student.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student </title>
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
                <i class='bx bxs-user'></i> <?php echo $_SESSION['username']; ?></span>
            </a>
            <a href="#"><i class='bx bx-log-out'></i> Logout</a>
        </nav>
    </header>
    <section class="body">
        <main class="main">
            <h1>Add Student Details</h1><br>
            <form action="../student/create.php" method="POST">
                <div class="form-group">
                    <label for="student_name">Student Name:</label>
                    <input type="text" name="student_name" placeholder="Student Name" required>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="course_id">Course:</label>
                        <select name="course_id" required>
                            <option value="">Select Course</option>
                            <?php
                            $courses = $conn->query("SELECT * FROM courses");
                            while ($course = $courses->fetch_assoc()) {
                                echo "<option value='{$course['course_id']}'>{$course['course_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="grno">GR No:</label>
                        <input type="text" name="grno" placeholder="GR No." required>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="enrollment_no">Enrollment No:</label>
                        <input type="text" name="enrollment_no" placeholder="Enrollment No." required>
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" name="date_of_birth" required>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" name="age" placeholder="Age" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" placeholder="Email ID" required>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" placeholder="City" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select name="gender" id="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mobileno">Mobile No:</label>
                    <input type="text" id="mobileno" name="mobileno" placeholder="Mobile No." required>
                </div>

                <p-button type="submit" pRipple (click)="showSuccess()" label="Save" icon="bx bx-save" severity="success"></p-button>
                <a href="../student/student.php"><i class='bx bx-arrow-back'></i> Back</a>
            </form>
        </main>
    </section>
</body>

</html>

<?php
$conn->close();
?>