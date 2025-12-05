<link href="/Assignment/View/CSS/ChangePassword.css" rel="stylesheet" type="text/css" />

<div class="email-container">
    <h2>Enter Your Email</h2>
    <form action="/Assignment/index.php/SendPasswordReset" method="POST">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="Enter your registered email" required>
        <button type="submit">Submit</button>
    </form>

    <?php if(isset($errorMessage)): ?>
        <div class="message"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
</div>
<script>
document.getElementById('emailForm').addEventListener('submit', function(e) {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/;
    if (!emailPattern.test(email)) {
        e.preventDefault(); 
        alert('Please enter a valid email address.');
        emailInput.focus();
        return false;
    }

});
</script>