<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$job_id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT j.*, u.name AS client_name FROM jobs j JOIN users u ON j.user_id = u.id WHERE j.id='$job_id'");

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Job not found!'); window.location='admin_jobs.php';</script>";
    exit();
}
$job = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Edit Job Status</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .admin-navbar { display:flex; justify-content:space-around; align-items:center; background:white; padding:15px 20px; border-radius:60px; margin-bottom:30px; flex-wrap:wrap; }
        .nav-item { text-decoration:none; color:#0d3b66; padding:8px 16px; border-radius:30px; cursor:pointer; font-weight:500; }
        .nav-item:hover, .nav-item.active { background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; }
        .btn-back { background:#e9ecef; color:#0d3b66; border:none; border-radius:30px; padding:10px 24px; font-size:14px; font-weight:bold; cursor:pointer; margin-bottom:20px; text-decoration:none; display:inline-block; }
        .btn-back:hover { background:#dee2e6; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-weight:bold; color:#1e3a5f; margin-bottom:6px; font-size:14px; }
        .form-group select {
            width:100%; padding:12px; border:1px solid #ccc; border-radius:10px; font-size:14px; box-sizing:border-box; font-family:inherit;
        }
        .form-group select:focus { outline:none; border-color:#f97316; }
        .job-info-box { background:#f8fafc; border-radius:12px; padding:16px 20px; margin-bottom:24px; }
        .job-info-box p { margin:4px 0; color:#444; font-size:14px; }
        .btn-save { background:linear-gradient(135deg,#16a34a,#15803d); color:white; border:none; border-radius:30px; padding:14px 40px; font-size:16px; font-weight:bold; cursor:pointer; width:100%; margin-top:10px; }
        .btn-save:hover { opacity:0.9; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="admin-navbar">
        <a href="admin_dashboard.php" class="nav-item">📊 Dashboard</a>
        <a href="admin_users.php" class="nav-item">👥 Users</a>
        <a href="admin_jobs.php" class="nav-item active">💼 Jobs</a>
        <a href="admin_payments.php" class="nav-item">💳 Payment</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card">
        <a href="admin_jobs.php" class="btn-back">← Back</a>
        <h2>✏️ Edit Job Status</h2>

        <div class="job-info-box">
            <p><strong>Job Title:</strong> <?php echo htmlspecialchars($job['title']); ?></p>
            <p><strong>Posted by:</strong> <?php echo htmlspecialchars($job['client_name']); ?></p>
            <p><strong>Budget:</strong> RM<?php echo $job['amount']; ?> (<?php echo $job['payment_type']; ?>)</p>
        </div>

        <form action="proses_admin_edit_job.php" method="POST">
            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="open" <?php echo $job['status'] == 'open' ? 'selected' : ''; ?>>🟡 Open</option>
                    <option value="in_progress" <?php echo $job['status'] == 'in_progress' ? 'selected' : ''; ?>>🔵 In Progress</option>
                    <option value="completed" <?php echo $job['status'] == 'completed' ? 'selected' : ''; ?>>✅ Completed</option>
                </select>
            </div>

            <button type="submit" class="btn-save">💾 Save Status</button>
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