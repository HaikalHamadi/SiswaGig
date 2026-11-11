<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiswaGIG - Registeration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <div class="logo-box">
            <div class="logo-icon">🎓</div>
            <h1 style="margin-top:15px;">SiswaGIG</h1>
        </div>

        <div class="toggle-container">
            <a href="LoginGP.php" class="toggle-btn">Login</a>
            <a href="#" class="toggle-btn active">Register</a>
        </div>

        <h2>Account Register</h2>
        <form action="proses_register.php" method="POST">
            <div class="form-group">
				<label>Full Name</label>
				<input type="text" name="name" placeholder="Your Name" pattern="[A-Za-z\s]+" title="Letters only, no numbers" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" required>
			</div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Min 4 character" required>
            </div>
            <div class="form-group">
                <label>Account Number</label>
                <input type="text" name="account_no" placeholder="12 digits" maxlength="12" pattern="\d{12}" title="Account number must be exactly 12 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
            </div>
            <div class="form-group">
                <input type="checkbox" name="terms" id="terms" style="width:auto;" required>
                <label for="terms" style="display:inline; margin-left:8px;">I agree to the Terms & Conditions</label>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</div>
</body>
</html>