<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
$freelancer_id = mysqli_real_escape_string($conn, $_POST['freelancer_id']);
$payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);
$amount = mysqli_real_escape_string($conn, $_POST['amount']);
$freelancer_rate = mysqli_real_escape_string($conn, $_POST['freelancer_rate']);

// Update status job jadi completed
mysqli_query($conn, "UPDATE jobs SET status='completed' WHERE id='$job_id'");

// Redirect ke page payment dengan maklumat
$query = http_build_query([
    'job_id' => $job_id,
    'freelancer_id' => $freelancer_id,
    'payment_type' => $payment_type,
    'amount' => $amount,
    'freelancer_rate' => $freelancer_rate
]);

header("Location: payment.php?$query");
exit();
?>