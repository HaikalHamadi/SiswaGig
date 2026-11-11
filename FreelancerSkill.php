<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - My Skills</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .skill-chip { padding:8px 16px; border-radius:30px; background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; display:inline-block; margin:5px; font-size:14px; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <a href="MainMenu_Browse.php" class="nav-item">🔍 Search Job</a>
        <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Job</a>
        <a href="MainMenu_Profile.php" class="nav-item active">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card">
        <h2>⭐ My Skills</h2>
        <div>
            <?php
            if (!empty($user['skills'])) {
                $skills = explode(',', $user['skills']);
                foreach ($skills as $skill) {
                    echo '<span class="skill-chip">' . htmlspecialchars(trim($skill)) . '</span>';
                }
            } else {
                echo '<p style="color:#999;">No skills. Click update to add.</p>';
            }
            ?>
        </div>
        <br>
        <a href="freelancer_skills.php"><button class="btn btn-primary" style="width:auto;">✏️ Update Skills</button></a>
    </div>

    <div class="card">
        <h2>💰 Hourly Rate</h2>
        <p style="font-size:24px; color:#ff7b00; font-weight:bold;">
            <?php echo $user['hourly_rate'] ? 'RM ' . $user['hourly_rate'] . ' / hour' : 'Not yet set'; ?>
        </p>
        <a href="freelancer_payExpectation.php"><button class="btn btn-secondary" style="width:auto; margin-top:10px;">✏️ Rate Update</button></a>
    </div>
</div>
<script>
	function confirmLogout() {
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'logout.php';
    }
    return false;
}
</script>
</body>
</html>