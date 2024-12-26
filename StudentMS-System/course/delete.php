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

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        header("Location: ../course/course.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Error: ID not set";
    exit();
}

$stmt->close();
$conn->close();