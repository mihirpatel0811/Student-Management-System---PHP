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
    $student_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        header("Location: ../student/student.php");
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