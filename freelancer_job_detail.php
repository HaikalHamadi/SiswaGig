<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

if ($role != 'freelancer') {
    header("Location: MainMenu_MyJobs.php");
    exit();
}

$app_id = $_GET['id'];

$result = mysqli_query($conn, "SELECT a.*, j.title, j.description, j.details, j.requirement, 
    j.platform, j.datetime, j.payment_type, j.duration, j.amount, j.status AS job_status,
    u.name AS client_name
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN users u ON j.user_id = u.id
    WHERE a.id='$app_id' AND a.freelancer_id='$user_id'");

if (mysqli_num_rows($result) == 0) {
    header("Location: MainMenu_MyJobs.php");
    exit();
}

$data = mysqli_fetch_assoc($result);

$upload_msg = '';
$upload_err = '';
if (isset($_POST['upload_proof'])) {
    if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $filename = $_FILES['proof_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $size = $_FILES['proof_image']['size'];

        if (!in_array($ext, $allowed)) {
            $upload_err = 'Only JPG, PNG, or PDF allowed.';
        } elseif ($size > 5 * 1024 * 1024) {
            $upload_err = 'File size must be under 5MB.';
        } else {
            $new_filename = 'proof_' . $app_id . '_' . time() . '.' . $ext;
            $upload_path = 'uploads/proof/' . $new_filename;

            if (!is_dir('uploads/proof')) {
                mkdir('uploads/proof', 0777, true);
            }

            if (move_uploaded_file($_FILES['proof_image']['tmp_name'], $upload_path)) {
                mysqli_query($conn, "UPDATE applications SET proof_image='$new_filename' WHERE id='$app_id'");
                $upload_msg = 'Proof uploaded successfully!';
                $data['proof_image'] = $new_filename;
            } else {
                $upload_err = 'Upload failed. Please try again.';
            }
        }
    } else {
        $upload_err = 'Please select a file to upload.';
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Job Details</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .detail-row { display:flex; gap:10px; margin-bottom:10px; flex-wrap:wrap; }
        .detail-label { font-weight:bold; color:#1e3a5f; min-width:140px; }
        .detail-value { color:#444; }
        .section-title { font-size:16px; font-weight:bold; color:#1e3a5f; border-left:4px solid #f97316; padding-left:10px; margin:20px 0 12px; }
        .upload-box { border:2px dashed #ccc; border-radius:12px; padding:20px; text-align:center; background:#fafafa; margin-top:10px; }
        .upload-box:hover { border-color:#f97316; background:#fff8f0; }
        .btn-upload { background:#f97316; color:white; border:none; border-radius:20px; padding:8px 24px; font-size:14px; cursor:pointer; font-weight:bold; margin-top:10px; }
        .btn-upload:hover { background:#ea6c00; }
        .btn-back { background:#1e6091; color:white; border:none; border-radius:20px; padding:8px 20px; font-size:14px; cursor:pointer; font-weight:bold; text-decoration:none; display:inline-block; margin-bottom:16px; }
        .btn-back:hover { background:#0d3b66; color:white; }
        .alert-success { background:#d1fae5; color:#065f46; padding:10px 16px; border-radius:10px; margin-bottom:16px; font-size:14px; }
        .alert-error { background:#fee2e2; color:#991b1b; padding:10px 16px; border-radius:10px; margin-bottom:16px; font-size:14px; }
        .proof-preview { margin-top:16px; }
        .proof-preview img { max-width:100%; border-radius:10px; border:1px solid #ddd; }
        .proof-preview a { color:#1e6091; font-weight:bold; }
        input[type="file"] { display:none; }
        .file-label { display:inline-block; background:#e9e9e9; border-radius:20px; padding:8px 20px; font-size:14px; cursor:pointer; margin-top:8px; }
        .file-label:hover { background:#ddd; }
        #file-name { font-size:13px; color:#666; margin-top:8px; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <a href="MainMenu_Browse.php" class="nav-item">🔍 Search Job</a>
        <a href="MainMenu_MyJobs.php" class="nav-item active">📋 My Work</a>
        <a href="MainMenu_Profile.php" class="nav-item">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card">
        <a href="MainMenu_MyJobs.php" class="btn-back">← Back</a>

        <?php if ($upload_msg): ?>
            <div class="alert-success">✅ <?php echo $upload_msg; ?></div>
        <?php endif; ?>
        <?php if ($upload_err): ?>
            <div class="alert-error">❌ <?php echo $upload_err; ?></div>
        <?php endif; ?>
		<?php if ($data['status'] == 'rejected'): ?>
		<div style="background:#fee2e2; border-left:4px solid #dc2626; padding:14px 16px; border-radius:10px; margin-bottom:20px;">
			<p style="color:#991b1b; font-weight:bold; margin:0 0 6px;">❌ Your application was rejected</p>
			<p style="color:#7f1d1d; font-size:14px; margin:0;"><?php echo nl2br(htmlspecialchars($data['reject_reason'])); ?></p>
		</div>
		<?php endif; ?>

        <!-- JOB INFO -->
        <div class="section-title">📋 Job Information</div>
        <div class="detail-row"><span class="detail-label">Job Title</span><span class="detail-value"><?php echo htmlspecialchars($data['title']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Client</span><span class="detail-value"><?php echo htmlspecialchars($data['client_name']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Platform</span><span class="detail-value"><?php echo htmlspecialchars($data['platform']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Payment Type</span><span class="detail-value"><?php echo ucfirst($data['payment_type']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Budget</span><span class="detail-value">RM<?php echo $data['amount']; ?></span></div>
        <div class="detail-row"><span class="detail-label">Duration</span><span class="detail-value"><?php echo htmlspecialchars($data['duration']); ?></span></div>
        <div class="detail-row"><span class="detail-label">Deadline</span><span class="detail-value"><?php echo htmlspecialchars($data['datetime']); ?></span></div>

        <?php
        $s = $data['job_status'];
        $bg = $s == 'open' ? '#fff3cd' : ($s == 'in_progress' ? '#cfe2ff' : '#d1fae5');
        $label = $s == 'open' ? '🟡 Open' : ($s == 'in_progress' ? '🔵 In Progress' : '✅ Completed');
        ?>
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span style="background:<?php echo $bg; ?>; padding:4px 12px; border-radius:20px; font-size:13px;"><?php echo $label; ?></span>
        </div>

        <div class="section-title">📝 Description</div>
        <p style="color:#444; line-height:1.6;"><?php echo nl2br(htmlspecialchars($data['description'])); ?></p>

        <?php if ($data['details']): ?>
        <div class="section-title">📌 Details</div>
        <p style="color:#444; line-height:1.6;"><?php echo nl2br(htmlspecialchars($data['details'])); ?></p>
        <?php endif; ?>

        <?php if ($data['requirement']): ?>
        <div class="section-title">✅ Requirements</div>
        <p style="color:#444; line-height:1.6;"><?php echo nl2br(htmlspecialchars($data['requirement'])); ?></p>
        <?php endif; ?>

        <!-- PROOF OF WORK -->
        <div class="section-title">📎 Proof of Work</div>

        <?php if ($data['proof_image']): ?>
            <div class="proof-preview">
                <p style="color:#16a34a; font-weight:bold; margin-bottom:10px;">✅ Proof already uploaded:</p>
                <?php
                $proof_path = 'uploads/proof/' . $data['proof_image'];
                $ext = strtolower(pathinfo($data['proof_image'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                    <img src="<?php echo $proof_path; ?>" alt="Proof of Work">
                <?php else: ?>
                    <a href="<?php echo $proof_path; ?>" target="_blank">📄 View PDF Proof</a>
                <?php endif; ?>
            </div>
            <p style="color:#666; font-size:13px; margin-top:12px;">You can replace the proof by uploading a new file below.</p>
        <?php else: ?>
            <p style="color:#999; font-size:14px;">No proof uploaded yet.</p>
        <?php endif; ?>

       <?php if ($data['job_status'] != 'completed' && $data['status'] != 'rejected'): ?>
<form action="freelancer_job_detail.php?id=<?php echo $app_id; ?>" method="POST" enctype="multipart/form-data">
    ...
</form>
<?php elseif ($data['status'] == 'rejected'): ?>
    <p style="color:#666; font-size:13px; margin-top:8px;">Your application was rejected — proof upload is not available.</p>
<?php else: ?>
    <p style="color:#666; font-size:13px; margin-top:8px;">Job is completed — proof upload is closed.</p>
<?php endif; ?>

    </div>
</div>

<script>
function showFileName(input) {
    var name = input.files[0] ? input.files[0].name : 'No file selected';
    document.getElementById('file-name').textContent = name;
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