<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Post Job</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .form-label { display:block; font-weight:600; color:#1e6091; margin-bottom:8px; font-size:15px; }
        .form-input { width:100%; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#333; background:#f8fafc; transition:border 0.2s; margin-bottom:20px; }
        .form-input:focus { outline:none; border-color:#ff7b00; background:white; }
        textarea.form-input { height:100px; resize:vertical; }
        .btn-post { background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; border:none; border-radius:30px; padding:14px 40px; font-size:16px; font-weight:bold; cursor:pointer; transition:0.2s; width:100%; }
        .btn-post:hover { transform:translateY(-2px); box-shadow:0 5px 15px rgba(255,123,0,0.3); }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <span class="nav-item active">📝 Post Job</span>
        <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Job</a>
        <a href="MainMenu_Profile.php" class="nav-item">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card" style="max-width:700px; margin:0 auto;">
        <h2>📝 New Job Post</h2>
        <form action="proses_postjob.php" method="POST">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-input" placeholder="e.g: Build a Website" required>

            <label class="form-label">Description</label>
            <textarea name="description" class="form-input" placeholder="Briefly explain what needs to be done..." required></textarea>

            <label class="form-label">Additional Details</label>
            <textarea name="details" class="form-input" placeholder="More info, specific requirements..."></textarea>

            <label class="form-label">Skill Requirements</label>
            <input type="text" name="requirement" class="form-input" placeholder="e.g: HTML, CSS, JavaScript">

            <label class="form-label">Platform</label>
            <select name="platform" class="form-input" required>
                <option value="">Select Platform</option>
                <option value="Online">Online</option>
                <option value="In-person">In-person</option>
            </select>

            <label class="form-label">Payment Type</label>
            <select name="payment_type" class="form-input" required>
                <option value="">Select Payment Type</option>
                <option value="One-time">One-time</option>
                <option value="Per hour">Per hour</option>
            </select>

            <label class="form-label">Duration</label>
			<input type="text" name="duration" class="form-input" placeholder="e.g: 3 days">

			<label class="form-label">Due Date</label>
<input type="datetime-local" name="datetime" class="form-input" min="<?php echo date('Y-m-d\TH:i', strtotime('+1 day')); ?>" value="<?php echo date('Y-m-d\TH:i', strtotime('+1 day')); ?>" required>
<p style="color:#888; font-size:12px; margin-top:-15px; margin-bottom:20px;">Minimum 1 day from now</p>

			<label class="form-label">Total (RM)</label>
			<input type="number" name="amount" class="form-input" placeholder="0" min="0">
            <button type="submit" class="btn-post">Post Job</button>
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