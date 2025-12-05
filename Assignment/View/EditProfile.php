<link href="/Assignment/View/CSS/EditProfile.css" rel="stylesheet" type="text/css" />

<div class="edit-profile-container">
    <div class="edit-profile-card">
        <h2>Edit Profile</h2>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form id="editProfileForm" method="POST" action="/Assignment/index.php/UpdateProfile">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user->getUsername()) ?>" required/>

            <label for="email">Email</label>
            <input type="email"  id="email"  name="email"  value="<?= htmlspecialchars($user->getEmail()) ?>" required/>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user->getPhone()) ?>" required/>

            <button type="submit" class="save-btn">Save Changes</button>
        </form>

        <div class="back-link">
            <a href="/Assignment/index.php/Profile">Back to Profile</a>
        </div>
    </div>
</div>
<script>
document.getElementById("editProfileForm").addEventListener("submit", function(e) {
    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();

    let usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
    if (!usernameRegex.test(username)) {
        alert("Username must be 3-20 characters, letters/numbers/underscore only.");
        e.preventDefault();
        return;
    }

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Invalid email format.");
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