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

// Get the username from the session
$username = $_SESSION['username'];

// Prepare the SQL query to fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
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
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleButton = document.getElementById("togglePassword");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleButton.innerHTML = "Hide Password";
            } else {
                passwordField.type = "password";
                toggleButton.innerHTML = "Show Password";
            }
        }
    </script>
</head>

<body>
    <header class="header">
        <a href="#" class="logo">Student Management System</a>
        <nav class="navbar">
            <a href="../student/student.php">Student</a>
            <a href="../course/course.php">Course</a>
            <a href="#" id="username-link" class="active">
                <i class='bx bxs-user'></i> <?php echo htmlspecialchars($username); ?>
            </a>
            <a href="../user/logout.php"><i class='bx bx-log-out'></i> Logout</a>
        </nav>
    </header>
    <div class="body">
        <main class="main">
            <h1>User Profile</h1>
            <div class="user">
                <img src="../images/user icon.png" alt="Profile Picture" class="profile-pic">
                <div class="user-info">
                    <?php if ($user): ?>
                        <p><strong>Username: </strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <p><strong>Email: </strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <!-- Uncomment the following block if you want to show the password -->
                        <!-- 
            <p>
                <strong>Password: </strong>
                <input type="password" id="password" value="<?php echo htmlspecialchars($user['password']); ?>" readonly>
                <button type="button" id="togglePassword" onclick="togglePassword()">Show Password</button>
            </p> 
            -->
                    <?php else: ?>
                        <p>User not found.</p>
                    <?php endif; ?>
                </div>
            </div>
            <a href="../student/student.php" class="back"><i class='bx bx-arrow-back' style="padding-right: 5px;"></i> Back</a>
        </main>
    </div>
    <footer id="contact" class="footer">
        <a href="#" class="logo">Student Management System</a>
        <div class="social-media">
            <a aria-label="Chat on WhatsApp" href="https://wa.me/9510457100"><i class='bx bxl-whatsapp'></i></a>
            <a href="https://www.instagram.com/immihir17193/profilecard/?igsh=MXA1NjI2cGx1NXE0Yg==">
                <i class='bx bxl-instagram'></i></a>
            <a href="https://www.facebook.com/immihir17193?mibextid=ZbWKwL"><i class='bx bxl-facebook'></i></a>
            <a href="mailto:mihirbhayani8@gmail.com"><i class='bx bxl-gmail'></i></a>
        </div>
    </footer>
    <script src="../js/script.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>