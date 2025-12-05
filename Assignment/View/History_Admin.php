<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Order History</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
    <link href="/Assignment/View/CSS/History_Admin.css" rel="stylesheet" type="text/css"/>
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

<div class="order-history-container">
    <h1 class="order-history-title">Order History</h1>

    <div class="order-history-table-container">
        <table class="order-history-table" id="order-history-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Products</th>
                    <th>Order Time</th>
                    <th>Total Price</th>
                    <th>Payment Status</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody id="order-history-tbody">
                <tr><td colspan="6">Loading orders...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <p>&copy; <?= date("Y"); ?> YUM FOMS. All rights reserved.</p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const tbody = document.getElementById('order-history-tbody');

    try {
        const response = await fetch('/Assignment/index.php/api/orders');
        if (!response.ok) throw new Error('Failed to fetch orders');

        const data = await response.json();

        tbody.innerHTML = '';
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6">No Orders found</td></tr>';
            return;
        }

        data.forEach(record => {
            const tr = document.createElement('tr');

            const productsHTML = record.items.map(item => {
                return `${item.productName} (x${item.item.quantity})`;
            }).join('<br>');

            tr.innerHTML = `
                <td>${record.order.id}</td>
                <td>${productsHTML}</td>
                <td>${record.order.orderDate}</td>
                <td>RM ${parseFloat(record.order.totalAmount).toFixed(2)}</td>
                <td>${record.order.paymentStatus}</td>
                <td class="order-history-control">
                    <a href="/Assignment/view/HistoryModify.php/${record.order.id}" class="order-btn order-btn-view">Modify</a>
                    <button class="order-btn order-btn-delete" data-id="${record.order.id}">Delete</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        tbody.querySelectorAll('.order-btn-view').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                window.location.href = `/Assignment/view/HistoryModify.php/${id}`;
            });
        });

        tbody.querySelectorAll('.order-btn-delete').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;
                if (!confirm('Are you sure you want to delete this order?')) return;

                try {
                    const res = await fetch(`/Assignment/index.php/api/orders/${id}`, { method: 'DELETE' });
                    if (!res.ok) throw new Error('Failed to delete order');

                    alert('Order deleted successfully');
                    btn.closest('tr').remove();
                } catch (err) {
                    alert(err.message);
                }
            });
        });

    } catch (err) {
        tbody.innerHTML = '<tr><td colspan="6">Failed to load orders</td></tr>';
        console.error(err);
    }
});
</script>
</body>
</html>
