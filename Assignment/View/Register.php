<link href="/Assignment/View/CSS/Register.css" rel="stylesheet" type="text/css" />

<div class="register-container">
    <div class="register-card">
        <h2>Register</h2>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <form id="registerForm" method="POST" action="/Assignment/index.php/Register/Create">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required/>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required/>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required/>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required/>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" placeholder="Enter your Phone Number" required/>

            <button type="submit" class="register-btn">Register</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="/Assignment/index.php/Login">Login</a>
        </div>
    </div>
</div>

<script>
    document.getElementById("registerForm").addEventListener("submit", function (e) {
        let username = document.getElementById("username").value.trim();
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirm_password").value;
        let email = document.getElementById("email").value.trim();
        let phone = document.getElementById("phone").value.trim();

        let usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        if (!usernameRegex.test(username)) {
            alert("Username must be 3-20 characters, letters/numbers/underscore only.");
            e.preventDefault();
            return;
        }

        let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (!passwordRegex.test(password)) {
            alert("Password must be at least 8 characters, include uppercase, lowercase, and a number.");
            e.preventDefault();
            return;
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            e.preventDefault();
            return;
        }

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            e.preventDefault();
            return;
        }

        let phoneRegex = /^[0-9]{10,15}$/;
        if (!phoneRegex.test(phone)) {
            alert("Phone number must be 10-15 digits.");
            e.preventDefault();
            return;
        }
    });
</script>
