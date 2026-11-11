<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';

$jobs = mysqli_query($conn, "SELECT j.*, u.name AS client_name FROM jobs j JOIN users u ON j.user_id = u.id ORDER BY j.id DESC");
$jobs_open = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs WHERE status='open'"))['total'];
$jobs_progress = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs WHERE status='in_progress'"))['total'];
$jobs_completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs WHERE status='completed'"))['total'];
$jobs_total = $jobs_open + $jobs_progress + $jobs_completed;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Admin Job</title>
    <link rel="stylesheet" href="gpCSS.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <style>
        .admin-navbar { display:flex; justify-content:space-around; align-items:center; background:white; padding:15px 20px; border-radius:60px; margin-bottom:30px; flex-wrap:wrap; }
        .nav-item { text-decoration:none; color:#0d3b66; padding:8px 16px; border-radius:30px; cursor:pointer; font-weight:500; }
        .nav-item:hover, .nav-item.active { background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; }
        .btn-del { background:#dc2626; color:white; border:none; border-radius:20px; padding:5px 14px; font-size:12px; cursor:pointer; font-weight:bold; }
        .status-badge { padding:4px 10px; border-radius:20px; font-size:12px; font-weight:bold; }
        .status-open { background:#fff3cd; color:#856404; }
        .status-in_progress { background:#cfe2ff; color:#0a58ca; }
        .status-completed { background:#d1fae5; color:#065f46; }
        .chart-container { display:flex; justify-content:center; align-items:center; gap:30px; flex-wrap:wrap; margin-bottom:20px; }
        .chart-box { background:white; border-radius:16px; padding:20px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
        .chart-legend { display:flex; flex-direction:column; gap:10px; justify-content:center; }
        .legend-item { display:flex; align-items:center; gap:10px; font-size:14px; font-weight:600; }
        .legend-dot { width:16px; height:16px; border-radius:50%; display:inline-block; }
		.btn-edit { background:#1e6091; color:white; border:none; border-radius:20px; padding:5px 14px; font-size:12px; cursor:pointer; font-weight:bold; text-decoration:none; display:inline-block; }
		.btn-edit:hover { background:#0d3b66; color:white; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="admin-navbar">
        <a href="admin_dashboard.php" class="nav-item">📊 Dashboard</a>
        <a href="admin_users.php" class="nav-item">👥 Users</a>
        <span class="nav-item active">💼 Jobs</span>
        <a href="admin_payments.php" class="nav-item">💳 Payment</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <!-- Pie Chart -->
    <div class="card">
        <h2>📊 Job Status Distribution</h2>
        <div class="chart-container">
            <div class="chart-box">
                <canvas id="jobChart" width="250" height="250"></canvas>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <span class="legend-dot" style="background:#f59e0b;"></span>
                    <span>Open: <strong><?php echo $jobs_open; ?></strong> jobs</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#3b82f6;"></span>
                    <span>In Progress: <strong><?php echo $jobs_progress; ?></strong> jobs</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#16a34a;"></span>
                    <span>Completed: <strong><?php echo $jobs_completed; ?></strong> jobs</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#e9ecef;"></span>
                    <span>Total: <strong><?php echo $jobs_total; ?></strong> jobs</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Table -->
    <div class="card">
        <h2>💼 Job Management (<?php echo mysqli_num_rows($jobs); ?>)</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Client</th>
                    <th>Budget</th>
                    <th>Platform</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($job = mysqli_fetch_assoc($jobs)): ?>
                <tr>
                    <td><?php echo $job['id']; ?></td>
                    <td><?php echo htmlspecialchars($job['title']); ?></td>
                    <td><?php echo htmlspecialchars($job['client_name']); ?></td>
                    <td>RM<?php echo $job['amount']; ?></td>
                    <td><?php echo $job['platform']; ?></td>
                    <td>
                        <?php
                        $s = $job['status'];
                        $badge = $s == 'open' ? 'status-open' : ($s == 'in_progress' ? 'status-in_progress' : 'status-completed');
                        $label = $s == 'open' ? '🟡 Open' : ($s == 'in_progress' ? '🔵 In Progress' : '✅ Completed');
                        echo "<span class='status-badge $badge'>$label</span>";
                        ?>
                    </td>
                    <td><?php echo date('d/m/Y', strtotime($job['created_at'])); ?></td>
                    <td>
						<div style="display:flex; gap:6px;">
							<a href="admin_edit_job.php?id=<?php echo $job['id']; ?>" class="btn-edit">✏️ Edit</a>
							<form action="admin_delete_job.php" method="POST" onsubmit="return confirm('Delete This Job?')" style="margin:0;">
									<input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
									<button type="submit" class="btn-del">🗑️ Delete</button>
							</form>
						</div>
					</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
var ctx = document.getElementById('jobChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Open', 'In Progress', 'Completed'],
        datasets: [{
            data: [<?php echo $jobs_open; ?>, <?php echo $jobs_progress; ?>, <?php echo $jobs_completed; ?>],
            backgroundColor: ['#f59e0b', '#3b82f6', '#16a34a'],
            borderColor: ['#fff', '#fff', '#fff'],
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
                        var total = <?php echo $jobs_total; ?>;
                        var value = context.parsed;
                        var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
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