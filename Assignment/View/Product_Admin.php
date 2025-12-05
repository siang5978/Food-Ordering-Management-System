<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
    <link href="/Assignment/View/CSS/Product_Admin.css" rel="stylesheet" type="text/css"/>
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
    <h1 class="admin-title">Product List</h1>

    <div class="admin-actions">
        <a href="/Assignment/index.php/AddProduct" class="btn btn-add">Add Product</a>
    </div>

    <div class="product-table-container">
        <table class="product-table" id="product-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Volume (ml)</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody id="product-tbody">
                <tr><td colspan="8">Loading products...</td></tr>
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
fetch("/Assignment/index.php/api/product")
  .then(response => response.json())
  .then(data => {
      const tbody = document.getElementById('product-tbody');
      tbody.innerHTML = '';

      if (!data || data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="8">No products found</td></tr>';
          return;
      }

      data.forEach(product => {
          const tr = document.createElement('tr');

          tr.innerHTML = `
              <td>${product.id}</td>
              <td>${product.name}</td>
              <td>${product.description}</td>
              <td>${product.stock}</td>
              <td>RM${parseFloat(product.price).toFixed(2)}</td>
              <td>${product.type}</td>
              <td>${product.type === 'Drink' ? product.volume + ' ml' : '-'}</td>
              <td class="control-buttons">
                  <a href="/Assignment/view/ModifyProduct_Admin.php/${encodeURIComponent(product.id)}" class="btn btn-modify">Modify</a>
                  <button class="btn btn-delete" data-id="${product.id}">Delete</button>
              </td>
          `;
          tbody.appendChild(tr);
      });
  })
  .catch(err => {
      const tbody = document.getElementById('product-tbody');
      tbody.innerHTML = '<tr><td colspan="8">Failed to load products</td></tr>';
      console.error(err);
  });
  document.addEventListener('click', async function(e) {
    if (e.target.classList.contains('btn-delete')) {
        const productId = e.target.dataset.id;
        if (!confirm(`Are you sure you want to delete product ${productId}?`)) return;

        try {
            const response = await fetch(`/Assignment/index.php/api/product/${productId}`, { method: 'DELETE' });

            let result;
            try {
                result = await response.json();
            } catch (err) {
                alert('Server returned invalid JSON: ' + (await response.text()));
                return;
            }

            if (response.ok) {
                alert(`Product ${productId} deleted successfully!`);
                e.target.closest('tr').remove();
            } else {
                alert(result.error || 'Failed to delete product');
            }
        } catch (err) {
            alert(err.message);
        }
    }
});
</script>
