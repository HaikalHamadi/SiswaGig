<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$freelancer_id = $_SESSION['user_id'];
$job_id = mysqli_real_escape_string($conn, $_POST['job_id']);

// Semak sama ada user ni yang post job tu
$own_job = mysqli_query($conn, "SELECT id FROM jobs WHERE id='$job_id' AND user_id='$freelancer_id'");
if (mysqli_num_rows($own_job) > 0) {
    echo "<script>alert('❌ You cannot apply for a job that you yourself posted!'); window.location='MainMenu_Browse.php';</script>";
    exit();
}
// Semak dah apply ke belum
$check = mysqli_query($conn, "SELECT status FROM applications WHERE job_id='$job_id' AND freelancer_id='$freelancer_id'");
if (mysqli_num_rows($check) > 0) {
    $existing = mysqli_fetch_assoc($check);
    if ($existing['status'] == 'rejected') {
        echo "<script>alert('❌ Your previous application for this job was rejected. You cannot reapply.'); window.location='MainMenu_Browse.php';</script>";
    } else {
        echo "<script>alert('You have already applied for this job!'); window.location='MainMenu_Browse.php';</script>";
    }
    exit();
}

$sql = "INSERT INTO applications (job_id, freelancer_id, status) VALUES ('$job_id','$freelancer_id','pending')";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✅ Application successfully sent!'); window.location='MainMenu_Browse.php';</script>";
} else {
    echo "<script>alert('Failed to send application:" . mysqli_error($conn) . "'); window.location='MainMenu_Browse.php';</script>";
}
?>