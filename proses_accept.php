<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$application_id = mysqli_real_escape_string($conn, $_POST['application_id']);

// Ambil job_id dari application
$app = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM applications WHERE id='$application_id'"));
$job_id = $app['job_id'];

// Update status application jadi accepted
mysqli_query($conn, "UPDATE applications SET status='accepted' WHERE id='$application_id'");

// Update status job jadi in_progress
mysqli_query($conn, "UPDATE jobs SET status='in_progress' WHERE id='$job_id'");

echo "<script>alert('✅ Application accepted! Job status updated to In Progress.'); window.location='MainMenu_MyJobs.php';</script>";
?>