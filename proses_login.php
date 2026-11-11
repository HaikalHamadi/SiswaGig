<?php
include 'connection.php';

$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$sql = "SELECT * FROM users WHERE name='$name' AND email='$email' AND password='$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];

    if ($user['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else if ($user['role'] == 'freelancer') {
        header("Location: MainMenu_Browse.php");
    } else if ($user['role'] == 'client') {
        header("Location: MainMenu_Post.php");
    } else {
        header("Location: RoleGP.php");
    }
    exit();
} else {
    echo "<script>alert('Name, email or password is incorrect!'); window.location='LoginGP.php';</script>";
}
?>