<link href="/Assignment/View/CSS/Profile.css" rel="stylesheet" type="text/css" />

<div class="profile-container">
    <div class="profile-card">
        <img src="/Assignment/avatar.png" alt="alt"/>
        <p>Name: <?= htmlspecialchars($user->getUserName()) ?></p>
        <p>Email: <?= htmlspecialchars($user->getEmail()) ?></p>
        <p>Phone Number: <?= htmlspecialchars($user->getPhone()) ?></p>
        
        <a href="/Assignment/index.php/EditProfile">Edit Profile</a>
        <a href="/Assignment/index.php/History">Order History</a>
    </div>
    
</div>