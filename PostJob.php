<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <link rel="stylesheet" href="gpCSS.css">
    <link rel="stylesheet" href="PostJob.css">
</head>
<body>
<div class="navbar">
    <span class="nav-item active">Post</span>
    <a href="MainMenu_Browse.php" class="nav-item">Browse</a>
    <a href="MainMenu_MyJobs.php" class="nav-item">My Jobs</a>
    <a href="MainMenu_Profile.php" class="nav-item">Profile</a>
    <a href="logout.php" class="nav-item">Logout</a>
</div>

<div class="container">
    <!-- STEP 1 -->
    <div class="step active" id="step1">
        <h2>What do you need done today?</h2>
        <input type="text" id="title" placeholder="Enter Title">
        <span class="error-message" id="titleError"></span>
        <textarea id="description" placeholder="Enter Description"></textarea>
        <span class="error-message" id="descError"></span>
        <div class="helper">Example: "I need a website for my small business selling clothes"</div>
        <button class="btn primary" onclick="validateStep1()">Next →</button>
    </div>

    <!-- STEP 2 -->
    <div class="step" id="step2">
        <h2>Provide more details</h2>
        <textarea id="details" placeholder="Enter Details"></textarea>
        <span class="error-message" id="detailsError"></span>
        <input type="text" id="question" placeholder="Optional Question">
        <div class="helper">You can leave this empty if not needed</div>
        <button class="btn secondary" onclick="prevStep()">← Previous</button>
        <button class="btn primary" onclick="validateStep2()">Next →</button>
    </div>

    <!-- STEP 3 -->
    <form action="proses_postjob.php" method="POST" id="jobForm">
        <div class="step" id="step3">
            <h2>Finalize your task</h2>
            <input type="hidden" name="title" id="titleHidden">
            <input type="hidden" name="description" id="descHidden">
            <input type="hidden" name="details" id="detailsHidden">
            <input type="text" name="requirement" id="requirement" placeholder="Requirement">
            <span class="error-message" id="reqError"></span>
            <select name="platform" id="platform">
                <option value="">Select Platform</option>
                <option>Online</option>
                <option>In-person</option>
            </select>
            <span class="error-message" id="platformError"></span>
            <input type="datetime-local" name="datetime" id="datetime">
            <span class="error-message" id="dateError"></span>
            <select name="payment_type" id="paymentType">
                <option value="">Payment Type</option>
                <option>One-time</option>
                <option>Per hour</option>
            </select>
            <span class="error-message" id="paymentError"></span>
            <input type="number" name="duration" id="duration" placeholder="Duration (days)">
            <span class="error-message" id="durationError"></span>
            <input type="number" name="amount" id="amount" placeholder="Amount (RM)">
            <span class="error-message" id="amountError"></span>
            <button class="btn secondary" type="button" onclick="prevStep()">← Previous</button>
            <button class="btn secondary" type="button" onclick="cancelForm()">Cancel</button>
            <button class="btn success" type="button" onclick="postTask()">Post</button>
        </div>
    </form>
</div>
<script src="PostJob.js"></script>
</body>
</html>