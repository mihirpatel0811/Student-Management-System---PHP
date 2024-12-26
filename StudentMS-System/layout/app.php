<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>

    <!--CSS Link-->
    <link rel="stylesheet" href="../css/style.css">

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

        .home-content h1 {
            font-size: 7.5rem;
            /* Increase font size */
            text-align: center;
            /* Center the text */
            opacity: 0;
            /* Start with hidden text */
            transform: translateY(20px);
            /* Start slightly lower */
            animation: fadeInUp 1s forwards;
            /* Apply animation */
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                /* Fade in */
                transform: translateY(0);
                /* Move to original position */
            }
        }
    </style>

    <!--Icon Link-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <header class="header">
        <h3 class="logo">Student Management System</h3>

        <nav class="navbar">
            <a href="#home" class="active">Home</a>
            <a href="../student/student.php">Student</a>
            <a href="../course/course.php">Course</a>
            <a href="#contact">Contact</a>
            <a href="#" id="username-link">
                <i class='bx bxs-user'></i><span id="username"></span>
            </a>
        </nav>
    </header>

    <script>
        // Get all navigation links
        const navLinks = document.querySelectorAll('.navbar a');

        // Add click event listener to each link
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Remove 'active' class from all links
                navLinks.forEach(nav => nav.classList.remove('active'));
                // Add 'active' class to the clicked link
                this.classList.add('active');
            });
        });
    </script>

    <main class="main" id="main">
        <section class="home" id="home">
            <div class="home-content">
                <h1>Welcome to <br>Student Management System</h1>
                <div class="option">
                    <a href="../user/register.php" class="btn1"><i class='bx bx-user-plus'></i> Register</a>
                    <a href="../user/login.php" class="btn2"><i class='bx bx-log-in'></i> Login</a>
                </div>
            </div>
        </section>
    </main>
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