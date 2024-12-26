<?php
session_start();
session_destroy();
header("Location: ../layout/app.php");
exit();
?>