<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
    <link href="/Assignment/View/CSS/HistoryModify.css" rel="stylesheet" type="text/css"/>
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
<div class="order-modify-container">
    <h1 class="order-modify-title">Modify Order</h1>
    <div id="order-content">Loading order...</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const pathParts = window.location.pathname.split('/');
    const orderId = pathParts[pathParts.length - 1];
    const container = document.getElementById('order-content');

    try {
        const response = await fetch(`/Assignment/index.php/api/orders/${orderId}`);
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const data = await response.json();
        const order = data.order;

        let html = `<h2>Order #${order.id}</h2>
                    <p><strong>Order Time:</strong> ${order.date}</p>
                    <p><strong>Total:</strong> RM ${Number(order.total).toFixed(2)}</p>
                    <h3>Products</h3>
                    <ul>`;
                    order.items.forEach(item => {
                        html += `<li>${item.productName} (x${item.quantity}) - RM ${Number(item.price).toFixed(2)}</li>`;
                    });
                    html += `</ul>
                             <label for="paymentStatus">Payment Status:</label>
                             <select id="paymentStatus">`;
                    ["Pending","Paid","Failed","Refunded"].forEach(status => {
                        html += `<option value="${status}" ${order.paymentStatus === status ? 'selected' : ''}>${status}</option>`;
                    });
                    html += `</select>
                 <br><br>
                 <button id="saveBtn">Save Changes</button>
                 <a href="/Assignment/View/History_Admin.php">Cancel</a>`;
        container.innerHTML = html;

        document.getElementById('saveBtn').addEventListener('click', async () => {
            const paymentStatus = document.getElementById('paymentStatus').value;
            const updateRes = await fetch(`/Assignment/index.php/api/orders/${orderId}`, {
                method: 'PUT',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({paymentStatus})
            });
            const result = await updateRes.json();
            if (updateRes.ok) {
                alert('Order updated!');
                window.location.href = "/Assignment/view/History_Admin.php";
            } else {
                alert(result.error);
            }
        });

    } catch (err) {
        container.innerHTML = `<p>Order not found or failed to load.</p>`;
        console.error(err);
    }
});
</script>
<footer>
    <p>&copy; <?= date("Y"); ?> YUM FOMS. All rights reserved.</p>
</footer>
</body>
</html>
