<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
mysqli_query($conn, "DELETE FROM users WHERE id='$user_id' AND role != 'admin'");
echo "<script>alert('✅ User successfully deleted!'); window.location='admin_users.php';</script>";
?>