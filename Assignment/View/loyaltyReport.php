<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
    <style>
    .loading {
        text-align: center;
        font-size: 18px;
        color: #555;
        margin-bottom: 20px;
    }

    table#reportTable {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin: 0 auto 40px;
    }

    th, td {
        padding: 12px 15px;
        text-align: center;
    }

    thead {
        background-color: #2c3e50;
        color: white;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tbody tr:hover {
        background-color: #dfe6e9;
    }

    td.loyalty-Gold {
        color: #f1c40f;
        font-weight: bold;
    }

    td.loyalty-Silver {
        color: #bdc3c7;
        font-weight: bold;
    }

    td.loyalty-Bronze {
        color: #cd7f32;
        font-weight: bold;
    }
    </style>
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
                    <li><a href="/Assignment/view/History_Admin.php">Loyalty Report</a></li>    
                    <li><a href="/Assignment/view/History_Admin.php">Order History</a></li>    
                    <li><a href="/Assignment/index.php">Leave Admin Panel</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <h1>User Loyalty Report</h1>
    <div class="loading" id="loading">Loading data...</div>
    <table id="reportTable" style="display:none;">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Total Spent</th>
                <th>Orders Count</th>
                <th>Loyalty Level</th>
                <th>Reward Points</th>
            </tr>
        </thead>
        <tbody id="reportBody"></tbody>
    </table>

    <script>
        const apiUrl = "/Assignment/index.php/api/loyaltyReport";

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('reportBody');
                data.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${user.userId}</td>
                        <td>${user.username}</td>
                        <td>${user.totalSpent.toFixed(2)}</td>
                        <td>${user.ordersCount}</td>
                        <td>${user.loyaltyLevel}</td>
                        <td>${user.rewardPoints}</td>
                    `;
                    tbody.appendChild(tr);
                });
                document.getElementById('loading').style.display = 'none';
                document.getElementById('reportTable').style.display = 'table';
            })
            .catch(error => {
                document.getElementById('loading').textContent = 'Error loading data';
                console.error("Error fetching loyalty report:", error);
            });
    </script>
<footer>
    <p>&copy; <?= date("Y"); ?> YUM FOMS. All rights reserved.</p>
</footer>
</body>
</html>
