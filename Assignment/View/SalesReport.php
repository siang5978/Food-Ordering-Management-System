<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
    <style>
        #reportTable {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        #reportTable th, #reportTable td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        #reportTable th {
            background-color: #f4f4f4;
        }

        #reportTable tr:nth-child(even) {
            background-color: #fafafa;
        }

        #loading {
            text-align: center;
            font-size: 18px;
            margin: 20px;
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
                        <li><a href="/Assignment/view/loyaltyReport.php">Loyalty Report</a></li>    
                        <li><a href="/Assignment/view/History_Admin.php">Order History</a></li>    
                        <li><a href="/Assignment/index.php">Leave Admin Panel</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <h1 style="text-align:center;">Sales Report</h1>
        <div class="loading" id="loading">Loading data...</div>

        <table id="reportTable" style="display:none;">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Units Sold</th>
                    <th>Total Revenue</th>
                    <th>Orders Count</th>
                </tr>
            </thead>
            <tbody id="reportBody"></tbody>
        </table>
    </div>

    <script>
        // Fetch sales report JSON
        fetch('http://localhost/Assignment/index.php/api/salesReport')
            .then(response => response.json())
            .then(data => {
                const table = document.getElementById('reportTable');
                const tbody = document.getElementById('reportBody');
                tbody.innerHTML = '';

                data.forEach(item => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td>${item.productId}</td>
                        <td>${item.productName}</td>
                        <td>${item.unitsSold}</td>
                        <td>RM${item.totalRevenue}</td>
                        <td>${item.ordersCount}</td>
                    `;
                    tbody.appendChild(tr);
                });

                document.getElementById('loading').style.display = 'none';
                table.style.display = 'table';
            })
            .catch(err => {
                document.getElementById('loading').textContent = 'Failed to load data.';
                console.error(err);
            });
    </script>
<footer>
    <p>&copy; <?= date("Y"); ?> YUM FOMS. All rights reserved.</p>
</footer>
</body>
</html>
