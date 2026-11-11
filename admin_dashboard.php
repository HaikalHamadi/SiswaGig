<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

// Statistik
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role != 'admin'"))['total'];
$total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs"))['total'];
$total_payments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM payments"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM payments WHERE status='completed'"))['total'];
$total_freelancers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='freelancer'"))['total'];
$total_clients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='client'"))['total'];
$jobs_open = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs WHERE status='open'"))['total'];
$jobs_progress = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs WHERE status='in_progress'"))['total'];
$jobs_completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs WHERE status='completed'"))['total'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Admin Dashboard</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .stat-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:15px; margin-bottom:20px; }
        .stat-card { background:white; border-radius:16px; padding:20px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
        .stat-number { font-size:36px; font-weight:bold; color:#ff7b00; }
        .stat-label { font-size:14px; color:#666; margin-top:5px; }
        .stat-icon { font-size:28px; margin-bottom:8px; }
        .admin-navbar { display:flex; justify-content:space-around; align-items:center; background:white; padding:15px 20px; border-radius:60px; margin-bottom:30px; flex-wrap:wrap; }
        .nav-item { text-decoration:none; color:#0d3b66; padding:8px 16px; border-radius:30px; cursor:pointer; font-weight:500; }
        .nav-item:hover, .nav-item.active { background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="admin-navbar">
        <span class="nav-item active">📊 Dashboard</span>
        <a href="admin_users.php" class="nav-item">👥 Users</a>
        <a href="admin_jobs.php" class="nav-item">💼 Jobs</a>
        <a href="admin_payments.php" class="nav-item">💳 Payment</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card">
        <h2>📊 Admin Dashboard</h2>
        <p style="color:#666;">Welcome, <?php echo $_SESSION['user_name']; ?>!</p>
    </div>

    <!-- Statistik -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number"><?php echo $total_users; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💼</div>
            <div class="stat-number"><?php echo $total_freelancers; ?></div>
            <div class="stat-label">Freelancer</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏢</div>
            <div class="stat-number"><?php echo $total_clients; ?></div>
            <div class="stat-label">Client</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-number"><?php echo $total_jobs; ?></div>
            <div class="stat-label">Total Jobs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🟡</div>
            <div class="stat-number"><?php echo $jobs_open; ?></div>
            <div class="stat-label">Open Job</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔵</div>
            <div class="stat-number"><?php echo $jobs_progress; ?></div>
            <div class="stat-label">In Progress Job</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number"><?php echo $jobs_completed; ?></div>
            <div class="stat-label">Completed Job</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-number">RM<?php echo number_format($total_revenue ?? 0, 2); ?></div>
            <div class="stat-label">Total Payment</div>
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