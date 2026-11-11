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
    <title>SiswaGIG - Pay Expectation</title>
    <link rel="stylesheet" href="gpCSS.css">
</head>
<body>
<div class="app-container">
    <div class="card" style="max-width:400px; margin:0 auto;">
        <h2>💰 Expected hourly income?</h2>
        <form action="proses_rate.php" method="POST">
            <div class="form-group">
                <label>RM / hour</label>
                <input type="number" name="hourly_rate" placeholder="eg: 25" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Save & Continue</button>
        </form>
    </div>
</div>
</body>
</html>