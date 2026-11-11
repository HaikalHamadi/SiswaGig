<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginGP.php");
    exit();
}
include 'connection.php';
$client_id = $_SESSION['user_id'];

$job_id = mysqli_real_escape_string($conn, $_GET['job_id']);
$freelancer_id = mysqli_real_escape_string($conn, $_GET['freelancer_id']);
$payment_type = mysqli_real_escape_string($conn, $_GET['payment_type']);
$amount = $_GET['amount'];
$freelancer_rate = $_GET['freelancer_rate'];

// Ambil info job dan freelancer
$job = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jobs WHERE id='$job_id'"));
$freelancer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$freelancer_id'"));
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Payment</title>
    <link rel="stylesheet" href="gpCSS.css">
    <style>
        .payment-card { background:#f8fafc; border-radius:16px; padding:20px; margin:15px 0; border:1px solid #e2e8f0; }
        .payment-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #e9ecef; }
        .payment-row:last-child { border-bottom:none; font-weight:bold; font-size:18px; color:#0d3b66; }
        .btn-pay { background:linear-gradient(135deg,#ff7b00,#ffaa00); color:white; border:none; border-radius:30px; padding:14px 40px; font-size:16px; font-weight:bold; cursor:pointer; width:100%; margin-top:15px; }
        .btn-pay:hover { transform:translateY(-2px); box-shadow:0 5px 15px rgba(255,123,0,0.3); }
        .hours-input { width:100%; padding:12px; border:1px solid #ddd; border-radius:12px; font-size:16px; margin:10px 0; }
        .total-display { font-size:22px; font-weight:bold; color:#ff7b00; text-align:center; margin:15px 0; }
    </style>
</head>
<body>
<div class="app-container">
    <div class="navbar">
        <a href="MainMenu_Post.php" class="nav-item">📝 Post Job</a>
        <a href="MainMenu_MyJobs.php" class="nav-item">📋 My Job</a>
        <a href="MainMenu_Profile.php" class="nav-item">👤 Profile</a>
        <a href="#" class="nav-item" onclick="return confirmLogout()">🚪 Logout</a>
    </div>

    <div class="card" style="max-width:600px; margin:0 auto;">
        <h2>💳 Payment</h2>

        <div class="payment-card">
            <div class="payment-row">
                <span>📌 Job:</span>
                <span><?php echo htmlspecialchars($job['title']); ?></span>
            </div>
            <div class="payment-row">
                <span>👷 Freelancer:</span>
                <span><?php echo htmlspecialchars($freelancer['name']); ?></span>
            </div>
            <div class="payment-row">
                <span>💼 Payment Type:</span>
                <span><?php echo $payment_type; ?></span>
            </div>
        </div>

        <form action="proses_payment.php" method="POST">
            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
            <input type="hidden" name="freelancer_id" value="<?php echo $freelancer_id; ?>">
            <input type="hidden" name="payment_type" value="<?php echo $payment_type; ?>">

            <?php if ($payment_type == 'Per hour'): ?>
                <!-- Per hour — kena masuk berapa jam -->
                <div style="margin:15px 0;">
                    <label style="font-weight:600; color:#1e6091;">Freelancer Rate: RM<?php echo $freelancer_rate; ?>/hour</label>
                    <br><br>
                    <label style="font-weight:600; color:#1e6091;">How many hours of work are done?</label>
                    <input type="number" name="hours_worked" id="hoursInput" class="hours-input" placeholder="e.g: 3" min="0.5" step="0.5" required onchange="countTotalPerHour(this.value)">
                    <div class="total-display" id="totalDisplay">Total: RM0.00</div>
                    <input type="hidden" name="amount" id="totalAmount" value="0">
                    <input type="hidden" name="freelancer_rate" value="<?php echo $freelancer_rate; ?>">
                </div>
            <?php else: ?>
                <!-- One-time — terus papar amount -->
                <div class="payment-card" style="text-align:center;">
                    <p style="color:#666; margin-bottom:10px;">Amount to be paid:</p>
                    <div class="total-display">RM<?php echo number_format($amount, 2); ?></div>
                </div>
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                <input type="hidden" name="hours_worked" value="0">
            <?php endif; ?>

            <!-- Pilih kaedah bayaran -->
            <label style="display:block; font-weight:600; color:#1e6091; margin:15px 0 8px;">Payment Method:</label>
            <select name="payment_method" style="width:100%; padding:12px; border:1px solid #ddd; border-radius:12px; font-size:14px; margin-bottom:15px;" required>
                <option value="">Select Payment Method</option>
                <option value="Online Banking">🏦 Online Banking</option>
                <option value="e-Wallet">📱 e-Wallet (Touch n Go, GrabPay)</option>
                <option value="Tunai">💵 Cash</option>
            </select>

            <button type="submit" class="btn-pay" <?php echo $payment_type == 'Per hour' ? 'onclick="return checkHours()"' : ''; ?>>
                💳 Confirm Pay
            </button>
        </form>
    </div>
</div>
<script>
var freelancerRate = <?php echo $freelancer_rate ?: 0; ?>;

function countTotalPerHour(hours) {
    var total = hours * freelancerRate;
    document.getElementById('totalDisplay').innerText = 'Total: RM' + total.toFixed(2);
    document.getElementById('totalAmount').value = total.toFixed(2);
}

function checkHours() {
    var hours = document.getElementById('hoursInput') ? document.getElementById('hoursInput').value : 1;
    if (!hours || hours <= 0) {
        alert('Please enter the number of working hours!');
        return false;
    }
    return confirm('Confirm payment of RM' + document.getElementById('totalDisplay').innerText.replace('Total: RM','') + '?');
}

function confirmLogout() {
    if (confirm('Are you sure you want to log out??')) {
        window.location.href = 'logout.php';
    }
    return false;
}
</script>
</body>
</html>