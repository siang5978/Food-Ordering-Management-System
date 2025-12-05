<link href="/Assignment/View/CSS/UpdatePassword.css" rel="stylesheet" type="text/css" />

<div class="updatepass-container">
    <h2>Reset Your Password</h2>
    <form action="/Assignment/index.php/updatePass" method="POST">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter new password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="conpassword" name="conpassword" placeholder="Re-enter new password" required>

        <button type="submit">Update Password</button>
    </form>

    <?php if (isset($errorMessage)): ?>
        <div class="message"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
</div>

<script>
function validatePasswordForm() {
    const password = document.getElementById("password").value.trim();
    const conpassword = document.getElementById("conpassword").value.trim();

    if (password === "" || conpassword === "") {
        alert("Password fields cannot be empty.");
        return false;
    }
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
    }
    let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!passwordRegex.test(password)) {
            alert("Password must be at least 8 characters, include uppercase, lowercase, and a number.");
            return false;
    }
        
    if (password !== conpassword) {
        alert("Passwords do not match!");
        return false;
    }

    return true;
}
</script>