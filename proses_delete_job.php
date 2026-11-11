<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM jobs WHERE id='$job_id' AND user_id='$user_id'";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✅ Job was successfully deleted!'); window.location='MainMenu_MyJobs.php';</script>";
} else {
    echo "<script>alert('Failed to delete job!'); window.location='MainMenu_MyJobs.php';</script>";
}
?>