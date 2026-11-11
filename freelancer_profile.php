<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$freelancer_id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$freelancer_id' AND role='freelancer'");

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Profile not found!'); window.location='MainMenu_MyJobs.php';</script>";
    exit();
}
$freelancer = mysqli_fetch_assoc($result);

// Ambil statistik freelancer
$total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM applications WHERE freelancer_id='$freelancer_id' AND status='accepted'"))['total'];
$completed_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs j JOIN applications a ON a.job_id = j.id WHERE a.freelancer_id='$freelancer_id' AND j.status='completed'"))['total'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Freelancer Profile</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .btn-back { background:#e9ecef; color:#0d3b66; border:none; border-radius:30px; padding:10px 24px; font-size:14px; font-weight:bold; cursor:pointer; margin-bottom:20px; text-decoration:none; display:inline-block; }
        .btn-back:hover { background:#dee2e6; }
        .profile-header { text-align:center; padding:20px 0; border-bottom:1px solid #e9ecef; margin-bottom:20px; }
        .profile-avatar { width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#ff7b00,#ffaa00); display:inline-flex; align-items:center; justify-content:center; font-size:36px; margin-bottom:10px; }
        .profile-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
		.skill-chip { padding:8px 16px; border-radius:30px; background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; display:inline-block; margin:4px; font-size:13px; }
        .stat-row { display:flex; gap:15px; justify-content:center; margin:15px 0; flex-wrap:wrap; }
        .stat-item { background:#f8fafc; border-radius:12px; padding:12px 20px; text-align:center; min-width:100px; }
        .stat-num { font-size:24px; font-weight:bold; color:#ff7b00; }
        .stat-lbl { font-size:12px; color:#666; margin-top:3px; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <?php if ($_SESSION['user_role'] == 'client'): ?>
            <a href="MainMenu_Post.php" class="nav-item">📝 Post Job</a>
            <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Job</a>
        <?php else: ?>
            <a href="MainMenu_Browse.php" class="nav-item">🔍 Search Job</a>
            <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Work</a>
        <?php endif; ?>
        <a href="MainMenu_Profile.php" class="nav-item">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card">
        <a href="javascript:history.back()" class="btn-back">← Back</a>

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
    <?php if (!empty($freelancer['profile_image'])): ?>
        <img src="uploads/profile/<?php echo htmlspecialchars($freelancer['profile_image']); ?>" alt="Profile Picture">
    <?php else: ?>
        👤
    <?php endif; ?>
</div>
            <h2 style="border:none; padding:0;"><?php echo htmlspecialchars($freelancer['name']); ?></h2>
            <p style="color:#666;">💼 Freelancer</p>
            <p style="color:#666;"><?php echo htmlspecialchars($freelancer['email']); ?></p>
        </div>

        <!-- Statistik -->
        <div class="stat-row">
            <div class="stat-item">
                <div class="stat-num"><?php echo $total_jobs; ?></div>
                <div class="stat-lbl">Job Accepted</div>
            </div>
            <div class="stat-item">
                <div class="stat-num"><?php echo $completed_jobs; ?></div>
                <div class="stat-lbl">Job Done</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">RM<?php echo $freelancer['hourly_rate'] ?? '0'; ?></div>
                <div class="stat-lbl">Rate/Hour</div>
            </div>
        </div>

        <!-- Kemahiran -->
        <div class="detail-section">
            <h3 style="margin-bottom:12px;">⭐ Skills</h3>
            <?php if (!empty($freelancer['skills'])): ?>
                <?php foreach (explode(',', $freelancer['skills']) as $skill): ?>
                    <span class="skill-chip"><?php echo htmlspecialchars(trim($skill)); ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:#999;">No skills registered.</p>
            <?php endif; ?>
        </div>

        <!-- Info Lain -->
        <div class="detail-section" style="margin-top:15px;">
            <div class="detail-row">
                <span class="detail-label">💰 Hourly Rate:</span>
                <?php echo $freelancer['hourly_rate'] ? 'RM'.$freelancer['hourly_rate'].'/hour' : 'Not yet set'; ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">📅 Member Since:</span>
                <?php echo date('d F Y', strtotime($freelancer['created_at'])); ?>
            </div>
        </div>
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