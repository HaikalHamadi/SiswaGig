<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$payments = mysqli_query($conn, "SELECT p.*, j.title AS job_title, c.name AS client_name, f.name AS freelancer_name
    FROM payments p
    JOIN jobs j ON p.job_id = j.id
    JOIN users c ON p.client_id = c.id
    JOIN users f ON p.freelancer_id = f.id
    ORDER BY p.payment_id DESC");

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM payments WHERE status='completed'"))['total'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Admin Payment</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .admin-navbar { display:flex; justify-content:space-around; align-items:center; background:white; padding:15px 20px; border-radius:60px; margin-bottom:30px; flex-wrap:wrap; }
        .nav-item { text-decoration:none; color:#0d3b66; padding:8px 16px; border-radius:30px; cursor:pointer; font-weight:500; }
        .nav-item:hover, .nav-item.active { background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; }
        .total-box { background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; border-radius:16px; padding:20px; text-align:center; margin-bottom:20px; }
        .total-box h3 { font-size:14px; margin-bottom:5px; }
        .total-box .amount { font-size:36px; font-weight:bold; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="admin-navbar">
        <a href="admin_dashboard.php" class="nav-item">📊 Dashboard</a>
        <a href="admin_users.php" class="nav-item">👥 Users</a>
        <a href="admin_jobs.php" class="nav-item">💼 Jobs</a>
        <span class="nav-item active">💳 Payment</span>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="total-box">
        <h3>Total Payment Amount</h3>
        <div class="amount">RM<?php echo number_format($total ?? 0, 2); ?></div>
    </div>

    <div class="card">
        <h2>💳 Payment Record</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Job</th>
                    <th>Client</th>
                    <th>Freelancer</th>
                    <th>Type</th>
                    <th>Time</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($payments) == 0): ?>
                <tr><td colspan="9" style="text-align:center; color:#999;">No payment record.</td></tr>
                <?php else: ?>
                <?php while ($pay = mysqli_fetch_assoc($payments)): ?>
                <tr>
                    <td><?php echo $pay['payment_id']; ?></td>
                    <td><?php echo htmlspecialchars($pay['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($pay['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($pay['freelancer_name']); ?></td>
                    <td><?php echo $pay['payment_type']; ?></td>
                    <td><?php echo $pay['hours_worked'] > 0 ? $pay['hours_worked'].' time' : '-'; ?></td>
                    <td>RM<?php echo number_format($pay['amount'], 2); ?></td>
                    <td><span style="background:#d1fae5; padding:4px 10px; border-radius:20px; font-size:12px;">✅ Completed</span></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($pay['paid_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
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