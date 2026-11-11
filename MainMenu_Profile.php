<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];

$upload_msg = '';
$upload_err = '';
if (isset($_POST['upload_profile'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $size = $_FILES['profile_image']['size'];

        if (!in_array($ext, $allowed)) {
            $upload_err = 'Only JPG or PNG allowed.';
        } elseif ($size > 5 * 1024 * 1024) {
            $upload_err = 'File size must be under 5MB.';
        } else {
            $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $ext;
            $upload_path = 'uploads/profile/' . $new_filename;

            if (!is_dir('uploads/profile')) {
                mkdir('uploads/profile', 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                mysqli_query($conn, "UPDATE users SET profile_image='$new_filename' WHERE id='$user_id'");
                $upload_msg = 'Profile picture updated!';
            } else {
                $upload_err = 'Upload failed. Please try again.';
            }
        }
    } else {
        $upload_err = 'Please select a file to upload.';
    }
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Profile</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .avatar-box { display:flex; flex-direction:column; align-items:center; margin-bottom:20px; }
        .avatar-img { width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid #f97316; background:#eee; }
        .avatar-placeholder { width:120px; height:120px; border-radius:50%; background:#e9ecef; display:flex; align-items:center; justify-content:center; font-size:48px; color:#aaa; border:3px solid #f97316; }
        .alert-success { background:#d1fae5; color:#065f46; padding:10px 16px; border-radius:10px; margin-bottom:16px; font-size:14px; }
        .alert-error { background:#fee2e2; color:#991b1b; padding:10px 16px; border-radius:10px; margin-bottom:16px; font-size:14px; }
        input[type="file"] { display:none; }
        .file-label { display:inline-block; background:#e9e9e9; border-radius:20px; padding:8px 20px; font-size:13px; cursor:pointer; margin-top:10px; }
        .file-label:hover { background:#ddd; }
        #file-name { font-size:12px; color:#666; margin-top:6px; }
        .btn-upload-pic { background:#f97316; color:white; border:none; border-radius:20px; padding:8px 24px; font-size:13px; cursor:pointer; font-weight:bold; margin-top:10px; }
        .btn-upload-pic:hover { background:#ea6c00; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <?php if ($user['role'] == 'client'): ?>
            <a href="MainMenu_Post.php" class="nav-item">📝 Post Job</a>
            <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Job</a>
        <?php else: ?>
            <a href="MainMenu_Browse.php" class="nav-item">🔍 Search Job</a>
            <a href="MainMenu_MyJobs.php" class="nav-item">📋 My work</a>
        <?php endif; ?>
        <span class="nav-item active">👤 Profile</span>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>
    <div class="card">
        <h2>👤 My Profile</h2>

        <?php if ($upload_msg): ?>
            <div class="alert-success">✅ <?php echo $upload_msg; ?></div>
        <?php endif; ?>
        <?php if ($upload_err): ?>
            <div class="alert-error">❌ <?php echo $upload_err; ?></div>
        <?php endif; ?>

        <div class="avatar-box">
            <?php if ($user['profile_image']): ?>
                <img class="avatar-img" src="uploads/profile/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture">
            <?php else: ?>
                <div class="avatar-placeholder">👤</div>
            <?php endif; ?>

            <form action="MainMenu_Profile.php" method="POST" enctype="multipart/form-data" style="text-align:center;">
                <label class="file-label" for="profile_file">📷 Choose Picture</label>
                <input type="file" id="profile_file" name="profile_image" accept=".jpg,.jpeg,.png" onchange="showFileName(this); this.form.submit();">
                <p id="file-name">JPG or PNG, max 5MB</p>
                <input type="hidden" name="upload_profile" value="1">
            </form>
        </div>

        <div class="detail-section">
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <?php echo htmlspecialchars($user['name']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <?php echo htmlspecialchars($user['email']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Role:</span>
                <?php
                if ($user['role'] == 'freelancer') echo '💼 Freelancer';
                elseif ($user['role'] == 'client') echo '🏢 Client';
                else echo '-';
                ?>
            </div>
            <?php if ($user['role'] == 'freelancer'): ?>
            <div class="detail-row">
                <span class="detail-label">Skills:</span>
                <?php echo htmlspecialchars($user['skills'] ?? 'None'); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Rate/hour:</span>
                RM<?php echo $user['hourly_rate'] ?? '0'; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($user['role'] == 'freelancer'): ?>
        <a href="FreelancerSkill.php">
            <button class="btn btn-primary" style="width:auto; margin-top:10px;">⭐ Update Skills</button>
        </a>
        <?php endif; ?>
        <a href="RoleGP.php">
            <button class="btn btn-secondary" style="width:auto; margin-top:10px;">🔄 Switch Roles</button>
        </a>
    </div>
</div>
	<script>
		function showFileName(input) {
		    var name = input.files[0] ? input.files[0].name : 'JPG or PNG, max 5MB';
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