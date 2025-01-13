<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "job_platform";

// إنشاء الاتصال
$conn = new mysqli($host, $user, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
