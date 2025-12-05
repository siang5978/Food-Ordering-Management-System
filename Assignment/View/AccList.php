<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
    <link href="/Assignment/View/CSS/AccList.css" rel="stylesheet" type="text/css"/>
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
<div class="admin-product-container">
    <h1 class="admin-title">Account List</h1>

    <div class="product-table-container">
        <table class="product-table" id="account-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody id="account-tbody">
                <tr><td colspan="7">Loading accounts...</td></tr>
            </tbody>
        </table>
    </div>
</div>
<footer>
    <p>&copy; <?= date("Y"); ?> YUM FOMS. All rights reserved.</p>
</footer>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const tbody = document.getElementById('account-tbody');

    try {
        const response = await fetch('/Assignment/index.php/api/Account');
        if (!response.ok) throw new Error('Failed to fetch accounts');

        const accounts = await response.json();
        tbody.innerHTML = '';

        if (!accounts || accounts.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7">No accounts found</td></tr>';
            return;
        }

        accounts.forEach(account => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${account.id}</td>
                <td>${account.username}</td>
                <td>${account.password}</td>
                <td>${account.email}</td>
                <td>${account.phone}</td>
                <td>${account.role}</td>
                <td class="control-buttons">
                    <a href="/Assignment/view/AccModify.php/${encodeURIComponent(account.id)}" class="btn btn-modify">Modify</a>
                    <button class="btn btn-delete" data-id="${account.id}">Delete</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        tbody.addEventListener('click', async function(e) {
            if (!e.target.classList.contains('btn-delete')) return;

            const id = e.target.getAttribute('data-id');
            if (!confirm('Are you sure you want to delete this account?')) return;

            try {
                const delResponse = await fetch(`/Assignment/index.php/api/Account/${id}`, {
                    method: 'DELETE'
                });

                const result = await delResponse.json();
                if (delResponse.ok) {
                    alert('Account deleted successfully!');
                    e.target.closest('tr').remove();
                } else {
                    alert(result.error || 'Failed to delete account');
                }
            } catch (err) {
                alert(err.message);
            }
        });

    } catch (err) {
        tbody.innerHTML = '<tr><td colspan="7">Failed to load accounts</td></tr>';
        console.error(err);
    }
});
</script>
