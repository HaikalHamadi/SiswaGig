<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

$result = mysqli_query($conn, "SELECT * FROM jobs WHERE status='open' AND user_id != '$user_id' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Search Job</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .btn-details {
            background: linear-gradient(135deg, #ff7b00, #ffaa00);
            color: white; border: none; border-radius: 20px;
            padding: 8px 18px; font-size: 13px; cursor: pointer;
            font-weight: bold; text-decoration: none;
        }
        .btn-details:hover { opacity: 0.9; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <?php if ($role == 'client'): ?>
            <a href="MainMenu_Post.php" class="nav-item">📝 Post Job</a>
            <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Job</a>
        <?php else: ?>
            <span class="nav-item active">🔍 Search Job</span>
            <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Work</a>
        <?php endif; ?>
        <a href="MainMenu_Profile.php" class="nav-item">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card">
        <h2>🚀 Jobs Available</h2>
        <?php if (mysqli_num_rows($result) == 0): ?>
            <p style="color:#999;">There are no jobs available at this time.</p>
        <?php else: ?>
            <?php while ($job = mysqli_fetch_assoc($result)): ?>
            <div class="job-card">
                <div class="flex-between" style="flex-wrap:wrap; gap:10px;">
                    <div style="flex:1;">
                        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p style="color:#666; margin:5px 0;"><?php echo htmlspecialchars(substr($job['description'], 0, 100)) . '...'; ?></p>
                        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:8px;">
                            <span style="background:#e9ecef; padding:4px 12px; border-radius:20px; font-size:13px;">💰 RM<?php echo $job['amount']; ?></span>
                            <span style="background:#e9ecef; padding:4px 12px; border-radius:20px; font-size:13px;">⏱️ <?php echo $job['duration']; ?></span>
                            <span style="background:#e9ecef; padding:4px 12px; border-radius:20px; font-size:13px;">📱 <?php echo $job['platform']; ?></span>
                        </div>
                    </div>
                    <a href="job_detail.php?id=<?php echo $job['id']; ?>" class="btn-details">👁️ Details</a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
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