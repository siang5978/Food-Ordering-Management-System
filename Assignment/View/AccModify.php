<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
    <link href="/Assignment/View/CSS/AccModify.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="page-container">
    <header>
        <div class="header-container">
            <h1 class="logo">Admin Panel</h1>
            <nav class="menu">
                <ul>
                    <li><a href="/Assignment/view/Product_Admin.php">Product</a></li>
                    <li><a href="/Assignment/View/SalesReport.php">Sales Report</a><li>
                    <li><a href="/Assignment/view/AccList.php">Account</a></li>
                    <li><a href="/Assignment/view/loyaltyReport.php">Loyalty Report</a></li>    
                    <li><a href="/Assignment/view/History_Admin.php">Order History</a></li>    
                    <li><a href="/Assignment/index.php">Leave Admin Panel</a></li>
                </ul>
            </nav>
        </div>
    </header>
<div class="account-edit-container">
    <div class="account-edit-card">
        <h2>Edit Account</h2>
        <form id="editAccountForm">
            <label for="userid">ID</label>
            <input type="text" id="userid" name="userid" readonly/>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required/>

            <label for="password">Password</label>
            <input type="text" id="password" name="password" required/>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required/>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" required/>

            <label>Role</label>
            <div class="role-options">
                <label><input type="radio" name="role" value="admin"/> Admin</label>
                <label><input type="radio" name="role" value="user"/> User</label>
            </div>

            <button type="submit" class="save-button">Save Changes</button>
        </form>
        <div class="back-link">
            <a href="/Assignment/view/AccList.php">Back to Account List</a>
        </div>
    </div>
</div>
<footer>
    <p>&copy; <?= date("Y"); ?> YUM FOMS. All rights reserved.</p>
</footer>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const pathParts = window.location.pathname.split('/');
    const userId = pathParts[pathParts.length - 1];

    const form = document.getElementById('editAccountForm');

    try {
        const response = await fetch(`/Assignment/index.php/api/Account/${userId}`);
        
        if (!response.ok) {
            throw new Error(`Account not found (HTTP ${response.status})`);
        }

        const user = await response.json();

        document.getElementById('userid').value = user.id ?? '';
        document.getElementById('username').value = user.username ?? '';
        document.getElementById('password').value = user.password ?? '';
        document.getElementById('email').value = user.email ?? '';
        document.getElementById('phone').value = user.phone ?? '';

        const roleInput = document.querySelector(`input[name="role"][value="${user.role}"]`);
        if (roleInput) roleInput.checked = true;
    } catch (err) {
        alert("Failed to load account: " + err.message);
        if (form) form.style.display = 'none';
        window.location.href = "/Assignment/view/AccList.php";
        return;
    }

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const data = {
                username: document.getElementById('username').value.trim(),
                password: document.getElementById('password').value,
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                role: form.querySelector('input[name="role"]:checked')?.value ?? ''
            };

            try {
                const response = await fetch(`/Assignment/index.php/api/Account/${userId}`, {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert("Account updated successfully!");
                    window.location.href = "/Assignment/view/AccList.php";
                } else {
                    if (result.errors && Array.isArray(result.errors)) {
                        alert(result.errors.join("\n"));
                    } else if (result.error) {
                        alert(result.error);
                    } else {
                        alert("Update failed due to unknown error.");
                    }
                }
            } catch (err) {
                alert("Update failed: " + err.message);
            }
        });
    }
});
</script>

