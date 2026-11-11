<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <div class="logo-box">
            <div class="logo-icon">🎓</div>
            <h1 style="margin-top:15px;">SiswaGIG</h1>
            <p>Freelance Platform for Student</p>
        </div>

        <div class="toggle-container">
            <a href="#" class="toggle-btn active">Login</a>
            <a href="RegistrationGP.php" class="toggle-btn">Register</a>
        </div>

        <h2>Login</h2>
        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="******" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>
</body>
</html>