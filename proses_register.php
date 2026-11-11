<?php
include 'connection.php';
$name = mysqli_real_escape_string($conn, trim($_POST['name']));
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$account_no = mysqli_real_escape_string($conn, $_POST['account_no']);

// Validate Full Name - huruf dan space je
if (!preg_match('/^[A-Za-z\s]+$/', $name)) {
    echo "<script>alert('Full Name must contain letters only, no numbers!'); window.location='RegistrationGP.php';</script>";
    exit();
}

// Validate account number
if (!preg_match('/^\d{12}$/', $account_no)) {
    echo "<script>alert('Account number must be exactly 12 digits!'); window.location='RegistrationGP.php';</script>";
    exit();
}

// Semak email dah wujud
$check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('Email has been registered!'); window.location='RegistrationGP.php';</script>";
    exit();
}

$sql = "INSERT INTO users (name, email, password, account_no) VALUES ('$name', '$email', '$password', '$account_no')";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Registration successful! Please login.'); window.location='LoginGP.php';</script>";
} else {
    echo "<script>alert('Registration failed! Try again.'); window.location='RegistrationGP.php';</script>";
}
?>