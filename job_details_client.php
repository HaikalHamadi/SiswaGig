<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];

$job_id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT j.*, u.name AS freelancer_name, u.hourly_rate AS freelancer_rate, u.id AS freelancer_id,
    a.proof_image AS proof_image
    FROM jobs j
    LEFT JOIN applications a ON a.job_id = j.id AND a.status = 'accepted'
    LEFT JOIN users u ON u.id = a.freelancer_id
    WHERE j.id='$job_id' AND j.user_id='$user_id'");

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Job not found!'); window.location='MainMenu_MyJobs.php';</script>";
    exit();
}
$job = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Job Details</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .btn-back { background:#e9ecef; color:#0d3b66; border:none; border-radius:30px; padding:10px 24px; font-size:14px; font-weight:bold; cursor:pointer; margin-bottom:20px; text-decoration:none; display:inline-block; }
        .btn-complete { background:linear-gradient(135deg,#16a34a,#15803d); color:white; border:none; border-radius:30px; padding:14px 40px; font-size:16px; font-weight:bold; cursor:pointer; width:100%; }
        .btn-complete:hover { opacity:0.9; }
        .btn-reject-proof { background:#dc2626; color:white; border:none; border-radius:30px; padding:14px 20px; font-size:14px; font-weight:bold; cursor:pointer; white-space:nowrap; }
        .btn-reject-proof:hover { background:#b91c1c; }
        .status-badge { padding:6px 16px; border-radius:20px; font-size:14px; font-weight:bold; display:inline-block; margin-top:5px; }
        .status-open { background:#fff3cd; color:#856404; }
        .status-in_progress { background:#cfe2ff; color:#0a58ca; }
        .status-completed { background:#d1fae5; color:#065f46; }

        .section-title { font-size:16px; font-weight:bold; color:#1e3a5f; border-left:4px solid #f97316; padding-left:10px; margin:20px 0 12px; }
        .proof-preview { margin-top:10px; }
        .proof-preview img { max-width:100%; border-radius:10px; border:1px solid #ddd; }
        .proof-preview a { color:#1e6091; font-weight:bold; text-decoration:none; }
        .proof-preview a:hover { text-decoration:underline; }

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
        <a href="MainMenu_Post.php" class="nav-item">📝 Post Job</a>
        <a href="MainMenu_MyJobs.php" class="nav-item active">📋 My Job</a>
        <a href="MainMenu_Profile.php" class="nav-item">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card">
        <a href="MainMenu_MyJobs.php" class="btn-back">← Back</a>

        <h2>📄 <?php echo htmlspecialchars($job['title']); ?></h2>

        <div class="detail-section">
            <div class="detail-row">
                <span class="detail-label">📊 Status:</span>
                <?php
                $status = $job['status'];
                $badge = $status == 'open' ? 'status-open' : ($status == 'in_progress' ? 'status-in_progress' : 'status-completed');
                $label = $status == 'open' ? '🟡 Open' : ($status == 'in_progress' ? '🔵 In Progress' : '✅ Completed');
                echo "<span class='status-badge $badge'>$label</span>";
                ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">📝 Description:</span>
                <?php echo htmlspecialchars($job['description']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">🔍 Details:</span>
                <?php echo htmlspecialchars($job['details'] ?? 'None'); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">⚙️ Requirements:</span>
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
            <?php if ($job['freelancer_name']): ?>
            <div class="detail-row">
                <span class="detail-label">👷 Freelancer:</span>
                <?php echo htmlspecialchars($job['freelancer_name']); ?>
                <?php if ($job['payment_type'] == 'Per hour'): ?>
                    (RM<?php echo $job['freelancer_rate']; ?>/hour)
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- PROOF OF WORK (client view - read only) -->
        <?php if ($job['freelancer_name']): ?>
        <div class="section-title">📎 Proof of Work</div>
        <?php if ($job['proof_image']): ?>
            <div class="proof-preview">
                <?php
                $proof_path = 'uploads/proof/' . $job['proof_image'];
                $ext = strtolower(pathinfo($job['proof_image'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                    <a href="<?php echo $proof_path; ?>" target="_blank">
                        <img src="<?php echo $proof_path; ?>" alt="Proof of Work">
                    </a>
                <?php else: ?>
                    <a href="<?php echo $proof_path; ?>" target="_blank">📄 View PDF Proof</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p style="color:#999; font-size:14px;">No proof uploaded yet by the freelancer.</p>
        <?php endif; ?>
        <?php endif; ?>

        <!-- Button ikut status -->
        <?php if ($job['status'] == 'in_progress' && $job['freelancer_name']): ?>
            <div style="display:flex; gap:10px; margin-top:15px;">
                <form action="proses_complete_job.php" method="POST" onsubmit="return confirm('Confirm the work is complete?')" style="flex:1; margin:0;">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <input type="hidden" name="freelancer_id" value="<?php echo $job['freelancer_id']; ?>">
                    <input type="hidden" name="payment_type" value="<?php echo $job['payment_type']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $job['amount']; ?>">
                    <input type="hidden" name="freelancer_rate" value="<?php echo $job['freelancer_rate']; ?>">
                    <button type="submit" class="btn-complete" style="margin-top:0;">✅ Work Completed → Proceed Payment</button>
                </form>
                <button type="button" class="btn-reject-proof" onclick="openRejectProofModal()">❌ Reject</button>
            </div>
        <?php elseif ($job['status'] == 'completed'): ?>
            <div style="background:#d1fae5; padding:15px; border-radius:12px; text-align:center; margin-top:15px;">
                ✅ This work has been completed and payment has been made.
            </div>
        <?php elseif ($job['status'] == 'open'): ?>
            <div style="background:#fff3cd; padding:15px; border-radius:12px; text-align:center; margin-top:15px;">
                ⏳ Still waiting for the freelancer to be accepted.
            </div>
        <?php endif; ?>
    </div>

    <!-- REJECT PROOF MODAL -->
    <div id="rejectProofModal" class="modal-overlay">
        <div class="modal-box">
            <h3>❌ Reject Proof of Work</h3>
            <p class="modal-info">This will reopen the job and unassign the current freelancer.</p>
            <label>Reason for rejection <span style="color:red;">*</span></label>
            <textarea id="rejectProofComment" rows="4" placeholder="Please provide a reason for rejecting this proof of work..."></textarea>
            <p class="modal-error" id="rejectProofError">⚠️ Please fill in the reason before rejecting.</p>
            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="closeRejectProofModal()">Cancel</button>
                <button class="btn-modal-reject" onclick="submitRejectProof()">❌ Reject</button>
            </div>
        </div>
    </div>

    <!-- Hidden form untuk submit reject -->
    <form id="rejectProofForm" action="proses_reject_proof.php" method="POST">
        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
        <input type="hidden" name="reject_comment" id="rejectProofCommentHidden">
    </form>

</div>

<script>
function openRejectProofModal() {
    document.getElementById('rejectProofComment').value = '';
    document.getElementById('rejectProofError').style.display = 'none';
    document.getElementById('rejectProofModal').classList.add('show');
}

function closeRejectProofModal() {
    document.getElementById('rejectProofModal').classList.remove('show');
}

function submitRejectProof() {
    var comment = document.getElementById('rejectProofComment').value.trim();
    if (comment === '') {
        document.getElementById('rejectProofError').style.display = 'block';
        return;
    }
    document.getElementById('rejectProofCommentHidden').value = comment;
    document.getElementById('rejectProofForm').submit();
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