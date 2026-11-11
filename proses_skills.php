<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$skills = mysqli_real_escape_string($conn, $_POST['skills']);

$sql = "UPDATE users SET skills='$skills' WHERE id='$user_id'";
if (mysqli_query($conn, $sql)) {
    header("Location: freelancer_payExpectation.php");
    exit();
} else {
    echo "<script>alert('Failed to update skills!'); window.location='freelancer_skills.php';</script>";
}
?>