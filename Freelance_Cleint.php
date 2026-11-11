<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT profile_image FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Client Profile</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .profile-avatar { width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#ff7b00,#ffaa00); display:inline-flex; align-items:center; justify-content:center; font-size:36px; margin-bottom:10px; }
        .profile-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <a href="MainMenu_Post.php" class="nav-item">📝 Post Job</a>
        <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Job</a>
        <a href="MainMenu_Profile.php" class="nav-item active">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>
    <div class="card">
        <h2>👤 Client Profile</h2>

        <div style="text-align:center; margin-bottom:15px;">
            <div class="profile-avatar">
                <?php if (!empty($user['profile_image'])): ?>
                    <img src="uploads/profile/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture">
                <?php else: ?>
                    👤
                <?php endif; ?>
            </div>
        </div>

        <div class="detail-section">
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <?php echo htmlspecialchars($_SESSION['user_email']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Role:</span>
                <span style="background:#d1fae5; padding:4px 12px; border-radius:20px;">🏢 Client</span>
            </div>
        </div>
        <form action="proses_role.php" method="POST">
            <input type="hidden" name="role" value="freelancer">
            <button type="submit" class="btn btn-secondary" style="width:auto;">🔄 Switch to Freelancer</button>
        </form>
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