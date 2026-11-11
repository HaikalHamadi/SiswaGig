<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];

// Ambil job detail
$job_id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT j.*, u.name AS poster_name FROM jobs j JOIN users u ON j.user_id = u.id WHERE j.id='$job_id'");

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Job not found!'); window.location='MainMenu_Browse.php';</script>";
    exit();
}

$job = mysqli_fetch_assoc($result);

// Semak dah apply ke belum
$check = mysqli_query($conn, "SELECT id FROM applications WHERE job_id='$job_id' AND freelancer_id='$user_id'");
$dah_apply = mysqli_num_rows($check) > 0;
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Job Details</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .btn-mohon {
            background: linear-gradient(135deg, #ff7b00, #ffaa00);
            color: white; border: none; border-radius: 30px;
            padding: 14px 40px; font-size: 16px; font-weight: bold;
            cursor: pointer; transition: 0.2s; width: 100%; margin-top: 10px;
        }
        .btn-mohon:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,123,0,0.3); }
        .btn-back {
            background: #e9ecef; color: #0d3b66; border: none;
            border-radius: 30px; padding: 10px 24px; font-size: 14px;
            font-weight: bold; cursor: pointer; margin-bottom: 20px;
            text-decoration: none; display: inline-block;
        }
        .btn-back:hover { background: #dee2e6; }
        .sudah-apply {
            background: #d1fae5; padding: 15px; border-radius: 12px;
            text-align: center; margin-top: 10px; color: #065f46;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <span class="nav-item active">🔍 Search Job</span>
        <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Job</a>
        <a href="MainMenu_Profile.php" class="nav-item">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card">
        <!-- Button Back -->
        <a href="MainMenu_Browse.php" class="btn-back">← Back</a>

        <h2>📄 <?php echo htmlspecialchars($job['title']); ?></h2>

        <div class="detail-section">
            <div class="detail-row">
                <span class="detail-label">📝 Description:</span>
                <?php echo htmlspecialchars($job['description']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">🔍 Details:</span>
                <?php echo htmlspecialchars($job['details'] ?? 'None'); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">⚙️ Requirement:</span>
                <?php echo htmlspecialchars($job['requirement'] ?? 'None'); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">💰 Budget:</span>
                RM<?php echo $job['amount']; ?> (<?php echo $job['payment_type']; ?>)
            </div>
            <div class="detail-row">
                <span class="detail-label">⏱️ Duration:</span>
                <?php echo $job['duration']; ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">📱 Platform:</span>
                <?php echo $job['platform']; ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">👤 Posted By:</span>
                <?php echo htmlspecialchars($job['poster_name']); ?>
            </div>
        </div>

        <?php if ($dah_apply): ?>
            <div class="sudah-apply">✅ You have applied for this job. Status: In process.</div>
        <?php else: ?>
            <form action="proses_apply.php" method="POST">
                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                <button type="submit" class="btn-mohon">📝 Apply Now</button>
            </form>
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