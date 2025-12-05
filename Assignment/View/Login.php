<link href="/Assignment/View/CSS/Login.css" rel="stylesheet" type="text/css" />

<div class="login-container">
    <div class="login-card">
        <h2>Login</h2>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <form id="loginForm" method="POST" action="/Assignment/index.php/Login/Authenticate">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required/>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required/>

            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="/Assignment/index.php/Register">Register</a>
        </div>
            <div class="register-link">
                Forget Password? <a href="/Assignment/index.php/ChangePass">Change Password</a>
            </div>
    </div>
</div>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value;

    let usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
    if (!usernameRegex.test(username)) {
        alert("Username must be 3-20 characters, letters/numbers/underscore only.");
        e.preventDefault();
        return;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters.");
        e.preventDefault();
        return;
    }
    });
</script>