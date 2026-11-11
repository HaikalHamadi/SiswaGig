<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$title = mysqli_real_escape_string($conn, $_POST['title']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$details = mysqli_real_escape_string($conn, $_POST['details']);
$requirement = mysqli_real_escape_string($conn, $_POST['requirement']);
$platform = mysqli_real_escape_string($conn, $_POST['platform']);

// Validate due date - wajib minimum 1 hari dari masa sekarang
if (empty($_POST['datetime']) || strtotime($_POST['datetime']) < strtotime('+1 day')) {
    echo "<script>alert('⚠️ Due date must be at least 1 day from now!'); window.location='MainMenu_Post.php';</script>";
    exit();
}
$datetime = mysqli_real_escape_string($conn, date('Y-m-d H:i:s', strtotime($_POST['datetime'])));

$payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);
$duration = mysqli_real_escape_string($conn, $_POST['duration']);
$amount = mysqli_real_escape_string($conn, $_POST['amount']);

$sql = "INSERT INTO jobs (title, description, details, requirement, platform, datetime, payment_type, duration, amount, posted_by, posted_by_id, user_id, status)
        VALUES ('$title','$description','$details','$requirement','$platform','$datetime','$payment_type','$duration','$amount','$user_name','$user_id','$user_id','open')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✅ Job successfully posted!'); window.location='MainMenu_MyJobs.php';</script>";
} else {
    echo "<script>alert('Failed to post job: " . mysqli_error($conn) . "'); window.location='MainMenu_Post.php';</script>";
}
?>