<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../user/login.php");
    exit();
}

// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'studentms_system');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $course_id = (int)$_GET['id']; // Cast to integer for safety

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the student ID parameter
    $stmt->bind_param("i", $course_id);

    // Execute the statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        //echo "Number of rows returned: " . $result->num_rows . "<br>"; // Debugging output

        // Check if any student was found
        if ($result->num_rows > 0) {
            $course = $result->fetch_assoc(); // Fetch the student data
        } else {
            echo "No student found.";
            exit();
        }
    } else {
        echo "Error executing query: " . $stmt->error;
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Course</title>
    <link rel="stylesheet" href="../css/show_dashbord.css">
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
            <a href="#">Student</a>
            <a href="../course/course.php" class="active">Course</a>
            <a href="../user/profile.php" id="username-link" class="active">
                <i class='bx bxs-user'></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a>
            <a href="#"><i class='bx bx-log-out'></i> Logout</a>
        </nav>
    </header>
    <section class="body">
        <main class="main-course">
            <h1>Show Course Details</h1><br>
            <div class="show">
                <p><strong>Course Name : </strong><?php echo htmlspecialchars($course['course_name']); ?></p>
                <p><strong>Course Code : </strong><?php echo htmlspecialchars($course['course_code']); ?></p>
            </div>
            <a href="../course/course.php"><i class='bx bx-arrow-back'></i> Back</a>
            <button class="print" onclick="printPage()">
                <i class='bx bx-printer'></i> Print
            </button>
        </main>
    </section>
    <script>
        function printPage() {
            const h1Content = document.querySelector('h1').innerHTML;
            const showContent = document.querySelector('.show').innerHTML;
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write('<link rel="stylesheet" href="../css/show_dashbord.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1>' + h1Content + '</h1>');
            printWindow.document.write(showContent);
            printWindow.document.write('</body></html>');   
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        }
    </script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>