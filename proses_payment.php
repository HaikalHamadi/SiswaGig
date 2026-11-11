<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
$client_id = mysqli_real_escape_string($conn, $_POST['client_id']);
$freelancer_id = mysqli_real_escape_string($conn, $_POST['freelancer_id']);
$payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);
$amount = mysqli_real_escape_string($conn, $_POST['amount']);
$hours_worked = mysqli_real_escape_string($conn, $_POST['hours_worked']);
$payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

$sql = "INSERT INTO payments (job_id, client_id, freelancer_id, amount, hours_worked, payment_type, status, paid_at)
        VALUES ('$job_id','$client_id','$freelancer_id','$amount','$hours_worked','$payment_type','completed', NOW())";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✅ Payment successful! Thank you.'); window.location='MainMenu_MyJobs.php';</script>";
} else {
    echo "<script>alert('Failed to process payment!'); window.location='MainMenu_MyJobs.php';</script>";
}
?>