<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Select Role</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="card" style="text-align:center;">
        <h2>What you are looking for?</h2>
        <p style="margin-bottom:20px; color:#666;">Choose your role in SiswaGIG</p>

        <form action="proses_role.php" method="POST">
            <input type="hidden" name="role" value="freelancer">
            <button type="submit" class="btn btn-primary" style="margin-bottom:10px;">💼 Be Freelancer - Find income</button>
        </form>

        <form action="proses_role.php" method="POST">
            <input type="hidden" name="role" value="client">
            <button type="submit" class="btn btn-secondary">🏢 Be Client - Employee wages</button>
        </form>
    </div>
</div>
</body>
</html>