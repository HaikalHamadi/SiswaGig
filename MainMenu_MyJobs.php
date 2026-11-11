<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

$posted_jobs = mysqli_query($conn, "SELECT * FROM jobs WHERE user_id='$user_id' ORDER BY id DESC");

$applications = mysqli_query($conn, "SELECT a.*, j.title AS job_title, u.name AS freelancer_name, u.id AS freelancer_user_id
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    JOIN users u ON a.freelancer_id = u.id
    WHERE j.user_id='$user_id' 
    ORDER BY a.id DESC");

$applied_jobs = mysqli_query($conn, "SELECT a.*, j.title, j.amount, j.platform 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    WHERE a.freelancer_id='$user_id' 
    ORDER BY a.id DESC");

$posted_count = mysqli_num_rows($posted_jobs);
$app_count = mysqli_num_rows($applications);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - My Job</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .tab-wrapper { display:flex; background:#e9e9e9; border-radius:50px; padding:4px; gap:4px; margin-bottom:20px; }
        .tab-btn { flex:1; padding:10px 16px; border:none; border-radius:50px; font-size:14px; font-weight:bold; cursor:pointer; transition:all 0.2s; background:transparent; color:#555; }
        .tab-btn.active { background:#f97316; color:white; }
        .tab-btn:hover:not(.active) { background:#ddd; }
        .tab-content { display:none; }
        .tab-content.active { display:block; }
        .btn-delete { background:#dc2626; color:white; border:none; border-radius:20px; padding:6px 16px; font-size:13px; cursor:pointer; font-weight:bold; width:100%; }
        .btn-delete:hover { background:#b91c1c; }
        .btn-accept { background:#16a34a; color:white; border:none; border-radius:20px; padding:6px 16px; font-size:13px; cursor:pointer; font-weight:bold; width:100%; }
        .btn-accept:hover { background:#15803d; }
        .btn-reject { background:#dc2626; color:white; border:none; border-radius:20px; padding:6px 16px; font-size:13px; cursor:pointer; font-weight:bold; width:100%; display:block; text-align:center; box-sizing:border-box; }
        .btn-reject:hover { background:#b91c1c; }
        .btn-profile { background:#1e6091; color:white; border:none; border-radius:20px; padding:6px 16px; font-size:13px; cursor:pointer; font-weight:bold; text-decoration:none; display:inline-block; width:100%; text-align:center; box-sizing:border-box; }
        .btn-profile:hover { background:#0d3b66; color:white; }
        .action-btns { display:flex; flex-direction:column; gap:5px; align-self:center; min-width:90px; }
        .app-actions { display:flex; flex-direction:column; gap:5px; align-self:center; min-width:110px; }

        /* MODAL */
        .modal-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center; }
        .modal-overlay.show { display:flex; }
        .modal-box { background:white; border-radius:16px; padding:28px; width:90%; max-width:420px; }
        .modal-box h3 { margin:0 0 6px; color:#1e3a5f; font-size:18px; }
        .modal-box p.modal-info { color:#666; font-size:13px; margin:0 0 16px; }
        .modal-box label { font-size:14px; font-weight:bold; color:#333; }
        .modal-box textarea { width:100%; margin-top:8px; padding:10px; border:1px solid #ccc; border-radius:10px; font-size:14px; resize:none; box-sizing:border-box; font-family:inherit; }
        .modal-box textarea:focus { outline:none; border-color:#f97316; }
        .modal-error { color:red; font-size:13px; margin:6px 0 0; display:none; }
        .modal-actions { display:flex; gap:10px; margin-top:16px; }
        .btn-modal-cancel { flex:1; padding:10px; border:1px solid #ccc; border-radius:20px; background:white; cursor:pointer; font-size:14px; }
        .btn-modal-cancel:hover { background:#f5f5f5; }
        .btn-modal-reject { flex:1; padding:10px; border:none; border-radius:20px; background:#dc2626; color:white; cursor:pointer; font-size:14px; font-weight:bold; }
        .btn-modal-reject:hover { background:#b91c1c; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <?php if ($role == 'client'): ?>
            <a href="MainMenu_Post.php" class="nav-item">📝 Post Job</a>
            <span class="nav-item active">📋 My Job</span>
        <?php else: ?>
            <a href="MainMenu_Browse.php" class="nav-item">🔍 Search Job</a>
            <span class="nav-item active">📋 My Work</span>
        <?php endif; ?>
        <a href="MainMenu_Profile.php" class="nav-item">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <?php if ($role == 'client'): ?>
    <div class="card">

        <!-- TAB BUTTONS -->
        <div class="tab-wrapper">
            <button class="tab-btn active" onclick="switchTab('posted', this)">
                📌 Posted Job (<?php echo $posted_count; ?>)
            </button>
            <button class="tab-btn" onclick="switchTab('applications', this)">
                📬 Entry Application (<?php echo $app_count; ?>)
            </button>
        </div>

        <!-- TAB: POSTED JOBS -->
        <div id="tab-posted" class="tab-content active">
            <?php if ($posted_count == 0): ?>
                <p style="color:#999;">You have not posted any jobs. <a href="MainMenu_Post.php">Post now</a></p>
            <?php else: ?>
                <?php while ($job = mysqli_fetch_assoc($posted_jobs)): ?>
                <div class="job-card">
                    <div class="flex-between" style="flex-wrap:wrap; gap:10px;">
                        <div>
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p style="color:#666; margin:5px 0;">Budget: RM<?php echo $job['amount']; ?> | Platform: <?php echo $job['platform']; ?></p>
                            <?php
                            $s = $job['status'];
                            $bg = $s == 'open' ? '#fff3cd' : ($s == 'in_progress' ? '#cfe2ff' : '#d1fae5');
                            $label = $s == 'open' ? '🟡 Open' : ($s == 'in_progress' ? '🔵 In Progress' : '✅ Completed');
                            echo "<span style='background:$bg; padding:4px 12px; border-radius:20px; font-size:13px;'>$label</span>";
                            ?>
                        </div>
                        <div class="action-btns">
                            <a href="job_details_client.php?id=<?php echo $job['id']; ?>" class="btn-profile">📋 Details</a>
                            <?php if ($job['status'] == 'open'): ?>
                            <form action="proses_delete_job.php" method="POST" onsubmit="return confirm('Want to delete this job?')">
                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                <button type="submit" class="btn-delete">🗑️ Delete</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <!-- TAB: ENTRY APPLICATIONS -->
        <div id="tab-applications" class="tab-content">
            <?php if ($app_count == 0): ?>
                <p style="color:#999;">No applications received yet.</p>
            <?php else: ?>
                <?php while ($app = mysqli_fetch_assoc($applications)): ?>
                <div class="job-card">
                    <div class="flex-between" style="flex-wrap:wrap; gap:10px;">
                        <div>
                            <h3>📌 <?php echo htmlspecialchars($app['job_title']); ?></h3>
                            <p style="margin:5px 0;"><strong>Freelancer:</strong> <?php echo htmlspecialchars($app['freelancer_name']); ?></p>
                            <?php
							$cbadge = $app['status'] == 'accepted' ? '#d1fae5' : ($app['status'] == 'rejected' ? '#fee2e2' : '#fff3cd');
							$clabel = $app['status'] == 'accepted' ? '✅ Accepted' : ($app['status'] == 'rejected' ? '❌ Rejected' : '⏳ Waiting');
							?>
							<span style="background:<?php echo $cbadge; ?>; padding:4px 12px; border-radius:20px; font-size:13px;">
								<?php echo $clabel; ?>
							</span>
                        </div>
                        <div class="app-actions">
                            <a href="freelancer_profile.php?id=<?php echo $app['freelancer_user_id']; ?>" class="btn-profile">👤 View Profile</a>
                            <?php if ($app['status'] == 'pending'): ?>
                            <form action="proses_accept.php" method="POST">
                                <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                <button type="submit" class="btn-accept">✅ Accept</button>
                            </form>
                            <button class="btn-reject" onclick="openRejectModal(<?php echo $app['id']; ?>, '<?php echo addslashes(htmlspecialchars($app['freelancer_name'])); ?>', '<?php echo addslashes(htmlspecialchars($app['job_title'])); ?>')">❌ Reject</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

    </div>

    <?php else: ?>
    <!-- FREELANCER -->
    <div class="card">
        <h2>📋 Job Requested (<?php echo mysqli_num_rows($applied_jobs); ?>)</h2>
        <?php if (mysqli_num_rows($applied_jobs) == 0): ?>
            <p style="color:#999;">You have not applied for any jobs. <a href="MainMenu_Browse.php">Find a job now</a></p>
        <?php else: ?>
            <?php while ($app = mysqli_fetch_assoc($applied_jobs)): ?>
            <div class="job-card">
                <div class="flex-between" style="flex-wrap:wrap; gap:10px;">
				<div>
					<h3><?php echo htmlspecialchars($app['title']); ?></h3>
					<p style="color:#666; margin:5px 0;">Budget: RM<?php echo $app['amount']; ?> | Platform: <?php echo $app['platform']; ?></p>
					<?php
					$fbadge = $app['status'] == 'accepted' ? '#d1fae5' : ($app['status'] == 'rejected' ? '#fee2e2' : '#fff3cd');
					$flabel = $app['status'] == 'accepted' ? '✅ Accepted' : ($app['status'] == 'rejected' ? '❌ Rejected' : '⏳ Pending');
					?>
					<span style="background:<?php echo $fbadge; ?>; padding:4px 12px; border-radius:20px; font-size:13px;">
						<?php echo $flabel; ?>
					</span>
				</div>
				<div class="action-btns">
					<a href="freelancer_job_detail.php?id=<?php echo $app['id']; ?>" class="btn-profile">📋 Details</a>
				</div>
			</div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- REJECT MODAL -->
    <div id="rejectModal" class="modal-overlay">
        <div class="modal-box">
            <h3>❌ Reject Application</h3>
            <p class="modal-info" id="rejectInfo"></p>
            <label>Reason for rejection <span style="color:red;">*</span></label>
            <textarea id="rejectComment" rows="4" placeholder="Please provide a reason for rejecting this application..."></textarea>
            <p class="modal-error" id="rejectError">⚠️ Please fill in the reason before rejecting.</p>
            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="closeRejectModal()">Cancel</button>
                <button class="btn-modal-reject" onclick="submitReject()">❌ Reject</button>
            </div>
        </div>
    </div>

    <!-- Hidden form untuk submit reject -->
    <form id="rejectForm" action="proses_reject.php" method="POST">
        <input type="hidden" name="application_id" id="rejectAppId">
        <input type="hidden" name="reject_comment" id="rejectCommentHidden">
    </form>

</div>

<script>
function switchTab(tab, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
}

function openRejectModal(appId, freelancerName, jobTitle) {
    document.getElementById('rejectAppId').value = appId;
    document.getElementById('rejectComment').value = '';
    document.getElementById('rejectError').style.display = 'none';
    document.getElementById('rejectInfo').textContent = 'Freelancer: ' + freelancerName + ' | Job: ' + jobTitle;
    document.getElementById('rejectModal').classList.add('show');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
}

function submitReject() {
    var comment = document.getElementById('rejectComment').value.trim();
    if (comment === '') {
        document.getElementById('rejectError').style.display = 'block';
        return;
    }
    document.getElementById('rejectCommentHidden').value = comment;
    document.getElementById('rejectForm').submit();
}

function confirmLogout() {
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'logout.php';
    }
    return false;
}
</script>
</body>
</html>