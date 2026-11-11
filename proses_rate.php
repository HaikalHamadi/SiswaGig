<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$hourly_rate = mysqli_real_escape_string($conn, $_POST['hourly_rate']);

$sql = "UPDATE users SET hourly_rate='$hourly_rate' WHERE id='$user_id'";
if (mysqli_query($conn, $sql)) {
    header("Location: MainMenu_Browse.php");
    exit();
} else {
    echo "<script>alert('Failed to update rate!'); window.location='freelancer_payExpectation.php';</script>";
}
?>