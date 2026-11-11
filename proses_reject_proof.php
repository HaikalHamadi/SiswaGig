<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
    $reject_comment = mysqli_real_escape_string($conn, $_POST['reject_comment']);
    $user_id = $_SESSION['user_id'];

    $check = mysqli_query($conn, "SELECT id FROM jobs WHERE id='$job_id' AND user_id='$user_id' AND status='in_progress'");

    if (mysqli_num_rows($check) > 0) {
        // Kalau nak simpan comment dalam DB, tambah column reject_comment dalam table applications
        // mysqli_query($conn, "UPDATE applications SET reject_comment='$reject_comment' WHERE job_id='$job_id' AND status='accepted'");
        mysqli_query($conn, "DELETE FROM applications WHERE job_id='$job_id' AND status='accepted'");
        mysqli_query($conn, "UPDATE jobs SET status='open' WHERE id='$job_id'");
    }
}
header("Location: MainMenu_MyJobs.php");
exit();
?>