<link href="/Assignment/View/CSS/VerifyOTP.css" rel="stylesheet" type="text/css" />

<div class="otp-container">
    <h2>Verify OTP</h2>
    <form id="otpForm" action="/Assignment/index.php/VerifyOTP" method="POST" onsubmit="return validateOTP()">
        <label for="otp">Enter the 6-digit code sent to your email:</label>
        <input type="text" id="otp" name="otp" maxlength="6" required>
        <button type="submit">Verify</button>
    </form>

    <?php if (isset($errorMessage)): ?>
        <div class="message"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
</div>

<script>
function validateOTP() {
    const otp = document.getElementById("otp").value.trim();

    if (otp === "") {
        alert("OTP cannot be empty.");
        return false;
    }

    if (!/^[0-9]+$/.test(otp)) {
        alert("OTP must contain only numbers.");
        return false;
    }

    if (otp.length !== 6) {
        alert("OTP must be exactly 6 digits.");
        return false;
    }

    return true; 
}
</script>