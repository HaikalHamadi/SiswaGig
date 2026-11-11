<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
mysqli_query($conn, "DELETE FROM jobs WHERE id='$job_id'");
echo "<script>alert('✅ Job successfully deleted!'); window.location='admin_jobs.php';</script>";
?>