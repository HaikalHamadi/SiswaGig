<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $application_id = $_POST['application_id'];
    $reject_comment = mysqli_real_escape_string($conn, $_POST['reject_comment']);
    $user_id = $_SESSION['user_id'];
    $check = mysqli_query($conn, "SELECT a.id FROM applications a 
        JOIN jobs j ON a.job_id = j.id 
        WHERE a.id='$application_id' AND j.user_id='$user_id' AND a.status='pending'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE applications SET status='rejected', reject_reason='$reject_comment' WHERE id='$application_id'");
    }
}
header("Location: MainMenu_MyJobs.php");
exit();
?>