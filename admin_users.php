<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$users = mysqli_query($conn, "SELECT * FROM users WHERE role != 'admin' ORDER BY id DESC");
$total_freelancers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='freelancer'"))['total'];
$total_clients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='client'"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Admin User</title>
    <link rel="stylesheet" href="gpCSS.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <style>
        .admin-navbar { display:flex; justify-content:space-around; align-items:center; background:white; padding:15px 20px; border-radius:60px; margin-bottom:30px; flex-wrap:wrap; }
        .nav-item { text-decoration:none; color:#0d3b66; padding:8px 16px; border-radius:30px; cursor:pointer; font-weight:500; }
        .nav-item:hover, .nav-item.active { background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; }
        .btn-del { background:#dc2626; color:white; border:none; border-radius:20px; padding:5px 14px; font-size:12px; cursor:pointer; font-weight:bold; }
        .role-badge { padding:4px 10px; border-radius:20px; font-size:12px; font-weight:bold; }
        .role-freelancer { background:#cfe2ff; color:#0a58ca; }
        .role-client { background:#d1fae5; color:#065f46; }
        .chart-container { display:flex; justify-content:center; align-items:center; gap:30px; flex-wrap:wrap; margin-bottom:20px; }
        .chart-box { background:white; border-radius:16px; padding:20px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
        .chart-legend { display:flex; flex-direction:column; gap:10px; justify-content:center; }
        .legend-item { display:flex; align-items:center; gap:10px; font-size:14px; font-weight:600; }
        .legend-dot { width:16px; height:16px; border-radius:50%; display:inline-block; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="admin-navbar">
        <a href="admin_dashboard.php" class="nav-item">📊 Dashboard</a>
        <span class="nav-item active">👥 Users</span>
        <a href="admin_jobs.php" class="nav-item">💼 Jobs</a>
        <a href="admin_payments.php" class="nav-item">💳 Payment</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <!-- Pie Chart -->
    <div class="card">
        <h2>📊 User Role Distribution</h2>
        <div class="chart-container">
            <div class="chart-box">
                <canvas id="roleChart" width="250" height="250"></canvas>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <span class="legend-dot" style="background:#ff7b00;"></span>
                    <span>Freelancer: <strong><?php echo $total_freelancers; ?></strong> users</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#0d3b66;"></span>
                    <span>Client: <strong><?php echo $total_clients; ?></strong> users</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#e9ecef;"></span>
                    <span>Total: <strong><?php echo $total_freelancers + $total_clients; ?></strong> users</span>
                </div>
            </div>
        </div>
    </div>

    <!-- User Table -->
    <div class="card">
        <h2>👥 User Management (<?php echo mysqli_num_rows($users); ?>)</h2>
        <table class="data-table">
            <thead>
				<tr>
					<th>ID</th>
					<th>Photo</th>
					<th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Skills</th>
                    <th>Rate/Hour</th>
                    <th>Registration</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users)): ?>
                <tr>
    <td><?php echo $user['id']; ?></td>
    <td>
        <?php if (!empty($user['profile_image'])): ?>
            <img src="uploads/profile/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Photo" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:1px solid #ddd;">
        <?php else: ?>
            <div style="width:36px; height:36px; border-radius:50%; background:#e9ecef; display:flex; align-items:center; justify-content:center; color:#aaa; font-size:16px;">👤</div>
        <?php endif; ?>
    </td>
    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="role-badge <?php echo $user['role'] == 'freelancer' ? 'role-freelancer' : 'role-client'; ?>">
                            <?php echo $user['role'] == 'freelancer' ? '💼 Freelancer' : '🏢 Client'; ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($user['skills'] ?? '-'); ?></td>
                    <td><?php echo $user['hourly_rate'] ? 'RM'.$user['hourly_rate'] : '-'; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <form action="admin_delete_user.php" method="POST" onsubmit="return confirm('Delete this user?')">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn-del">🗑️ Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Pie Chart
var ctx = document.getElementById('roleChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Freelancer', 'Client'],
        datasets: [{
            data: [<?php echo $total_freelancers; ?>, <?php echo $total_clients; ?>],
            backgroundColor: ['#ff7b00', '#0d3b66'],
            borderColor: ['#fff', '#fff'],
            borderWidth: 3
        }]
    },
    options: {
        responsive: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        var total = <?php echo $total_freelancers + $total_clients; ?>;
                        var value = context.parsed;
                        var percentage = ((value / total) * 100).toFixed(1);
                        return context.label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

function confirmLogout() {
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'logout.php';
    }
    return false;
}
</script>
</body>
</html>