<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = mysqli_real_escape_string($conn, $_POST['role']);

$sql = "UPDATE users SET role='$role' WHERE id='$user_id'";
if (mysqli_query($conn, $sql)) {
    $_SESSION['user_role'] = $role;

    if ($role == 'freelancer') {
        // Semak ada skills ke belum
        $check = mysqli_query($conn, "SELECT skills FROM users WHERE id='$user_id'");
        $user = mysqli_fetch_assoc($check);

        if (empty($user['skills'])) {
            // Belum ada skills — pergi setup skills dulu
            header("Location: freelancer_skills.php");
        } else {
            // Dah ada skills — terus ke cari kerja
            header("Location: MainMenu_Browse.php");
        }
    } else {
        header("Location: MainMenu_Post.php");
    }
    exit();
} else {
    echo "<script>alert('Failed to update role!'); window.location='RoleGP.php';</script>";
}
?>