<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE jobs SET status='$status' WHERE id='$job_id'");
}
header("Location: admin_jobs.php");
exit();
?>