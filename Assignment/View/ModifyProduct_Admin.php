<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="/Assignment/View/CSS/Layout.css" rel="stylesheet" type="text/css"/>
    <link href="/Assignment/View/CSS/ModifyProduct_Admin.css" rel="stylesheet" type="text/css" />
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
        <div class="modify-product-container">
            <h2>Modify Product</h2>
            <div class="error-message" id="errorMessage" style="display:none;"></div>

            <form id="modifyProductForm" class="modify-product-form">
                <label for="id">Product ID</label>
                <input type="number" id="id" name="id" readonly/>

                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" placeholder="Enter Product Name" required/>

                <label for="description">Description</label>
                <input type="text" id="description" name="description" placeholder="Enter Product Description" required/>

                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" placeholder="Enter Stock" required/>

                <label for="price">Price</label>
                <input type="number" step="0.01" id="price" name="price" placeholder="Enter Price" required/>

                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="Food">Food</option>
                    <option value="Drink">Drink</option>
                </select>

                <div id="volume-container">
                    <label for="volume">Volume (ml)</label>
                    <input type="number" id="volume" name="volume"/>
                </div>

                <button type="submit">Update</button>
            </form>
        </div>
        <footer>
    <p>&copy; <?= date("Y"); ?> YUM FOMS. All rights reserved.</p>
</footer>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const form = document.getElementById('modifyProductForm');
    const typeSelect = document.getElementById('type');
    const volumeContainer = document.getElementById('volume-container');
    const volumeInput = document.getElementById('volume');
    const errorMessageDiv = document.getElementById('errorMessage');
    const pathParts = window.location.pathname.split('/'); 
    const productId = pathParts[pathParts.length - 1]; 

    try {
        const response = await fetch(`/Assignment/index.php/api/product/${productId}`);
        if (!response.ok) throw new Error("Product not found");

        const product = await response.json();
        document.getElementById('id').value = product.id;
        document.getElementById('name').value = product.name;
        document.getElementById('description').value = product.description;
        document.getElementById('stock').value = product.stock;
        document.getElementById('price').value = product.price;
        typeSelect.value = product.type;
        volumeInput.value = product.type === 'Drink' ? product.volume : '';
        volumeContainer.style.display = product.type === 'Drink' ? 'block' : 'none';
    } catch (error) {
        errorMessageDiv.style.display = 'block';
        errorMessageDiv.textContent = error.message;
        form.style.display = 'none';
        return;
    }

    typeSelect.addEventListener('change', function() {
        if (this.value === 'Drink') {
            volumeContainer.style.display = 'block';
        } else {
            volumeContainer.style.display = 'none';
            volumeInput.value = '';
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        let id = document.getElementById('id').value.trim();
        let name = document.getElementById('name').value.trim();
        let description = document.getElementById('description').value.trim();
        let stock = document.getElementById('stock').value.trim();
        let price = document.getElementById('price').value.trim();
        let type = typeSelect.value;
        let volume = volumeInput.value.trim();

        if (name.length < 3) { alert("Product name must be at least 3 characters."); return; }
        if (description.length < 5) { alert("Description must be at least 5 characters."); return; }
        if (!/^[1-9][0-9]*$/.test(stock)) { alert("Stock must be a whole number greater than 0."); return; }
        if (!/^[0-9]+(\.[0-9]{1,2})?$/.test(price) || parseFloat(price) <= 0) { alert("Price must be a positive number with up to 2 decimal places."); return; }
        if (type === "Drink" && (!/^[1-9][0-9]*$/.test(volume))) { alert("Volume must be a whole number greater than 0 when product type is Drink."); return; }

        const data = {
            id,
            name,
            description,
            stock: parseInt(stock),
            price: parseFloat(price),
            type,
            volume: type === "Drink" ? parseInt(volume) : null
        };

        try {
            const response = await fetch(`/Assignment/index.php/api/product/${productId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                alert("Product updated successfully!");
                window.location.href = "/Assignment/view/Product_Admin.php";
            } else {
                alert(result.errors ? result.errors.join("\n") : result.error);
            }
        } catch (err) {
            alert("An unexpected error occurred: " + err.message);
        }
    });
});

</script>